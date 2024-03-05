<?php ob_start();
session_start();

$session_id = "";
if(isset($_SESSION['user'])){
$session_id = $_SESSION['user'];
}else if(isset($_SESSION['id'])){
$session_id = $_SESSION['id'];
}
   include_once ('../config.php');
   include_once ('../user.php');
   include_once ('../db_helper.php');

   $dbcon = new Database();
   $db = $dbcon->getConnection();
   $objUser = new user($db); 
   $db_helper = new db_helper($db);

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

$query = "SELECT * FROM stm_stores
";

  if($_POST['query'] != '')
  {
    
    $query .= ' WHERE storeName LIKE "%'.$_POST['query'].'%" ';
  }


$query .= 'ORDER BY storeChannelID ASC ';

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
<table class="table table-striped table-sm" id="userTable">
  <tr>
    <th>Sr #</th>
    <th>ChannelName</th>
    <th>StoreName</th>
    
    <th>Brand</th>
    <th>Action</th>
  </tr>
';
if($total_data > 0)
{
  $key=1;
  foreach($result as $row)
  {
    
    $storeChannelID = $row['storeChannelID']; 
    $tb = "stm_channels";
    $whe = "id = '".$storeChannelID."'";
    $channel = $db_helper->SingleDataWhere($tb, $whe);

    $OurBrandID = $row['OurBrandID']; 
    $br = "stm_ourbrands";
    $wh = "id = '".$OurBrandID."'";
    $brand = $db_helper->SingleDataWhere($br, $wh);
    $brandName = "";
    if(!empty($brand['brandName'])){
      $brandName = $brand['brandName'];
    }

    $output .= '
    <tr style="height:20px !important;">
      <td>'.$key.'</td>
      <td>'.$channel['channelName'].'</td>
      <td>'.$row['storeName'].'</td>
      
      <td>'.$brandName.'</td>
      <td><a href="editstore.php?sid='.$row['id'].'" class="editAccount">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </a></td>
    </tr>
    ';
    $key++;
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
    <li class="active">
      <a href="#" class="page-link">'.$page_array[$count].' <span class="sr-only"></span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li><a href="javascript:void(0)" class="page-link" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li>
        <a href="#" class="page-link">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id >= $total_links)
    {
      $next_link = '
      <li>
        <a href="#" class="page-link">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li><a href="javascript:void(0)" data-page_number="'.$next_id.'" class="page-link">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li>
          <a href="#" class="page-link">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li><a href="javascript:void(0)" data-page_number="'.$page_array[$count].'" class="page-link">'.$page_array[$count].'</a></li>
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