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

$limit = '100';
$page = 1;

  if($_POST['page'] > 1)
  {
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
  }else
  {
    $start = 0;
  } 


$query = "SELECT * FROM stm_prelistings ";
  
  if($_POST['query'] != ""){
    $query .= 'WHERE productCode LIKE "%'.$_POST['query'].'%" OR storeSKU LIKE "%'.$_POST['query'].'%" OR linkedSKU LIKE "%'.$_POST['query'].'%" OR refURL LIKE "%'.$_POST['query'].'%" OR taskID LIKE "%'.$_POST['query'].'%"';    
  }  

  if($_POST['statusStore'] != ""){

    $query .= "WHERE channelID = '".$_POST['channelID']."' AND  storeID = '".$_POST['store_names']."' AND LinkedStatusID = '".$_POST['statusStore']."'";  
  }
  
$query .= 'ORDER by LinkedStatusID DESC ';


$statement = $db->prepare($query);
$statement->execute();
$preListing = $statement->fetchAll();
$total_data = $statement->rowCount();

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output = '
<div class="table-responsive">
<table class="table table-striped table-sm">
   <tr>
       <th>TASK#</th>
       <th>PRODUCT CODE</th>
       <th>CHANNEL</th>
       <th>STORE</th>
       <th>STORE SKU</th>
       <th>LINKED SKU</th>
       <th>TYPE</th>
       <th>STATUS</th>
       <th>NOTE</th>
   </tr>
';
if($total_data > 0)
{
  foreach($result as $preListingData)
  {

    $channel = $db_helper->SingleDataWhere("stm_channels","id = '".$preListingData['channelID']."'");
    $store = $db_helper->SingleDataWhere("stm_stores","id = '".$preListingData['storeID']."'");

    $listingType = $db_helper->SingleDataWhere("stm_listingtype","id = '".$preListingData['listingTypeID']."'");

    $statusLinked = $db_helper->SingleDataWhere('stm_linked_statuses','statusName = "Linked"');
    $statusUnlink = $db_helper->SingleDataWhere('stm_linked_statuses','statusName = "Unlinked"');
    $statusIssue = $db_helper->SingleDataWhere('stm_linked_statuses','statusName = "Issue"');

    $style = "";
    if($preListingData['LinkedStatusID'] == $statusUnlink['id']){
         $style .= "background-color:#ffff66";
    }else if($preListingData['LinkedStatusID'] == $statusLinked['id']){
        $style .= "background-color:#0066cc; color:#fff;";
    }else if($preListingData['LinkedStatusID'] == $statusIssue['id']){
        $style .= "background-color:#cc3300; color:#fff;";
    }
    $status = $db_helper->allRecordsOrderBy("stm_linked_statuses","statusName DESC");
    
    $sl = '<select id="sync_sku" class="form-control sync_sku_'.$preListingData['id'].'" data-id="'.$preListingData['id'].'" style="width:90%; '.$style.';">';

    foreach ($status as $statuses) {
      $select = "";
      if($preListingData['LinkedStatusID'] == $statuses['id']){
          $select .= "selected = 'selected'";
      }
      $sl .='<option value="'.$statuses['id'].'" '.$select.'>'.$statuses['statusName'].'</option>';
    }

    $sl .= '</select>';

    $productCode = strip_tags($preListingData['productCode']);
    
      if (strlen($productCode) > 15) {
          // truncate string
          $stringCut = substr($productCode, 0, 15);
          $endPoint = strrpos($stringCut, ' ');

          $productCode = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
          $productCode .= '...';
      }

      $storeSKU = strip_tags($preListingData['storeSKU']);
    
      if (strlen($storeSKU) > 15) {
          // truncate string
          $stringCut = substr($storeSKU, 0, 15);
          $endPoint = strrpos($stringCut, ' ');

          $storeSKU = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
          $storeSKU .= '...';
      }

    $output .= '
    <tr>
      <td>
        <a href="stmtaskdetail.php?id='.$preListingData['taskID'].'&view">'.$preListingData['taskID'].'</a>
      </td>
      <td><a data-title="'.$preListingData['productCode'].'" class="title-task">'.$productCode.'</a></td>
      <td>'.$channel['channelName'].'</td>
      <td>'.$store['storeName'].'</td>
      <td>'.$storeSKU.'</td>
      <td>'.$preListingData['linkedSKU'].'</td>
      <td>'.$listingType['listingTypeName'].'</td>
      <td>'.$sl.'</td>
      <td><textarea class="form-control issueNote_'.$preListingData['id'].'" rows="1" id="issueNote" data-id="'.$preListingData['id'].'">'.$preListingData['note'].'</textarea></td>
    </tr>';
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