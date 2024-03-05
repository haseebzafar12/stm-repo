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
    if(!empty($_FILES)){

        $filename=$_FILES["fileName"]["tmp_name"];
        $output = '';
        if($_FILES["fileName"]["size"] > 0)
        {
            
            $row = 0;
            $skip_row_number = array("1");
            $file = fopen($filename, "r");

            $st = $db->prepare('truncate table stm_amz_os');
            $st->execute();
            
            while (($getData =fgetcsv($file,",")) !== FALSE)
            {
                $row++;
                $num = count($getData);
                if (in_array($row, $skip_row_number))   
                {
                    continue; 
                    
                }else{
                    
                    $StoreItemID = $getData[1];
                    $StoreSKU = addslashes($getData[3]);

                    $Qty = intval($getData[14])+intval($getData[15]);
                    
                    $orderPsale = $getData[18];
                    $orderB = $getData[19];

                    $orderSale = preg_replace('/[\£,]/', '', $orderPsale);
                    $orderSaleBB = preg_replace('/[\£,]/', '', $orderB);

                    $amount = floatval($orderSale) + floatval($orderSaleBB);
                    
                    $statement = $db->prepare("INSERT into stm_amz_os (LwSku,StoreItemID,StoreSKU,Qty,Amount) values(null,'$StoreItemID','$StoreSKU','$Qty','$amount')");
                    $result = $statement->execute();  
                    
                } 
               
            }
          
            fclose($file);

            $stm = $db->prepare("UPDATE stm_amz_os INNER JOIN stm_linked_item ON stm_linked_item.StoreItemID = stm_amz_os.StoreItemID SET stm_amz_os.LwSku = stm_linked_item.LwSku WHERE stm_amz_os.StoreItemID = stm_linked_item.StoreItemID ");
            $stm->execute();

            // $list = $db->prepare("SELECT DISTINCT LwSku,SUM(Qty) AS totalQty,SUM(ROUND(Amount)) AS totalAmount FROM stm_amz_os GROUP BY LwSku;");
          
            // $list->execute();
            // $tempData = $list->fetchAll();
            // foreach($tempData as $listTemp){
            //     $lwsku = $listTemp['LwSku'];
            //     $totalamount = $listTemp['totalAmount'];
            //     $totalQty = $listTemp['totalQty'];
            //     $updateQuery = $db->prepare("UPDATE stm_itemmaster set AmzOsSalesQty = '$totalQty', AmzOsSalesVal = '$totalamount' WHERE LWSKU = '$lwsku'");
            //     $updateQuery->execute();
            // }

            $date = date('Y-m-d',strtotime($_POST['AmzOsFlatpickr']));
            
            $stat = $db->prepare("INSERT into stm_sales (LwSku,StoreItemID,StoreSKU,Qty,Amount,SaleDate,MarketID,StoreID) select LwSku,StoreItemID,StoreSKU,Qty,Amount,'$date' as SaleDate,1 as MarketID,5 as StoreID from stm_amz_os");
            $result = $stat->execute();

            // $list = $db->prepare("SELECT DISTINCT LwSku,SUM(Qty) AS totalQty,SUM(ROUND(Amount)) AS totalAmount,SaleDate,StoreSKU FROM stm_sales WHERE MarketID = '1' AND StoreID = '5' GROUP BY LwSku;");
          
            // $list->execute();
            // $tempData = $list->fetchAll();
            // foreach($tempData as $listTemp){
                
            //     $lwsku = $listTemp['LwSku'];
            //     $totalamount = $listTemp['totalAmount'];
            //     $totalQty = $listTemp['totalQty'];
            //     $StoreSKU = $listTemp['StoreSKU'];
            //     $SaleDate = $listTemp['SaleDate'];

                // $data = $DB_HELPER_CLASS->SingleDataWhere('stm_sale_master',"LwSku = '$lwsku' AND WeekDate = '$SaleDate'");
                
                // if($data['LwSku'] == $lwsku AND $data['WeekDate'] == $SaleDate){
                //     $res = $db->prepare("UPDATE stm_sale_master set AmzOsQty = '$totalQty' AND AmzOsVal = '$totalamount' WHERE LwSku = '$lwsku' AND WeekDate = '$SaleDate'");
                //     $res->execute();
                // }else{
                    $res = $db->prepare("INSERT ignore into stm_sale_master (LwSku,WeekDate,AmzOsQty,AmzOsVal,AmzQcQty,AmzQcVal,EbayOsQty,EbayOsVal,EbayAoQty,EbayAoVal) SELECT DISTINCT LwSku,SaleDate,SUM(Qty),SUM(ROUND(Amount)),0,0,0,0,0,0 FROM stm_sales WHERE MarketID = '1' AND StoreID = '5' AND SaleDate = '$date' GROUP BY LwSku");
                       
                    $res->execute();
                //}

            //}
            
        }
        
    }

?>