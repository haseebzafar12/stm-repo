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

$query = "SELECT * FROM stm_itemmaster";

// if(isset($_POST['supplier'])){
  if($_POST['supplier'] != '' AND $_POST['query'] == '')
  {
    $query .= ' WHERE SupplierID = "'.$_POST['supplier'].'"';
  } 

  if($_POST['query'] !='' AND $_POST['supplier'] == ''){
    $query = 'SELECT * from stm_itemmaster t1 INNER JOIN stm_listing t2 On t2.LwSku = t1.LWSKU WHERE t1.LWSKU LIKE "%'.$_POST['query'].'%" OR t1.ItemName LIKE "%'.$_POST['query'].'%" OR t1.ItemBarCode LIKE "%'.$_POST['query'].'%" OR t2.StoreItemID LIKE "%'.$_POST['query'].'%" OR StoreSKU LIKE "%'.$_POST['query'].'%" OR t2.ItemID LIKE "%'.$_POST['query'].'%"';
  }
if($_POST['query'] == ''){
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

$output = '<div class="table-responsive">
<table class="table table-striped table-sm" id="userTable">
  <thead style="background-color:#d9e6f2;">
  <tr>
    <th id="uper_rows">LW.SKU</th>
    <th id="uper_rows">ITEM NAME</th>
    <th id="uper_rows">SUPPLIER</th>
    <th id="uper_rows">BARCODE</th>
    <th id="uper_rows">Total Stock</th>
    <th id="uper_rows">AMZ (OS)</th>
    <th id="uper_rows">AMZ (QC)</th>
    <th id="uper_rows">EBAY (OS)</th>
    <th id="uper_rows">EBAY (AO)</th>
  </tr>
  </thead>
';
if($total_data > 0)
{
  foreach($result as $row)
  {
    $sku = $row['LWSKU'];
    $supplier = $db_helper->SingleDataWhere('stm_supplier','id = "'.$row['SupplierID'].'"');
    $string = strip_tags($row['ItemBarCode']);
    if (strlen($string) > 15) {
        // truncate string
        $stringCut = substr($string, 0, 15);
        $endPoint = strrpos($stringCut, ' ');

        $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    $yesAmzOS = "";
    $yesAmzQC = "";
    $yesEbayOs = "";
    $yesEbayAo = "";

    $AmzOS = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '1' AND StoreID = '5'");
    if($AmzOS){
      if($AmzOS['LwSku'] == $row['LWSKU']){
        $yesAmzOS = "<center><img src='././images/tickmark2.png' height='25' width='25'></center>";
      }  
    }

    $AmzQC = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '1' AND StoreID = '6'");
    if($AmzQC){
      if($AmzQC['LwSku'] == $row['LWSKU']){
        $yesAmzQC = "<center><img src='././images/tickmark2.png' height='25' width='25'></center>";
      }  
    }
    $ebayOs = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '2' AND StoreID = '1'");
    if($ebayOs){
      if($ebayOs['LwSku'] == $row['LWSKU']){
        $yesEbayOs = "<center><img src='././images/tickmark2.png' height='25' width='25'></center>";
      }  
    }
    $ebayAo = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '2' AND StoreID = '2'");
    if($ebayAo){
      if($ebayAo['LwSku'] == $row['LWSKU']){
        $yesEbayAo = "<center><img src='././images/tickmark2.png' height='25' width='25'></center>";
      }  
    }
    
    $output .= '<tr>
      <td><a class="lw_sku" data-id="'.$row['LWSKU'].'" style="color:#000; text-decoration:underline; cursor:pointer;">'.$row['LWSKU'].'</a></td>
      <td>'.$row['ItemName'].'</td>
      <td>'.$supplier['supplierName'].'</td>
      <td><a title="'.$row['ItemBarCode'].'">'.$string.'</a></td>
      <td align="right">'.$row['TotalStock'].'</td>
      <td>'.$yesAmzOS.'</td>
      <td>'.$yesAmzQC.'</td>
      <td>'.$yesEbayOs.'</td>
      <td>'.$yesEbayAo.'</td>
    </tr>
    ';
  }
}
else
{
  $output .= '
  <tr>
    <td colspan="5" align="center">No Data Found</td>
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