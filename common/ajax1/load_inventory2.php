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

  if($_POST['supplier'] != '' AND $_POST['inv'] == '')
  {
    $query .= ' WHERE SupplierID = "'.$_POST['supplier'].'"';
  }

  if($_POST['inv'] != '' AND $_POST['supplier'] == '')
  {
    $query = 'SELECT * from stm_itemmaster t1 INNER JOIN stm_listing t2 On t2.LwSku = t1.LWSKU WHERE t1.LWSKU LIKE "%'.$_POST['inv'].'%" OR t1.ItemName LIKE "%'.$_POST['inv'].'%" OR t1.ItemBarCode LIKE "%'.$_POST['inv'].'%" OR t2.StoreItemID LIKE "%'.$_POST['inv'].'%" OR StoreSKU LIKE "%'.$_POST['inv'].'%" OR t2.ItemID LIKE "%'.$_POST['inv'].'%"';
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
        
        $list = $db->prepare("select DISTINCT WeekDate from stm_sale_master");
        $list->execute();
        $dataWeek = $list->fetchAll();
        $output .= '<tr>';
          $output .= '<th id="headings" colspan="5"></th>';
        
        foreach($dataWeek as $list){ 
            $output .= '<th id="uper_rows" colspan="8">'.date('d M Y',strtotime($list['WeekDate'])).'</th>';          
        }  
          $output .= '</tr>';
          $output .= '<tr>';
          $output .= '<th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th><th id="headings2"></th>';
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
            $output .= '<th id="headings3">Barcode</th>';
            $output .= '<th id="headings3">Stock</th>';
            
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

    $output .= '<tr>';
        $output .= '<td><a class="lw_sku_inv" data-id="'.$row['LWSKU'].'" style="color:#000; text-decoration:underline; cursor:pointer;">'.$row['LWSKU'].'</td>';
        $output .= '<td><a title="'.$row['ItemName'].'">'.$ItemName.'</a></td>';
        $output .= '<td>'.$supps['supplierName'].'</td>';
        $output .= '<td><a title="'.$row['ItemBarCode'].'">'.$string.'</a></td>';
        $output .= '<td align="right">'.$row['TotalStock'].'</td>';

          
        
        $list = $db->prepare("select * from stm_sale_master where LwSku = '".$row['LWSKU']."'");
        $list->execute();
        $res = $list->fetchAll();
        if($res){
          foreach($res as $data){

            $amzosData = $db_helper->SingleDataWhere('stm_sales',"LwSku = '".$data['LwSku']."' AND MarketID = '1' AND StoreID = '5'");
            
            $amzOsVal = "";
            if(!$amzosData){
              $amzOsBack = "style=background-color:#ffe6e6;";
            }else{
              if($data['AmzOsVal']){
                $amzOsVal = $data['AmzOsVal'];
              }  
            }

            $amzqcData = $db_helper->SingleDataWhere('stm_sales',"LwSku = '".$data['LwSku']."' AND MarketID = '1' AND StoreID = '6'"); 
            $AmzQcVal = "";
            if(!$amzqcData){
              $AmzQcBack = "style=background-color:#ffe6e6;";
            }else{
              if($data['AmzQcVal']){
                $AmzQcVal = $data['AmzQcVal'];
              }  
            }


            $ebyosData = $db_helper->SingleDataWhere('stm_sales',"LwSku = '".$data['LwSku']."' AND MarketID = '2' AND StoreID = '1'");
            $EbayOsVal = "";
            if(!$ebyosData){
              $EbayOsBack = "style=background-color:#ffe6e6;";
            }else{
              if($data['EbayOsVal']){
                $EbayOsVal = $data['EbayOsVal'];
              }  
            }

            $EbayAoVal = "";
            $EbayAoBack = "";
            $ebayAo = $db_helper->SingleDataWhere('stm_sales',"LwSku = '".$data['LwSku']."' AND MarketID = '2' AND StoreID = '2'");
            
            if(!$ebayAo){
              $EbayAoBack = "style=background-color:#ffe6e6;"; 
            }else{
              if($data['EbayAoVal']){
                $EbayAoVal = $data['EbayAoVal'];
              }              
            } 
              
              $EbayAoQty = "";
              $EbayAoBack = "";
              if($data['EbayAoQty']){
                $EbayAoQty = $data['EbayAoQty'];
              }else{
                $EbayAoBack = "style=background-color:#ffe6e6;";
              }

              $EbayOsQty = "";
              $EbayOsBack = "";
              if($data['EbayOsQty']){
                $EbayOsQty = $data['EbayOsQty'];
              }else{
                $EbayOsBack = "style=background-color:#ffe6e6;";
              }
              $AmzQcQty = "";
              $AmzQcBack = "";
              if($data['AmzQcQty']){
                $AmzQcQty = $data['AmzQcQty'];
              }else{
                $AmzQcBack = "style=background-color:#ffe6e6;";
              }
              $AmzOsQty = "";
              $amzOsBack = "";
              if($data['AmzOsQty']){
                $AmzOsQty = $data['AmzOsQty'];
              }else{
                $amzOsBack = "style=background-color:#ffe6e6;";
              }

              $output .= '<td align="right"'.$amzOsBack.'>'.$AmzOsQty.'</td>';
              $output .= '<td align="right"'.$amzOsBack.'>'.$amzOsVal.'</td>';

              $output .= '<td align="right"'.$AmzQcBack.'>'.$AmzQcQty.'</td>';
              $output .= '<td align="right"'.$AmzQcBack.'>'.$AmzQcVal.'</td>';

              $output .= '<td align="right"'.$EbayOsBack.'>'.$EbayOsQty.'</td>';
              $output .= '<td align="right"'.$EbayOsBack.'>'.$EbayOsVal.'</td>';

              $output .= '<td align="right"'.$EbayAoBack.'>'.$EbayAoQty.'</td>';
              $output .= '<td align="right"'.$EbayAoBack.'>'.$EbayAoVal.'</td>';  
            } 
        }
        // else{
        //     $amzosData = $db_helper->SingleDataWhere('stm_listing',"LwSku = '".$row['LWSKU']."' AND ChannelID = '1' AND StoreID = '5'");
        //     $amzOsVal = "";
        //     if(!$amzosData){
        //       $amzOsVal = "style=background-color:#ffe6e6;";
        //     }

        //     $amzqcData = $db_helper->SingleDataWhere('stm_listing',"LwSku = '".$row['LWSKU']."' AND ChannelID = '1' AND StoreID = '6'"); 
        //     $AmzQcVal = "";
        //     if(!$amzqcData){
        //       $AmzQcVal = "style=background-color:#ffe6e6;";
        //     }


        //     $ebyosData = $db_helper->SingleDataWhere('stm_listing',"LwSku = '".$row['LWSKU']."' AND ChannelID = '2' AND StoreID = '1'");
        //     $EbayOsVal = "";
        //     if(!$ebyosData){
        //       $EbayOsVal = "style=background-color:#ffe6e6;";
        //     }

        //     $EbayAoVal = "";
        //     $ebayAo = $db_helper->SingleDataWhere('stm_listing',"LwSku = '".$data['LwSku']."' AND ChannelID = '2' AND StoreID = '2'");
            
        //     if(!$ebayAo){
        //       $EbayAoVal = 'style=background-color:#ffe6e6;';
        //     }
            
        //     $output .= '<td align="right"'.$amzOsVal.'></td>';
        //     $output .= '<td align="right"'.$amzOsVal.'></td>';

        //     $output .= '<td align="right"'.$AmzQcVal.'></td>';
        //     $output .= '<td align="right"'.$AmzQcVal.'></td>';

        //     $output .= '<td align="right"'.$EbayOsVal.'></td>';
        //     $output .= '<td align="right"'.$EbayOsVal.'></td>';

        //     $output .= '<td align="right"'.$EbayAoVal.'></td>';
        //     $output .= '<td align="right"'.$EbayAoVal.'></td>';  
          
          
        // }
            

    $output .= '</tr>';

    

  }
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