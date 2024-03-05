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

$dataStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');
$statusDone = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');

$query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '$session_id' AND taskStatusID IN('".$dataStatus['id']."','".$statusDone['id']."')";

  if($_POST['query'] != '')
  {
    $query .= 'AND taskID LIKE "%'.$_POST['query'].'%" ';
  }

$query .= "ORDER BY taskSupervisorID ASC,taskCreationDate DESC ";

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
    <th>TASK ID / DATE</th>
    <th>SUB TASK</th>
    <th>CATEGORY</th>
    <th>PRIORITY</th>
    <th>CHANNEL</th>
    <th>STORE</th>
    <th>SUPERVISOR</th>
    <th>STARTED ON</th>
    <th>ENDED ON</th>
    <th>DEADLINE</th>
    <th>STATUS</th>
    <th>ACTION</th>
  </tr>
';
if($total_data > 0)
{
  foreach($result as $row)
  {

    $tbtasks = "stm_tasks";
    $whetasks = "id = '".$row['taskID']."'";
    $stmtasks = $db_helper->SingleDataWhere($tbtasks, $whetasks);

    $tbtasks = "stm_tasks";
    $whetasks = "id = '".$row['taskID']."'";
    $stmtasks = $db_helper->SingleDataWhere($tbtasks, $whetasks);

    $categoy = $db_helper->SingleDataWhere('stm_tasktypes','id = "'.$stmtasks['taskTypeID'].'"');

    $datawheproio = $db_helper->SingleDataWhere('stm_priorities','id = "'.$stmtasks['taskPriorityID'].'"');

    $priClass = "";
    if($datawheproio['taskpriorityName'] == "1-Critical"){
      $priClass .= "<span class='badge badge-secondary'>".
      $datawheproio['taskpriorityName']."</span>";
    }
    if($datawheproio['taskpriorityName'] == "2-Immediate"){
      $priClass .= "<span class='badge badge-danger'>".
      $datawheproio['taskpriorityName']."</span>";
    }
    if($datawheproio['taskpriorityName'] == "3-High"){
      $priClass .= "<span class='badge badge-primary'>".
      $datawheproio['taskpriorityName']."</span>";
    }
    if($datawheproio['taskpriorityName'] == "4-Normal"){
      $priClass .= "<span class='priority_btn_noraml'>".
      $datawheproio['taskpriorityName']."</span>";
    } 

    $supervisor = $db_helper->SingleDataWhere('stm_users','id = "'.$row['taskSupervisorID'].'"');

    $tblCh = "stm_channels";
    $wheCH = "id = '".$row['taskchannelID']."'";
    $CHdata = $db_helper->SingleDataWhere($tblCh, $wheCH);

    $tblST = "stm_stores";
    $wheST = "id = '".$row['taskstoreID']."'";
    $STdata = $db_helper->SingleDataWhere($tblST, $wheST);

    $tblSTS = "stm_statuses";
    $wheSTS = "id = '".$row['taskStatusID']."'";
    $StData = $db_helper->SingleDataWhere($tblSTS, $wheSTS);

    $statusClass = "";
    if($StData['statusName'] == "Approved"){
      $statusClass .= "dark";
    }
    if($StData['statusName'] == '1-New Task'){
      $statusClass .= "danger";
    }
    if($StData['statusName'] == "3-In Progress" OR $StData['statusName'] == "2-Started"){
      $statusClass .= "warning";
    }
    if($StData['statusName'] == "4-Ended" OR $StData['statusName'] == "5-Done"){
      $statusClass .= "success";
    }

    $creationDate = date("d M Y", strtotime($row["taskCreationDate"]));
    $deadlineDate = date("d-m-Y", strtotime($row["taskDeadline"]));

    $start_date = date('Y-m-d');
    $end_date = date("d-m-Y", strtotime($row["taskDeadline"]));


    $diff = strtotime($end_date) - strtotime($start_date);
      // 1 day = 24 hours
      // 24 * 60 * 60 = 86400 seconds
    
    $daysCount = "&nbsp&nbsp".abs(round($diff / 86400));
    $minusDays = "-&nbsp".abs(round($diff / 86400));  
    
      if($start_date > $end_date){
        if($StData['statusName'] != '6-Reviewed'){
          $daysCount = "<span class='bagde badge-important'>".$minusDays."</span>";
        }else{
          $daysCount = "";
        }
      } 

    $started_at = "";
    if($row['taskStartDate'] == ""){
      $started_at = "";
    }else if($row['taskStartDate'] != ""){
      $started_at = date("d-m-Y", strtotime($row['taskStartDate']));
      //$started_at = $row['taskStartDate'];
    }

    $ended_at = "";
    if($row['taskEndDate'] == ""){
      $ended_at = "";
    }else if($row['taskEndDate'] != ""){
      $ended_at = date("d-m-Y", strtotime($row['taskEndDate']));
      //$started_at = $row['taskStartDate'];
    }

    $subtask = $db_helper->SingleDataWhere("stm_subtask","id = '".$row['subTaskID']."'");

    $data_messages = $db_helper->SingleDataWhere('stm_messages','taskID = "'.$stmtasks['id'].'"');
    $message_icon = "";
    if($data_messages){
      $message_icon = '<a class="green_message_icon" href="stmtaskdetail.php?id='.$stmtasks['id'].'&sub='.$row['id'].'#messages" title="Chat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>';
    }else{
      $message_icon = '<a class="light_message_icon" href="stmtaskdetail.php?id='.$stmtasks['id'].'&sub='.$row['id'].'#messages" title="Chat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>';
    }      

    $deleteStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');

    $tasks = $db_helper->SingleDataWhere('stm_tasks','id = "'.$row['taskID'].'"');
    if($tasks['taskStatusID'] != $deleteStatus['id']){

    $output .= '
    <tr>
      <td>'.$row['taskID'].'-('.$creationDate.')</td>
      <td>'.$subtask['subTask'].'</td>
      <td>'.$categoy['tasktypeName'].'</td>
      <td>'.$priClass.'</td>
      <td>'.$CHdata['channelName'].'</td>
      <td>'.$STdata['storeName'].'</td>
      <td>'.$supervisor['userName'].'</td>
      <td>'.$started_at.'</td>
      <td>'.$ended_at.'</td>
      <td>'.$deadlineDate.'</td>
      <td><span class="badge badge-'.$statusClass.'">'.$StData['statusName'].'</span></td>
      <td>
         <a href="stmtaskdetail.php?id='.$stmtasks['id'].'&sub='.$row['id'].'#assignees" title="View" target="_blank"><svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
          </svg></a>
          '.$message_icon.'
      </td>
    </tr>
    ';
   }
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