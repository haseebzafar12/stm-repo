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

            $st = $db->prepare('truncate table stm_amz_qc');
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
                    
                    $statement = $db->prepare("INSERT into stm_amz_qc (LwSku,StoreItemID,StoreSKU,Qty,Amount) values(null,'$StoreItemID','$StoreSKU','$Qty','$amount')");
                    $result = $statement->execute();  
                    
                } 
               
            }
          
            fclose($file);

            $stm = $db->prepare("UPDATE stm_amz_qc INNER JOIN stm_linked_item ON stm_linked_item.StoreItemID = stm_amz_qc.StoreItemID SET stm_amz_qc.LwSku = stm_linked_item.LwSku WHERE stm_amz_qc.StoreItemID = stm_linked_item.StoreItemID ");
            $stm->execute();

            // $list = $db->prepare("SELECT DISTINCT LwSku,SUM(Qty) AS totalQty,SUM(ROUND(Amount)) AS totalAmount FROM stm_amz_qc GROUP BY LwSku;");
          
            // $list->execute();
            // $tempData = $list->fetchAll();
            // foreach($tempData as $listTemp){
            //     $lwsku = $listTemp['LwSku'];
            //     $totalamount = $listTemp['totalAmount'];
            //     $totalQty = $listTemp['totalQty'];
            //     $updateQuery = $db->prepare("UPDATE stm_itemmaster set AmzQcSalesQty = '$totalQty', AmzQcSalesVal = '$totalamount' WHERE LWSKU = '$lwsku'");
            //     $updateQuery->execute();
            // }

            $date = date('Y-m-d',strtotime($_POST['AmzQcFlatpickr']));
            
            $stat = $db->prepare("INSERT into stm_sales (LwSku,StoreItemID,StoreSKU,Qty,Amount,SaleDate,MarketID,StoreID) select LwSku,StoreItemID,StoreSKU,Qty,Amount,'$date' as SaleDate,1 as MarketID,6 as StoreID from stm_amz_qc");
            $result = $stat->execute();

            $list = $db->prepare("SELECT DISTINCT LwSku,SUM(Qty) AS totalQty,SUM(ROUND(Amount)) AS totalAmount,SaleDate,StoreSKU FROM stm_sales WHERE MarketID = '1' AND StoreID = '6' AND SaleDate ='$date' GROUP BY LwSku");
          
            $list->execute();
            $tempData = $list->fetchAll();
            foreach($tempData as $listTemp){
                
                $lwsku = $listTemp['LwSku'];
                $totalamount = $listTemp['totalAmount'];
                $totalQty = $listTemp['totalQty'];
                $StoreSKU = $listTemp['StoreSKU'];
                $SaleDate = $listTemp['SaleDate'];

                $data = $DB_HELPER_CLASS->SingleDataWhere('stm_sale_master',"LwSku = '$lwsku' AND WeekDate = '$date'");
                
                if($data['LwSku'] == $lwsku AND $data['WeekDate'] == $date){
                    $res = $db->prepare("UPDATE stm_sale_master set AmzQcQty = '$totalQty',AmzQcVal = '$totalamount' WHERE LwSku = '$lwsku' AND WeekDate = '$date'");
                    $res->execute();
                }

            }
            
        }
        
    }

?>