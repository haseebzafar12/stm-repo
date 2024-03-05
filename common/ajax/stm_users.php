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

$query = "SELECT * FROM stm_users
";

  if($_POST['query'] != '')
  {
    
    $query .= ' WHERE userName LIKE "%'.$_POST['query'].'%" ';
  }


$query .= 'ORDER BY userName ASC ';

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
    <th>DP</th>
    <th>Name</th>
    <th>Email</th>
    <th>Whatsapp</th>
    <th>User Type</th>
    <th>Department</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
';
if($total_data > 0)
{
  foreach($result as $row)
  {
    $userTypesIds = explode(',', $row['usertypeID']);
    
    $userids = $row['usertypeID']; 
    $tb = "stm_usertypes";
    $whe = "id IN ($userids)";
    $dataUserType = $db_helper->allRecordsRepeatedWhere($tb, $whe);

    $us = "";
    foreach($dataUserType as $dats){
      $us .= $dats['usertypeName'].",";
    }

    $dep = "stm_departments";
    $whedep = "id = '".$row['departmentID']."'";
    $dataDep = $db_helper->SingleDataWhere($dep, $whedep);
    $department = null;
    if(!empty($dataDep["departmentName"])){
      $department = $dataDep["departmentName"];
    }

    $status = null;
    if($row["isActive"] == 1){
      $status = "Active";
    }else{
      $status = "In-Active";
    }
    $usrstatusicon = null;
    if($row["isActive"] == 1){
      
      $usrstatusicon = '<a href="#" class="userStatus" data-id="'.$row['id'].'" data-status="inactive" title="Click to In-Active"><image src="images/tickmark.png" height="24" width="24"></a>';
      
    }else{
      
      $usrstatusicon = '<a href="#" class="userStatus" data-id="'.$row['id'].'" data-status="active" title="Click to Active"><image src="images/cross.png" height="24" width="24"></a>';
    }

    $folder = "././images/".$row['userDP'];
    $image = "<img src='".$folder."' height='40' width='40'>";
    $output .= '
    <tr style="height:20px !important;">
      <td>'.$image.'</td>
      <td>'.$row['userName'].'</td>
      <td>'.$row["userEmail"].'</td>
      <td>'.$row["userWhatsapp"].'</td>
      <td>'.rtrim($us, ',').'</td>
      <td>'.$department.'</td>
      <td>'.$status.'</td>
      <td>
          '.$usrstatusicon.'
          <a href="stmuserview.php?uid='.$row['id'].'" target="_blank" class="userdetailPage">
            <svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
          </svg>
          </a>
          <a href="stmuseredit.php?uid='.$row['id'].'&utid='.$row["usertypeID"].'" class="editAccount">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </a>
      </td>
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