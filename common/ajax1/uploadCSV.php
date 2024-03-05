<?php 

ob_start();

session_start();



include('../../smtp/PHPMailerAutoload.php');     

include_once ('../config.php');

include_once ('../db_helper.php');

include_once ('../user.php');

include_once ('../announceClass.php');



$dbcon = new Database();

$db = $dbcon->getConnection();

$DB_HELPER_CLASS = new db_helper($db);

$objUser = new User($db);

$objAnnouce = new announceClass($db);



$session_id = "";

if(isset($_SESSION['user'])){

$session_id = $_SESSION['user'];

}else if(isset($_SESSION['id'])){

$session_id = $_SESSION['id'];

}

    if(!empty($_FILES) AND !empty($_POST['FBMFlatpickr'])){

        

        $filename=$_FILES["fileName"]["tmp_name"];

        $output = '';

        if($_FILES["fileName"]["size"] > 0)

        {

            

            $row = 0;

            $output = "";

            $skip_row_number = array("1");

            $file = fopen($filename, "r");

            $st = $db->prepare('truncate table stm_lw_data');

            $st->execute();

            while (($getData =fgetcsv($file,",")) !== FALSE)

            {

                $row++;

                $num = count($getData);

                if (in_array($row, $skip_row_number))   

                {

                    continue; 

                    

                }else{

                    $storeID = "";

                    if(floatval($getData[2])){

                        $storeID = intval($getData[2]);

                    }else{

                        $storeID = $getData[2];

                    }



                    $itemID = "";

                    if(floatval($getData[2])){

                        $itemID = intval($getData[2]);

                    }else{

                        $itemID = $getData[2];

                    }



                    $barcode = "";

                    if(floatval($getData[6])){

                        $barcode = intval($getData[6]);

                    }else{

                        $barcode = addslashes($getData[6]);

                    }

                    $type = "FBM";



                    // $query_1 = 'LOAD DATA LOCAL INFILE "'.$file_location.'" INTO TABLE stm_fba_inv_tmp FIELDS TERMINATED BY "," LINES TERMINATED BY "\r\n"  IGNORE 1 LINES (@column1,@column2,@column3) SET StoreSKU = @column1, StoreItemID = @column2, Stock = @column3, LwSku = NULL';

                    

                    $statement = $db->prepare("INSERT into stm_lw_data (StockSKU,StockItemTitle,ChannelId,ChannelSKU,Source,SubSource,BarcodeNumber,DefaultSupplier,supplierID,StockType,Qty,ItemID) values('".$getData[0]."','".addslashes($getData[1])."','".$storeID."','".addslashes($getData[3])."','".addslashes($getData[4])."','".addslashes($getData[5])."','".$barcode."','".addslashes($getData[7])."',0,'$type','".$getData[8]."','$itemID')");

                    $statement->execute();

                        

                    

                } 

               

            }

          

            fclose($file);

            

            $q = $db->prepare("UPDATE stm_lw_data SET ChannelId = ChannelSKU WHERE Source = 'EBAY'");



            $q->execute();



            $db->prepare("UPDATE stm_lw_data SET

                    Source = CASE Source WHEN 'EBAY' THEN 'eBay' 

                                  WHEN 'AMAZON' THEN 'Amazon'

                                  WHEN 'WOOCOMMERCE' THEN 'Website'

                    ELSE source

                    END,

                    subsource = CASE subsource WHEN 'EBAY4' THEN 'OnlineStreet'

                                     WHEN 'EBAY5' THEN 'AmajOnline'

                                     WHEN 'https://onlinestreet.co.uk' THEN 'OnlineStreet'

                                     WHEN 'https://amajcandles.co.uk' THEN 'AmajCandles'

                                     WHEN 'Online Street' THEN 'OnlineStreet'

                                     WHEN 'Quality_Clearance' THEN 'QualityClearance'

                    ELSE subsource

                    END")->execute();



                     $quer = $db->prepare("INSERT ignore into stm_supplier (supplierName) select DefaultSupplier from stm_lw_data");

                     $quer->execute(); 



                    $data = $db->prepare("UPDATE stm_lw_data INNER JOIN stm_channels on stm_channels.channelName = stm_lw_data.Source SET stm_lw_data.marketID = stm_channels.id WHERE stm_lw_data.Source = stm_channels.channelName");

                    $data->execute();



                    $me = $db->prepare("UPDATE stm_lw_data INNER JOIN stm_stores on stm_stores.storeName = stm_lw_data.SubSource SET stm_lw_data.storeID = stm_stores.id WHERE stm_lw_data.marketID = stm_stores.storeChannelID");

                    $me->execute();



                    $up = $db->prepare("UPDATE stm_lw_data INNER JOIN stm_supplier on stm_supplier.supplierName = stm_lw_data.DefaultSupplier SET stm_lw_data.supplierID = stm_supplier.id WHERE stm_lw_data.DefaultSupplier = stm_supplier.supplierName");

                    $up->execute();

                  

                  $list = $db->prepare("SELECT DISTINCT StockSKU,StockItemTitle,BarcodeNumber,supplierID,MAX(Qty) AS totalQty FROM stm_lw_data GROUP BY StockSKU");

                  

                  $list->execute();

                  $tempData = $list->fetchAll();

                  foreach($tempData as $listTemp){



                      $date = date('Y-m-d',strtotime($_POST['FBMFlatpickr']));

                      $stockSKU = $listTemp['StockSKU'];

                      $stockItemTitle = addslashes($listTemp['StockItemTitle']);

                      $barCode = addslashes($listTemp['BarcodeNumber']);

                      $supplierID = $listTemp['supplierID'];

                      $FBMQty  = $listTemp['totalQty'];

                      

                      $query = $db->prepare("INSERT into stm_itemmaster (LWSKU,ItemName,ItemBarCode,SupplierID,FBAStockOs,FBAStockQc,FBMStock,TotalStock,AmzOsSalesQty,AmzQcSalesQty,EbayOsSalesQty,EbayAcSalesQty,AmzOsSalesVal,AmzQcSalesVal,EbayOsSalesVal,EbayAcSalesVal,ItemStatus,ItemStockDate) values('$stockSKU','$stockItemTitle','$barCode','$supplierID',0,0,'$FBMQty',0,null,null,null,null,null,null,null,null,0,'$date')");

                      

                      $query->execute();



                  }



                  $query = $db->prepare("INSERT ignore into stm_listing (ItemMasterID,LwSku,ChannelID,StoreID,StoreItemID,StoreSKU,StockType,ItemID) select 0 as ItemMasterID,StockSKU,marketID,storeID,ChannelId,ChannelSKU,StockType,ItemID from stm_lw_data");

                      $query->execute();                        



                   $que = $db->prepare("INSERT IGNORE INTO stm_linked_item (StoreItemID,LwSku) SELECT StoreItemID,LwSku FROM stm_listing");

                   $que->execute();



                   $stm = $db->prepare("UPDATE stm_listing INNER JOIN stm_itemmaster ON stm_itemmaster.LWSKU =stm_listing.LwSku SET stm_listing.ItemMasterID = stm_itemmaster.id WHERE stm_listing.LwSku = stm_itemmaster.LWSKU ");



                   $stm->execute();



        }  

    }else{

        $output .= "Fields are required";

    }

    echo $output;

?>