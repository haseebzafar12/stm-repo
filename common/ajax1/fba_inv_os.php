<?php 
ob_start();
session_start();

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
    
    if(!empty($_FILES) AND !empty($_POST['FBA_amz_os'])){
        $output = "";
        $filename=$_FILES["fileName"]["tmp_name"];
        
         if($_FILES["fileName"]["size"] > 0)
         {
            $row = 0;
            $skip_row_number = array("1");
            $file = fopen($filename, "r");
            $st = $db->prepare('truncate table stm_fba_os');
            $st->execute();
            while (($getData =fgetcsv($file, ",")) !== FALSE)
            {
                $row++;
                $num = count($getData);
                if (in_array($row, $skip_row_number))   
                {
                    continue; 
                    
                }else{
                    $storeSKU = addslashes($getData[0]);
                    $StoreItemID = addslashes($getData[2]);
                    $Stock = $getData[5];

                    if($getData[4] == "SELLABLE"){
                      $statement = $db->prepare("INSERT into stm_fba_os (StoreSKU,StoreItemID,Stock,LwSku) values('$storeSKU','$StoreItemID','$Stock',NULL)");
                      $result = $statement->execute();
                       
                    }
                } 
               
            }
          
            fclose($file);

            $que = $db->prepare("INSERT IGNORE INTO stm_linked_item (StoreItemID,LwSku) SELECT StoreItemID,LwSku FROM stm_fba_os");
            $que->execute();

            $stm = $db->prepare("UPDATE stm_fba_os INNER JOIN stm_linked_item ON stm_linked_item.StoreItemID = stm_fba_os.StoreItemID SET stm_fba_os.LwSku = stm_linked_item.LwSku WHERE stm_fba_os.StoreItemID = stm_linked_item.StoreItemID ");
            $stm->execute();

            $query = $db->prepare("INSERT IGNORE into stm_listing (ItemMasterID,LwSku,ChannelID,StoreID,StoreItemID,StoreSKU,StockType) select 0 as ItemMasterID,LwSku,1 as ChannelID,5 as StoreID,StoreItemID,StoreSKU,'FBA' as StockType from stm_fba_os");
            $query->execute();

            $date = date('Y-m-d',strtotime($_POST['FBA_amz_os']));

            $query = $db->prepare("INSERT IGNORE into stm_itemmaster(LWSKU,ItemName,ItemBarCode,SupplierID,FBAStockOs,FBAStockQc,FBMStock,TotalStock,AmzOsSalesQty,AmzQcSalesQty,EbayOsSalesQty,EbayAcSalesQty,AmzOsSalesVal,AmzQcSalesVal,EbayOsSalesVal,EbayAcSalesVal,ItemStatus,ItemStockDate) select DISTINCT LwSku,null as ItemName,null as ItemBarCode,0 as SupplierID,0 as FBAStockOs,0 as FBAStockQc,0 as FBMStock,0 as TotalStock,null as AmzOsSalesQty,null as AmzQcSalesQty,null as EbayOsSalesQty,null as EbayAcSalesQty,null as AmzOsSalesVal,null as AmzQcSalesVal,null as EbayOsSalesVal,null as EbayAcSalesVal,0 as ItemStatus,'$date' as ItemStockDate from stm_fba_os");
            $query->execute();

            $dbs = $db->prepare("SELECT LwSku,SUM(stock) as totalStock FROM stm_fba_os WHERE LwSku != '' GROUP BY LwSku");
            $dbs->execute();

            $result = $dbs->fetchAll();

            foreach($result as $row){
                $fbastock = $row['totalStock'];
                $lwskus = $row['LwSku'];
                $it = $db->prepare("UPDATE stm_itemmaster set FBAStockOs = '$fbastock',TotalStock= (FBAStockOs+FBAStockQc+FBMStock) WHERE LWSKU = '$lwskus'");
                $it->execute();
            }
        }
                    
    }else{
        $output .= "Both Fields Are Required";
    }
    echo $output;
?>