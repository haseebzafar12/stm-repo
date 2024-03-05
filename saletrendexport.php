<?php ob_start();
session_start();
  header("Content-Type: application/xlsx");   
  header("Content-Disposition: attachment; filename=saletrend.xls");  
  header("Pragma: no-cache"); 
  header("Expires: 0");
      
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');

   $dbcon = new Database();
   $db = $dbcon->getConnection();
   $objUser = new user($db); 
   $db_helper = new db_helper($db);

   $session_id = "";
   if(isset($_SESSION['user'])){
    $session_id = $_SESSION['user'];
   }else if(isset($_SESSION['id'])){
    $session_id = $_SESSION['id'];
   }
   // $session_id = $_SESSION['id'];
    $tb = "stm_users";
    $wh = "id = '$session_id'";
    $session_data = $db_helper->SingleDataWhere($tb, $wh);

if(isset($_GET['supplierid'])){
  $query = "SELECT * FROM stm_itemmaster WHERE SupplierID = '".$_GET['supplierid']."' Order By TotalStock DESC";
}else{
  $query = "SELECT * FROM stm_itemmaster Order By TotalStock DESC";  
} 

$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$output ='<table class="table table-striped table-sm">';
        $output .= '<thead style="background-color:#d9e6f2;">';
        
        $list = $db->prepare("select DISTINCT WeekDate from stm_sale_master ORDER BY WeekDate DESC");
        $list->execute();
        $dataWeek = $list->fetchAll();
        $output .= '<tr>';
          $output .= '<th id="headings" colspan="6"></th><th id="headings" colspan="4" style="border-left:#a6a6a6 solid 1px;
            border-right:#a6a6a6 solid 1px;"></th>';
        
        foreach($dataWeek as $list){ 
            $output .= '<th id="uper_rows" colspan="9">'.date('d M Y',strtotime($list['WeekDate'])).'</th>';          
        }  
          $output .= '</tr>';
          $output .= '<tr>';
          $output .= '<th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="uper_rows_listing" colspan="4">Listed At</th>';
        foreach($dataWeek as $list){ 
              $output .= '<th id="uper_rows" colspan="2">Amz (OS)</th>';
              $output .= '<th colspan="2" id="uper_rows">Amz (QC)</th>';
              $output .= '<th colspan="2" id="uper_rows">Ebay (OS)</th>';
              $output .= '<th colspan="2" id="uper_rows">Ebay (AO)</th>';
        } 
        $output .= '</tr>';
        $output .= '<tr class="row_inv">';
            $output .= '<th id="headings3">LW.SKU</th>';
            $output .= '<th id="headings3">Item Name</th>';
            $output .= '<th id="headings3">Supplier</th>';
            $output .= '<th id="headings3">SUPERVISOR</th>';
            $output .= '<th id="headings3">Barcode</th>';
            $output .= '<th id="headings3">Stock</th>';
            $output .= '<th id="uper_rows">Amz (OS)</th>';
            $output .= '<th id="uper_rows">Amz (QC)</th>';
            $output .= '<th id="uper_rows">Ebay (OS)</th>';
            $output .= '<th id="uper_rows">Ebay (AO)</th>';
            
            foreach($dataWeek as $list){  
              $output .= '<th id="uper_rows">Qty</th>';
              $output .= '<th id="uper_rows">Value</th>';
              $output .= '<th id="uper_rows">Qty</th>';
              $output .= '<th id="uper_rows">Value</th>';
              $output .= '<th id="uper_rows">Qty</th>';
              $output .= '<th id="uper_rows">Value</th>';
              $output .= '<th id="uper_rows">Qty</th>';
              $output .= '<th id="uper_rows">Value</th>';
            }

        $output .= '</tr></thead>';
  foreach($result as $row)
  {
    
    $string = strip_tags($row['ItemBarCode']);
    if (strlen($string) > 15) {
        // truncate string
        $stringCut = substr($string, 0, 15);
        $endPoint = strrpos($stringCut, ' ');

        $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    $ItemName = strip_tags($row['ItemName']);
    if (strlen($ItemName) > 15) {
        // truncate string
        $stringCut = substr($ItemName, 0, 15);
        $endPoint = strrpos($stringCut, ' ');

        $ItemName = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $ItemName .= '...';
    }
    $supps = $db_helper->SingleDataWhere("stm_supplier","id = '".$row['SupplierID']."'");
    $userSup = $db_helper->SingleDataWhere('stm_users','id = "'.$supps['userID'].'"');

        $EbayOsVal = "";
        $EbayAoVal = "";
        $AmzOsVal = "";
        $AmzQcVal = "";

        $EbayOsValGr = "";
        $EbayAoValGr = "";
        $AmzOsValGr = "";
        $AmzQcValGr = "";

        $AmzOsQty = "";
        $AmzQcQty = "";
        $EbayOsQty = "";
        $EbayAoQty = "";

        $AmzOsQtyGr = "";
        $AmzQcQtyGr = "";
        $EbayOsQtyGr = "";
        $EbayAoQtyGr = "";
        $date = "";
        
        $amz_os_qty = "";
        $amz_os_val = "";
        $amz_qc_qty = "";
        $amz_qc_val = "";
        $ebay_os_qty = "";
        $ebay_os_val = "";
        $ebay_ao_qty = "";
        $ebay_ao_val = "";

        $output .= '<tr>';
        $output .= '<td><a class="lw_sku_inv" data-id="'.$row['LWSKU'].'" style="color:#000; text-decoration:underline; cursor:pointer;">'.$row['LWSKU'].'</td>';
        $output .= '<td><a title="'.$row['ItemName'].'">'.$ItemName.'</a></td>';
        $output .= '<td>'.$supps['supplierName'].'</td>';
        $output .= '<td>'.$userSup['displayName'].'</td>';
        $output .= '<td><a title="'.$row['ItemBarCode'].'">'.$string.'</a></td>';
        $output .= '<td align="right">'.$row['TotalStock'].'</td>';
        if($row['AmzOsListed'] == 0){
          $AmzOsValGr .= "style=background-color:#ffcccc;";
        }else{
          $AmzOsValGr .= "style=background-color:#b3e6cc;";
        }

        if($row['EbayOsListed'] == 0){
          $EbayOsValGr .= "style=background-color:#ffcccc;";
        }else{
          $EbayOsValGr .= "style=background-color:#b3e6cc;";
        }

        if($row['EbayAoListed'] == 0){
          $EbayAoValGr .= "style=background-color:#ffcccc;";
        }else{
          $EbayAoValGr .= "style=background-color:#b3e6cc;";
        }

        if($row['AmzQcListed'] == 0){
          $AmzQcValGr .= "style=background-color:#ffcccc;";
        }else{
          $AmzQcValGr .= "style=background-color:#b3e6cc;";
        }

        
        $output .= '<td align="right"'.$AmzOsValGr.'></td>';
        $output .= '<td align="right"'.$AmzQcValGr.'></td>';

        $output .= '<td align="right"'.$EbayOsValGr.'></td>';
        $output .= '<td align="right"'.$EbayAoValGr.'></td>';
        
          $list = $db->prepare("select * from stm_sale_master where LwSku = '".$row['LWSKU']."' ORDER BY WeekDate DESC");

          $list->execute();
          $res = $list->fetchAll();
          
          foreach($res as $data){

           $date = $data['WeekDate'];
           
              if($data['AmzOsVal']){
                $AmzOsVal = $data['AmzOsVal'];
              }else{
                $AmzOsVal = "-";
              }

              if($data['EbayOsVal']){
                $EbayOsVal = $data['EbayOsVal'];
              }else{
                $EbayOsVal = "-";
              }

              if($data['AmzQcVal']){
                $AmzQcVal = $data['AmzQcVal'];
              }else{
                $AmzQcVal = "-";
              }

              if($data['EbayAoVal']){
                $EbayAoVal = $data['EbayAoVal'];
              }else{
                $EbayAoVal = "-";
              }
             
              //Quantities
              
              
              if($data['AmzOsQty'] == true){
                $AmzOsQty = $data['AmzOsQty'];
              }else{
                $AmzOsQty = "-";
              }

              
              
              if($data['AmzQcQty'] == true){
                $AmzQcQty = $data['AmzQcQty'];
              }else{
                $AmzQcQty = "-";
              }
              
              
             
              if($data['EbayOsQty'] == true){
                $EbayOsQty = $data['EbayOsQty'];
              }else{
                $EbayOsQty = "-";
              }

              
              if($data['EbayAoQty'] == true){
                $EbayAoQty = $data['EbayAoQty'];
              }else{
                $EbayAoQty = "-";
              }
               
              $output .= '<td align="right">'.$AmzOsQty.'</td>';
              $output .= '<td align="right">'.$AmzOsVal.'</td>';

              $output .= '<td align="right">'.$AmzQcQty.'</td>';
              $output .= '<td align="right">'.$AmzQcVal.'</td>';

              $output .= '<td align="right">'.$EbayOsQty.'</td>';
              $output .= '<td align="right">'.$EbayOsVal.'</td>';

              $output .= '<td align="right">'.$EbayAoQty.'</td>';
              $output .= '<td align="right">'.$EbayAoVal.'</td>';   
          }//2nd foreach ended 
       
       
    $output .= '</tr>';
    
  }//first foreach ended

echo $output;
?>