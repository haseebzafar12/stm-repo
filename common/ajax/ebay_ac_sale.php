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
            $st = $db->prepare('truncate table stm_ebay_ac');
            $st->execute();
            while (($getData =fgetcsv($file,",")) !== FALSE)
            {
                $row++;
                $num = count($getData);
                if (in_array($row, $skip_row_number))   
                {
                    continue; 
                    
                }else{
                    
                    $customLabel = addslashes($getData[24]);
                    $qty = $getData[26];

                    //$sale = str_replace('£', '', $getData[27]);
                    $sale = preg_replace('/[\£,]/', '', $getData[27]);
                    $amount = intval($qty) * floatval($sale); 

                    $statement = $db->prepare("INSERT into stm_ebay_ac (LwSku,StoreItemID,StoreSKU,Qty,Amount) values(null,'$customLabel','$customLabel','$qty','$amount')");
                    $result = $statement->execute();
                    
                } 
               
            }
          
            fclose($file);
            

            $stm = $db->prepare("UPDATE stm_ebay_ac INNER JOIN stm_linked_item ON stm_linked_item.StoreItemID = stm_ebay_ac.StoreItemID SET stm_ebay_ac.LwSku = stm_linked_item.LwSku WHERE stm_ebay_ac.StoreItemID = stm_linked_item.StoreItemID ");
            $stm->execute();

            $list = $db->prepare("SELECT DISTINCT LwSku,SUM(Qty) AS totalQty,SUM(ROUND(Amount)) AS totalAmount FROM stm_ebay_ac GROUP BY LwSku;");
          
            $list->execute();
            $tempData = $list->fetchAll();
            foreach($tempData as $listTemp){
                $lwsku = $listTemp['LwSku'];
                $totalamount = $listTemp['totalAmount'];
                $totalQty = $listTemp['totalQty'];
                $updateQuery = $db->prepare("UPDATE stm_itemmaster set EbayAcSalesQty = '$totalQty', EbayAcSalesVal = '$totalamount' WHERE LWSKU = '$lwsku'");
                $updateQuery->execute();
            }
            
            $date = date('Y-m-d',strtotime($_POST['EbayAoFlatpickr']));
            $stat = $db->prepare("INSERT into stm_sales (LwSku,StoreItemID,StoreSKU,Qty,Amount,SaleDate,MarketID,StoreID) select LwSku,StoreItemID,StoreSKU,Qty,Amount,'$date' as SaleDate,2 as MarketID,2 as StoreID from stm_ebay_ac");
            $result = $stat->execute();

            $list = $db->prepare("SELECT DISTINCT LwSku,SUM(Qty) AS totalQty,SUM(ROUND(Amount)) AS totalAmount,SaleDate,StoreSKU FROM stm_sales WHERE MarketID = '2' AND StoreID = '2' AND SaleDate ='$date' GROUP BY LwSku");
          
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
                    $res = $db->prepare("UPDATE stm_sale_master set EbayAoQty = '$totalQty',EbayAoVal = '$totalamount' WHERE LwSku = '$lwsku' AND WeekDate = '$date'");
                    $res->execute();
                }
            }
        }
        
    }

?>