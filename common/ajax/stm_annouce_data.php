<?php ob_start();
session_start();
   
   include_once ('../config.php');
   include_once ('../user.php');
   include_once ('../db_helper.php');
   include_once ('../announceClass.php');
   $dbcon = new Database();
   $db = $dbcon->getConnection();
   $objUser = new user($db); 
   $db_helper = new db_helper($db);
   $objAnnouce = new announceClass($db);

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

$query = "SELECT * FROM stm_announcements ";

  if($_POST['query'] != '')
  {
    $query .= ' WHERE title LIKE "%'.$_POST['query'].'%" ';
  }
$query .= 'ORDER BY id DESC ';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();
$user = $db_helper->SingleDataWhere('stm_users','userEmail = "shamsgulzar@gmail.com"');

$user2 = $db_helper->SingleDataWhere('stm_users','userEmail = "quratulain@swiftitsol.net"');

$statusTH = "";
$th = "";
if($user['id'] == $session_id OR $user2['id'] == $session_id){
  $statusTH = '<th style="width:9%;">STATUS</th>';
  $th = '<th style="width:7%; text-align:right;">ACTION</th>';
}    
$output = '
<div class="table-responsive">
<table class="table table-striped table-sm">
  <tr>
    <th style="width:70%;">TITLE</th>
    <th style="width:9%;">CREATED ON</th>
    '.$statusTH.'
    '.$th.'
  </tr>
';
if($total_data > 0)
{
  foreach($result as $row)
  {
    $allStatus = $db_helper->allRecordsOrderBy('stm_annoucement_statuses','statusName ASC');
    
    
    $actionTd = "";
    $select = "";
    if($user['id'] == $session_id OR $user2['id'] == $session_id){

      $select .= '<td><select class="form-control announceStatus" data-id='.$row['id'].'>';
      foreach ($allStatus as $allStatusList) {
        $selected = '';
          if($allStatusList['id'] == $row['status']){
            $selected = "selected = 'selected'";
          }
          $select .= '<option value="'.$allStatusList['id'].'" '.$selected.'>'.$allStatusList['statusName'].'</option>';
      }
      $select .= '</select></td>';
      $actionTd = '<td style="float:right;">
          
          <a class="rounded editAnnounce" data-placement="top" title="Edit" data-id='.$row['id'].'>
           <svg style="color:#04AA6D; height:17px !important; width:17px !important;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          </a>
          <a class="rounded deleteAnnounce" data-placement="top" title="Delete" data-id='.$row['id'].'>
           <svg style="color:#e7515a;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
          </a>
      </td>';
    }
    $output .= '
    <tr>
      <td><a class="announceClass" target="_blank" href="stmannouncedetail.php?id='.$row['id'].'&view">'.$row['title'].'</a></td>
      <td>'.date('d M Y',strtotime($row['createdOn'])).'</td>
      '.$select.'
      '.$actionTd.'
    </tr>
    ';
  }
}else
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