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

/*function get_total_row($connect)
{
  $query = "
  SELECT * FROM tbl_webslesson_post
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  return $statement->rowCount();
}

$total_record = get_total_row($connect);*/

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

$reviewStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');
$deleteStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
$doneStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');
$stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');
$progress = $db_helper->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
$newtask = $db_helper->SingleDataWhere('stm_statuses','statusName = "1-New Task"');
$forreview = $db_helper->SingleDataWhere('stm_statuses','statusName = "7-For Review"');

$query = "SELECT * FROM stm_tasks WHERE taskStatusID ='".$reviewStatus['id']."'";

  if($_POST['query'] != '')
  {
    $query = 'SELECT DISTINCT t1.id as mID, t1.* FROM stm_tasks t1 LEFT JOIN stm_taskassigned t2 on t1.id = t2.taskID LEFT JOIN stm_priorities t3 on t3.id = t1.taskPriorityID LEFT JOIN stm_subtask t4 on t2.subTaskID = t4.id LEFT JOIN stm_task_details t5 on t1.id = t5.taskID LEFT JOIN stm_prelistings t6 on t1.id = t6.taskID WHERE t1.taskName LIKE "%'.$_POST['query'].'%" OR t4.subTask LIKE "%'.$_POST['query'].'%" OR t1.taskDescription LIKE "%'.$_POST['query'].'%" OR t1.taskRefURL LIKE "%'.$_POST['query'].'%" OR t1.taskSkypeGroup LIKE "%'.$_POST['query'].'%" OR t1.taskCreationDate LIKE "%'.$_POST['query'].'%" OR t2.subTaskDescription LIKE "%'.$_POST['query'].'%" OR t2.taskURL LIKE "%'.$_POST['query'].'%" OR t2.taskComments LIKE "%'.$_POST['query'].'%" OR t2.taskCreationDate LIKE "%'.$_POST['query'].'%" OR t2.taskDeadline LIKE "%'.$_POST['query'].'%" OR t3.taskpriorityName LIKE "%'.$_POST['query'].'%" OR t1.id LIKE "%'.$_POST['query'].'%" OR t5.refURL LIKE "%'.$_POST['query'].'%" OR t5.productCode LIKE "%'.$_POST['query'].'%" OR t6.productCode LIKE "%'.$_POST['query'].'%" OR t6.refURL LIKE "%'.$_POST['query'].'%" OR t6.storeSKU LIKE "%'.$_POST['query'].'%" OR t6.linkedSKU LIKE "%'.$_POST['query'].'%"';
  }
  
  if($_POST['category'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] != ''){

    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_tasks WHERE taskTypeID = '".$_POST['category']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_tasks WHERE taskTypeID = '".$_POST['category']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID = '".$reviewStatus['id']."'";
    }
  
  }else if($_POST['category'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] != ''){
    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_tasks WHERE taskTypeID = '".$_POST['category']."' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_tasks WHERE taskTypeID = '".$_POST['category']."' AND taskStatusID = '".$reviewStatus['id']."'";
    } 
  }else if($_POST['category'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] == ''){
      $query = "SELECT * FROM stm_tasks WHERE taskTypeID = '".$_POST['category']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' ";  
  }else if($_POST['category'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] == ''){
    $query = "SELECT * FROM stm_tasks WHERE taskTypeID = '".$_POST['category']."'";
  }

  //Created By

  if($_POST['created_by'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] != ''){

    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_tasks WHERE taskAssignedBy = '".$_POST['created_by']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_tasks WHERE taskAssignedBy = '".$_POST['created_by']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID = '".$reviewStatus['id']."'";
    }
  
  }else if($_POST['created_by'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] != ''){
    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_tasks WHERE taskAssignedBy = '".$_POST['created_by']."' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_tasks WHERE taskAssignedBy = '".$_POST['created_by']."' AND taskStatusID = '".$reviewStatus['id']."'";
    } 
  }else if($_POST['created_by'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] == ''){
   $query = "SELECT * FROM stm_tasks WHERE taskAssignedBy = '".$_POST['created_by']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' "; 
  }else if($_POST['created_by'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] == ''){
    $query = "SELECT * FROM stm_tasks WHERE taskAssignedBy = '".$_POST['created_by']."'";
  }

  // Priority

  if($_POST['priority'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] != ''){

      if($_POST['status'] == 'open'){
        $query = "SELECT * FROM stm_tasks WHERE taskPriorityID = '".$_POST['priority']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
      }else if($_POST['status'] == 'closed'){
        $query = "SELECT * FROM stm_tasks WHERE taskPriorityID = '".$_POST['priority']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID = '".$reviewStatus['id']."'";
      }
  
  }else if($_POST['priority'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] != ''){
    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_tasks WHERE taskPriorityID = '".$_POST['priority']."' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_tasks WHERE taskPriorityID = '".$_POST['priority']."' AND taskStatusID = '".$reviewStatus['id']."'";
    } 
  }else if($_POST['priority'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] == ''){
   $query = "SELECT * FROM stm_tasks WHERE taskPriorityID = '".$_POST['priority']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' "; 
  }else if($_POST['priority'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] == ''){
    $query = "SELECT * FROM stm_tasks WHERE taskPriorityID = '".$_POST['priority']."'";
  }

  //assignee

  if($_POST['assignees'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] != ''){

      if($_POST['status'] == 'open'){
        $query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '".$_POST['assignees']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID NOT IN ('".$doneStatus['id']."','".$stApprov['id']."') AND isActive = '1'"; 
      }else if($_POST['status'] == 'closed'){
        $query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '".$_POST['assignees']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID IN ('".$doneStatus['id']."','".$stApprov['id']."') AND isActive = '1'";
      }
  
  }else if($_POST['assignees'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] != ''){
    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '".$_POST['assignees']."' AND taskStatusID NOT IN ('".$doneStatus['id']."','".$stApprov['id']."') AND isActive = '1'"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '".$_POST['assignees']."' AND taskStatusID IN ('".$doneStatus['id']."','".$stApprov['id']."') AND isActive = '1'";
    } 
  }else if($_POST['assignees'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] == ''){
   $query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '".$_POST['assignees']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND isActive = '1'"; 
  }else if($_POST['assignees'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] == ''){
    $query = "SELECT * FROM stm_taskassigned WHERE taskuserID = '".$_POST['assignees']."' AND isActive = '1'";
  }

  //Skype

  if($_POST['skype'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] != ''){

      if($_POST['status'] == 'open'){
        $query = "SELECT * FROM stm_tasks WHERE taskSkypeGroup = '".$_POST['skype']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
      }else if($_POST['status'] == 'closed'){
        $query = "SELECT * FROM stm_tasks WHERE taskSkypeGroup = '".$_POST['skype']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' AND taskStatusID = '".$reviewStatus['id']."'";
      }
  
  }else if($_POST['skype'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] != ''){
    if($_POST['status'] == 'open'){
      $query = "SELECT * FROM stm_tasks WHERE taskSkypeGroup = '".$_POST['skype']."' AND taskStatusID NOT IN ('".$reviewStatus['id']."','".$deleteStatus['id']."')"; 
    }else if($_POST['status'] == 'closed'){
      $query = "SELECT * FROM stm_tasks WHERE taskSkypeGroup = '".$_POST['skype']."' AND taskStatusID = '".$reviewStatus['id']."'";
    } 
  }else if($_POST['skype'] !='' AND $_POST['from_task'] != '' AND $_POST['status'] == ''){
   $query = "SELECT * FROM stm_tasks WHERE taskSkypeGroup = '".$_POST['skype']."' AND taskCreationDate BETWEEN '" . $_POST['from_task'] . "' AND  '" . $_POST['to_task'] . "' "; 
  }else if($_POST['skype'] !='' AND $_POST['from_task'] == '' AND $_POST['status'] == ''){
    $query = "SELECT * FROM stm_tasks WHERE taskSkypeGroup = '".$_POST['skype']."'";
  }

if($_POST['query'] != ''){
  $query .= ' ORDER BY t1.taskCreationDate DESC,t1.taskStatusID ASC ';
}else{
  $query .= ' ORDER BY id DESC ';
}

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
    <th>PRIORITY</th>
    <th>CATEGORY</th>
    <th>SUPPLIER</th>
    <th>TITLE</th>
    <th>CREATOR</th>
    <th>ASSIGNEES</th>
    <th>ACTION</th>
  </tr>
';
if($total_data > 0)
{
  foreach($result as $row)
  {


    if($_POST['assignees']){

      $lists = $db_helper->allRecordsRepeatedWhere('stm_tasks','id = "'.$row['taskID'].'"');

      foreach ($lists as $row) {
          $tblType = "stm_tasktypes";
          $wheType = "id = '".$row['taskTypeID']."'";
          $type = $db_helper->SingleDataWhere($tblType, $wheType);

          $status = "stm_statuses";
          $statuspio = "id = '".$row['taskStatusID']."'";
          $StData = $db_helper->SingleDataWhere($status, $statuspio);

          $statusClass = "";
          if($StData['statusName'] == "6-Reviewed" OR $StData['statusName'] == "In-Active"){
            $statusClass .= "dark";
          }
          if($StData['statusName'] == "1-New Task"){
            $statusClass .= "danger";
          }
          if($StData['statusName'] == "Rejected"){
            $statusClass .= "secondary";
          }
          if($StData['statusName'] == "3-In Progress" OR $StData['statusName'] == "2-Started"){
            $statusClass .= "warning";
          }
          if($StData['statusName'] == "Approved" OR $StData['statusName'] == "7-For Review"){
            $statusClass .= "info";
          }
          $stClass = "";
          
          if($row['reviewStartedAt'] == "" AND $row['reviewEndAt'] == "" ){
            $stClass .= "danger";
          }else if($row['reviewStartedAt'] != "" AND $row['reviewEndAt'] == ""){
            $stClass .= "warning";
          }else if($row['reviewEndAt'] !=""){
            $stClass .= "success";
          }

          $use = "stm_users";
          $wheuse = "id = '".$row['taskAssignedBy']."'";
          $dataUse = $db_helper->SingleDataWhere($use, $wheuse);
          $task_assignee = "";
          $statData = $db_helper->SingleDataWhere('stm_statuses', 'statusName = "5-DONE"');
        $deleteStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
        $data = $db_helper->onlyDISTINCTRecords('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskStatusID !="'.$deleteStatus['id'].'"');

          foreach($data as $task_assignee_data){

            $dataName = $db_helper->SingleDataWhere('stm_users', 'id = "'.$task_assignee_data['taskuserID'].'"');

            $taskData = $db_helper->SingleDataWhere('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskuserID = "'.$task_assignee_data['taskuserID'].'" ORDER By taskStatusID ASC');

            $statusD = $db_helper->SingleDataWhere("stm_statuses", "id = '".$taskData['taskStatusID']."'");

            $statusC = "";
            
            if($statusD['statusName'] == "1-New Task"){
              $statusC .= "danger";
            }
            if($statusD['statusName'] == "Rejected"){
              $statusC .= "secondary";
            }
            if($statusD['statusName'] == "3-In Progress" OR $statusD['statusName'] == "2-Started"){
              $statusC .= "warning";
            }
            if($statusD['statusName'] == "Approved" OR $statusD['statusName'] == "5-Done"){
              $statusC .= "success";
            }

            $task_assignee .= "<span class='badge badge-".$statusC."'>".$dataName['displayName']."</span>&nbsp";
          }
          
          $proio = "stm_priorities";
          $wheproio = "id = '".$row['taskPriorityID']."'";
          $datawheproio = $db_helper->SingleDataWhere($proio, $wheproio);

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


          $creationDate = date("d-m-Y", strtotime($row["taskCreationDate"]));
          //$deadlineDate = date("d-m-Y", strtotime($row["taskDeadline"]));

          $start_date = date('d-m-Y');
          // $end_date = date("d-m-Y", strtotime($row["taskDeadline"]));


          // $diff = strtotime($end_date) - strtotime($start_date);
          //   // 1 day = 24 hours
          //   // 24 * 60 * 60 = 86400 seconds
          
          // $daysCount = "&nbsp&nbsp".abs(round($diff / 86400));
         
          // $minusDays = "-&nbsp".abs(round($diff / 86400));  
          
            // if($start_date > $end_date){
            //   if($StData['statusName'] != '6-Reviewed'){
            //     $daysCount = "<span class='label label-important'>".$minusDays."</span>";
            //   }else{
            //     $daysCount = "";
            //   }
            // }         
            $supData = $db_helper->SingleDataWhere('stm_supplier','id = "'.$row['taskSupplierID'].'"');
            $supplier = $supData['supplierName'];

            $taskName = $row['taskName'];

            $string = strip_tags($taskName);
            if (strlen($string) > 30) {
                // truncate string
                $stringCut = substr($string, 0, 30);
                $endPoint = strrpos($stringCut, ' ');

                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $string .= '...';
            }

          $data_messages = $db_helper->SingleDataWhere('stm_messages','taskID = "'.$row['id'].'"');
          $message_icon = "";
          if($data_messages){
            $message_icon = '<a class="green_message_icon" href="stmtaskdetail.php?id='.$row['id'].'&view#messages" title="Chat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>';
          }else{
            $message_icon = '<a class="light_message_icon" href="stmtaskdetail.php?id='.$row['id'].'&view#messages" title="Chat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>';
          }
          
          $deleteStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
          $class = "";
          if($deleteStatus['id'] == $row['taskStatusID']){
            $class = 'class="task_row_opacity"';
          }
          $tasks = $db_helper->SingleDataWhere('stm_tasks','id = "'.$row['id'].'"');
          $activeIcon = "";
          if($tasks['taskStatusID'] == $deleteStatus['id']){
            $activeIcon .= '<a class="active_task" title="Active" data-id="'.$row['id'].'" target="_blank"><svg style="color:#805dca;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></a>';
          }else{
            $activeIcon .= '<a href="stmtaskedit.php?id='.$row['id'].'" title="Edit" target="_blank"><svg style="color:#04AA6D; height:17px !important; width:17px !important;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
              <a class="inActive" data-id="'.$row['id'].'" title="Delete" target="_blank"><svg style="color:#e7515a;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
          }     
      }    

    }else{
        $tblType = "stm_tasktypes";
          $wheType = "id = '".$row['taskTypeID']."'";
          $type = $db_helper->SingleDataWhere($tblType, $wheType);

          $status = "stm_statuses";
          $statuspio = "id = '".$row['taskStatusID']."'";
          $StData = $db_helper->SingleDataWhere($status, $statuspio);

          $statusClass = "";
          if($StData['statusName'] == "6-Reviewed" OR $StData['statusName'] == "In-Active"){
            $statusClass .= "dark";
          }
          if($StData['statusName'] == "1-New Task"){
            $statusClass .= "danger";
          }
          if($StData['statusName'] == "Rejected"){
            $statusClass .= "secondary";
          }
          if($StData['statusName'] == "3-In Progress" OR $StData['statusName'] == "2-Started"){
            $statusClass .= "warning";
          }
          if($StData['statusName'] == "Approved" OR $StData['statusName'] == "7-For Review"){
            $statusClass .= "info";
          }
          $stClass = "";
          
          if($row['reviewStartedAt'] == "" AND $row['reviewEndAt'] == "" ){
            $stClass .= "danger";
          }else if($row['reviewStartedAt'] != "" AND $row['reviewEndAt'] == ""){
            $stClass .= "warning";
          }else if($row['reviewEndAt'] !=""){
            $stClass .= "success";
          }

          $use = "stm_users";
          $wheuse = "id = '".$row['taskAssignedBy']."'";
          $dataUse = $db_helper->SingleDataWhere($use, $wheuse);
          $task_assignee = "";
          $statData = $db_helper->SingleDataWhere('stm_statuses', 'statusName = "5-DONE"');
        $deleteStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
        $data = $db_helper->onlyDISTINCTRecords('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskStatusID !="'.$deleteStatus['id'].'"');

          foreach($data as $task_assignee_data){

            $dataName = $db_helper->SingleDataWhere('stm_users', 'id = "'.$task_assignee_data['taskuserID'].'"');

            $taskData = $db_helper->SingleDataWhere('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskuserID = "'.$task_assignee_data['taskuserID'].'" ORDER By taskStatusID ASC');

            $statusD = $db_helper->SingleDataWhere("stm_statuses", "id = '".$taskData['taskStatusID']."'");

            $statusC = "";
            
            if($statusD['statusName'] == "1-New Task"){
              $statusC .= "danger";
            }
            if($statusD['statusName'] == "Rejected"){
              $statusC .= "secondary";
            }
            if($statusD['statusName'] == "3-In Progress" OR $statusD['statusName'] == "2-Started"){
              $statusC .= "warning";
            }
            if($statusD['statusName'] == "Approved" OR $statusD['statusName'] == "5-Done"){
              $statusC .= "success";
            }

            $task_assignee .= "<span class='badge badge-".$statusC."'>".$dataName['displayName']."</span>&nbsp";
          }
          
          $proio = "stm_priorities";
          $wheproio = "id = '".$row['taskPriorityID']."'";
          $datawheproio = $db_helper->SingleDataWhere($proio, $wheproio);

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


          $creationDate = date("d-m-Y", strtotime($row["taskCreationDate"]));
          //$deadlineDate = date("d-m-Y", strtotime($row["taskDeadline"]));

          $start_date = date('d-m-Y');
          // $end_date = date("d-m-Y", strtotime($row["taskDeadline"]));


          // $diff = strtotime($end_date) - strtotime($start_date);
          //   // 1 day = 24 hours
          //   // 24 * 60 * 60 = 86400 seconds
          
          // $daysCount = "&nbsp&nbsp".abs(round($diff / 86400));
         
          // $minusDays = "-&nbsp".abs(round($diff / 86400));  
          
            // if($start_date > $end_date){
            //   if($StData['statusName'] != '6-Reviewed'){
            //     $daysCount = "<span class='label label-important'>".$minusDays."</span>";
            //   }else{
            //     $daysCount = "";
            //   }
            // }     

            $supData = $db_helper->SingleDataWhere('stm_supplier','id = "'.$row['taskSupplierID'].'"');
            $supplier = $supData['supplierName'];    
            
            $taskName = $row['taskName'];

            $string = strip_tags($taskName);
            if (strlen($string) > 30) {
                // truncate string
                $stringCut = substr($string, 0, 30);
                $endPoint = strrpos($stringCut, ' ');

                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $string .= '...';
            }

          $data_messages = $db_helper->SingleDataWhere('stm_messages','taskID = "'.$row['id'].'"');
          $message_icon = "";
          if($data_messages){
            $message_icon = '<a class="green_message_icon" href="stmtaskdetail.php?id='.$row['id'].'&view#messages" title="Chat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>';
          }else{
            $message_icon = '<a class="light_message_icon" href="stmtaskdetail.php?id='.$row['id'].'&view#messages" title="Chat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>';
          }
          
          $deleteStatus = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
          $class = "";
          if($deleteStatus['id'] == $row['taskStatusID']){
            $class = 'class="task_row_opacity"';
          }
          $tasks = $db_helper->SingleDataWhere('stm_tasks','id = "'.$row['id'].'"');
          $activeIcon = "";
          if($tasks['taskStatusID'] == $deleteStatus['id']){
            $activeIcon .= '<a class="active_task" title="Active" data-id="'.$row['id'].'" target="_blank"><svg style="color:#805dca;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></a>';
          }else{
            $activeIcon .= '<a href="stmtaskedit.php?id='.$row['id'].'" title="Edit" target="_blank"><svg style="color:#04AA6D; height:17px !important; width:17px !important;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
              <a class="inActive" data-id="'.$row['id'].'" title="Delete" target="_blank"><svg style="color:#e7515a;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
          }
    }
    // <td><span class="badge badge-'.$statusClass.'">'.$StData['statusName'].'</span></td>
    $output .= '
    <tr>
      <td>'.$row['id'].'-('.$creationDate.')</td>
      <td>'.$priClass.'</td>
      <td>'.$type['tasktypeName'].'</td>
      <td>'.$supplier.'</td>
      <td><a data-title="'.$taskName.'" class="title-task">'.$string.'</a></td>
      <td>'.$dataUse['displayName'].'</td>
      <td>'.$task_assignee.'</td>
      
      <td>
          
          <a href="stmtaskdetail.php?id='.$row['id'].'&view" title="View">
          <svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
          </svg>
          </a>
          '.$activeIcon.'
          '.$message_icon.'
          <a class="copyTask" data-id='.$row['id'].' title="Copy">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
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