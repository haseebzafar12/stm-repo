<?php ob_start();
session_start();
   
   include_once ('../config.php');
   include_once ('../user.php');
   include_once ('../db_helper.php');

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
    
$limit = '500';
$page = 1;

  if($_POST['page'] > 1)
  {
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
  }else
  {
    $start = 0;
  } 

$query = "select * from stm_itemmaster";

  if($_POST['supplier'] != '' AND $_POST['inv'] == '' AND $_POST['stock'] == '' AND $_POST['supervisor'] == '')
  {
    $query .= ' WHERE SupplierID = "'.$_POST['supplier'].'"';

  }else if($_POST['supplier'] == '' AND $_POST['inv'] == '' AND $_POST['stock'] != '' AND $_POST['supervisor'] == ''){

    if($_POST['stock'] == '1'){
     
     $query .= ' WHERE TotalStock > 0 '; 
    
    }else if ($_POST['stock'] == '0') {
      
      $query .= ' WHERE TotalStock = "0" ';
    
    }
  
  }else if($_POST['supplier'] == '' AND $_POST['inv'] == '' AND $_POST['stock'] == '' AND $_POST['supervisor'] != ''){
    
    $stmt = $db->prepare('SELECT * from stm_supplier WHERE userID = "'.$_POST['supervisor'].'" ');
    $stmt->execute();
    $user = $stmt->fetchAll();
    if($user){
      $supplierID = "";
      foreach($user as $users){
        
        $supplierID .= $users['id'].',';
         
      }
      $suppliers = rtrim($supplierID,",");
      $query .= " WHERE SupplierID IN ($suppliers)"; 
    }else{
      $query = '';
    }

  }else if($_POST['inv'] !='' AND $_POST['supplier'] == '' AND $_POST['stock'] == '' AND $_POST['supervisor'] == ''){

    $query = 'SELECT DISTINCT t1.id,t1.* from stm_itemmaster t1 LEFT JOIN stm_listing t2 On t2.LwSku = t1.LWSKU WHERE t1.LWSKU LIKE "%'.$_POST['inv'].'%" OR t1.ItemName LIKE "%'.$_POST['inv'].'%" OR t1.ItemBarCode LIKE "%'.$_POST['inv'].'%" OR t2.StoreItemID LIKE "%'.$_POST['inv'].'%" OR t2.StoreSKU LIKE "%'.$_POST['inv'].'%" OR t2.ItemID LIKE "%'.$_POST['inv'].'%"';
  
  }

 
if($_POST['inv'] == ''){
  $query .= ' Order By TotalStock DESC';
} 
$filter_query = $query . ' LIMIT '.$start.', '.$limit.'';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output ='<table class="table table-striped table-sm">';
        $output .= '<thead style="background-color:#d9e6f2;">';
        
        $list = $db->prepare("select DISTINCT WeekDate from stm_sale_master ORDER BY WeekDate DESC");

        $list->execute();
        $dataWeek = $list->fetchAll();
        $output .= '<tr>';
          $output .= '<th id="headings" colspan="6"></th><th id="headings" colspan="4" style="border-left:#a6a6a6 solid 1px; text-align:center;
            border-right:#a6a6a6 solid 1px; color:#555;" id="uper_rows_listing">Listed On</th>';
        
        foreach($dataWeek as $list){ 
            $output .= '<th id="uper_rows" colspan="8">'.date('d M Y',strtotime($list['WeekDate'])).'</th>';          
        }  
          $output .= '</tr>';
          $output .= '<tr>';
          $output .= '<th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th>
          <th id="uper_rows_listing" colspan="4">
            <ul id="legends">
              <li><div style="background-color:#b3e6cc; height: 10px; width: 10px; float: left;"></div>&nbsp&nbspLISTED</li>
              <li><div style="background-color:#ffcccc; height: 10px; width: 10px; float: left;"></div>&nbsp&nbspNOT LISTED</li>
              
            </ul>
          </th>';
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
if($total_data > 0)
{
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
          if($res){
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
          }else{
            foreach($dataWeek as $list){  
              $output .= '<td align="right">-</td>';
              $output .= '<td align="right">-</td>';

              $output .= '<td align="right">-</td>';
              $output .= '<td align="right">-</td>';

              $output .= '<td align="right">-</td>';
              $output .= '<td align="right">-</td>';

              $output .= '<td align="right">-</td>';
              $output .= '<td align="right">-</td>';
            }
          }
           
       
       
    $output .= '</tr>';
    
  }//first foreach ended
}
else
{
  $output .= '
  <tr>
    <td colspan="8" align="center">No Data Found</td>
  </tr>
  ';
}

$output .= '
</table>
</div>
<div class="paginating-container pagination-default">
  <ul class="pagination">
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 4)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id >= $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';
echo $output;
?>