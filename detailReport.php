<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
  if(isset($_SESSION['id']) OR isset($_SESSION['user']))
  {
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
      $fromDate = ''; 
      if(isset($_GET['fromDate'])){
        $fromDate = $_GET['fromDate'];  
      } 
      $toDate = "";
      if(isset($_GET['toDate'])){
        $toDate = $_GET['toDate'];
      }
      $tb = "stm_users";
      $wh = "id = '$session_id'";
      $session_data = $db_helper->SingleDataWhere($tb, $wh);

    $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');

    $rejected = $db_helper->SingleDataWhere('stm_statuses','statusName = "Rejected"');

    $stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');

    $stNew = $db_helper->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

    $stProg = $db_helper->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
    $stInactive = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
    $new = $stNew['id'];
    $progress = $stProg['id'];
    $done = $status['id']; 
    $approved = $stApprov['id'];
    
    $user = $db_helper->SingleDataWhere('stm_users','id = "'.$_GET['uid'].'"');

      if(isset($_GET['totalassignee'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate <= '$toDate' AND (taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskStatusID NOT IN ('$done','$approved') OR taskEndDate IS NULL) AND taskuserID = '".$_GET['uid']."' AND isActive = '1' ");
      }

      if(isset($_GET['totalrev'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate <= '$toDate' AND (taskApprovedOn BETWEEN '$fromDate' AND '$toDate' OR taskStatusID = '$done') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
      }
      if(isset($_GET['createdby']) && isset($_GET['fromDate']) && isset($_GET['subtask'])){
        
        $dataCreatChild = $DB_HELPER_CLASS->DISTINCTRecords('taskID','stm_taskassigned','subTaskID = "'.$_GET['subtask'].'" AND isActive = "1"');
         foreach($dataCreatChild as $data){
           $record = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks',"id IN ('".$data['taskID']."') AND taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$_GET['uid']."' AND taskStatusID != '".$stInactive['id']."'");
         }
        
      }else if(isset($_GET['createdby']) && !isset($_GET['fromDate']) && isset($_GET['subtask'])){

       $record = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$_GET['uid']."' AND taskStatusID != '".$stInactive['id']."'");

      }else if(isset($_GET['createdby']) && isset($_GET['fromDate']) && !isset($_GET['subtask'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$_GET['uid']."' AND taskStatusID != '".$stInactive['id']."'");
      }
      // if(isset($_GET['createdby'])){
      //   $record = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$_GET['uid']."' AND taskStatusID != '".$stInactive['id']."'");
      // }
      if(isset($_GET['preuserpending'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate < '$fromDate' AND (taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskStatusID NOT IN ('".$done."','".$approved."')) AND taskuserID = '".$_GET['uid']."' AND isActive = '1' ");

      }
      if(isset($_GET['addednewassignee'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$_GET['uid']."' AND isActive = '1' ");
      }


      if(isset($_GET['compthismon']) && isset($_GET['fromDate']) && isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();

        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskID IN ('.$taskIDs.') AND subTaskID = "'.$_GET['subtask'].'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$_GET['uid'].'" AND isActive = "1" ');
          $catData[] = $qury;
          $catCount += count($qury);
        }

      }else if(isset($_GET['compthismon']) && !isset($_GET['fromDate']) && isset($_GET['subtask']) && isset($_GET['category'])){
        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();

        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ('.$taskIDs.') AND subTaskID = "'.$_GET['subtask'].'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$_GET['uid'].'" AND isActive = "1" ');
          $catData[] = $qury;
          $catCount += count($qury);
        }
      
      }else if(isset($_GET['compthismon']) && isset($_GET['fromDate']) && !isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();

        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskID IN ('.$taskIDs.') AND taskStatusID IN ("16","21") AND taskuserID = "'.$_GET['uid'].'" AND isActive = "1" ');
          $catData[] = $qury;
          $catCount += count($qury);
        }
      
      }else if(isset($_GET['compthismon']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();

        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ('.$taskIDs.') AND taskStatusID IN ("16","21") AND taskuserID = "'.$_GET['uid'].'" AND isActive = "1" ');
          $catData[] = $qury;
          $catCount += count($qury);
        }
      
      }else if(isset($_GET['compthismon']) && !isset($_GET['fromDate']) && isset($_GET['subtask']) && !isset($_GET['category'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskuserID = '".$_GET['uid']."' AND subTaskID = ".$_GET['subtask']." AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
      
      }else if(isset($_GET['compthismon']) && isset($_GET['fromDate']) && isset($_GET['subtask']) && !isset($_GET['category'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskuserID = '".$_GET['uid']."' AND subTaskID = ".$_GET['subtask']." AND taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
      
      }else if(isset($_GET['compthismon']) && isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskuserID = '".$_GET['uid']."' AND taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
      
      }else if(isset($_GET['compthismon']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskuserID = '".$_GET['uid']."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
      }else if(!isset($_GET['compthismon']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskuserID = '".$_GET['uid']."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
      }

      
      
      if(isset($_GET['pendthismon']) && isset($_GET['subtask']) && isset($_GET['category']) && isset($_GET['fromDate']) && isset($_GET['toDate'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        //$catCount = 0;
        foreach($cats as $task){
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND subTaskID = '".$_GET['subtask']."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");
          $catData[] = $qury;
          // $catCount += count($qury);
        } 
      
      }else if(isset($_GET['pendthismon']) && isset($_GET['subtask']) && isset($_GET['category']) && !isset($_GET['fromDate']) && !isset($_GET['toDate'])){
        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        //$catCount = 0;
        foreach($cats as $task){
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND subTaskID = '".$_GET['subtask']."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");
          $catData[] = $qury;
          // $catCount += count($qury);
        } 
      
      }else if(isset($_GET['pendthismon']) && !isset($_GET['subtask']) && isset($_GET['category']) && isset($_GET['fromDate']) && isset($_GET['toDate'])){
        
        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        //$catCount = 0;
        foreach($cats as $task){
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");
          $catData[] = $qury;
          // $catCount += count($qury);
        } 
      
      }else if(isset($_GET['pendthismon']) && !isset($_GET['subtask']) && isset($_GET['category']) && !isset($_GET['fromDate']) && !isset($_GET['toDate'])){
        
        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        //$catCount = 0;
        foreach($cats as $task){
          $taskIDs = $task['id'];
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");
          $catData[] = $qury;
          // $catCount += count($qury);
        } 
      
      }else if(isset($_GET['pendthismon']) && isset($_GET['subtask']) && !isset($_GET['category']) && !isset($_GET['fromDate']) && !isset($_GET['toDate'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_GET['subtask']."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");

      }else if(isset($_GET['pendthismon']) && isset($_GET['subtask']) && !isset($_GET['category']) && isset($_GET['fromDate']) && isset($_GET['toDate'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_GET['subtask']."' AND taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");

      }else if(isset($_GET['pendthismon']) && !isset($_GET['subtask']) && !isset($_GET['category']) && isset($_GET['fromDate']) && isset($_GET['toDate'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");

      }else if(isset($_GET['pendthismon']) && !isset($_GET['subtask']) && !isset($_GET['category']) && !isset($_GET['fromDate']) && !isset($_GET['toDate'])){
        
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID NOT IN ('16','21') AND taskuserID = '".$_GET['uid']."' AND isActive = '1'");

      }
      
      
      if(isset($_GET['comprev']) && isset($_GET['fromDate']) && isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $query = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND (taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' OR taskApprovedOn BETWEEN '".$fromDate."' AND '".$toDate."') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND subTaskID = '".$_GET['subtask']."' AND isActive = '1' ");

          $catData[] = $query;
          $catCount += count($query);

        }
        
      }else if(isset($_GET['comprev']) && !isset($_GET['fromDate']) && isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $query = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND subTaskID = '".$_GET['subtask']."' AND isActive = '1' ");

          $catData[] = $query;
          $catCount += count($query);

        }
        
      }else if(isset($_GET['comprev']) && isset($_GET['fromDate']) && !isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $query = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND (taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' OR taskApprovedOn BETWEEN '".$fromDate."' AND '".$toDate."') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

          $catData[] = $query;
          $catCount += count($query);

        }
        
      }else if(isset($_GET['comprev']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          $query = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

          $catData[] = $query;
          $catCount += count($query);

        }
        
      }else if(isset($_GET['comprev']) && !isset($_GET['fromDate']) && isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_GET['subtask']."' AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }else if(isset($_GET['comprev']) && isset($_GET['fromDate']) && isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_GET['subtask']."' AND (taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' OR taskApprovedOn BETWEEN '".$fromDate."' AND '".$toDate."') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }else if(isset($_GET['comprev']) && isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"(taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' OR taskApprovedOn BETWEEN '".$fromDate."' AND '".$toDate."') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }else if(isset($_GET['comprev']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }

      
      if(isset($_GET['unreview']) && isset($_GET['fromDate']) && isset($_GET['subtask']) && isset($_GET['category'])){
        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID = '".$done."' AND taskEndDate BETWEEN '".$_GET['fromDate']."' AND '".$_GET['toDate']."' AND taskApprovedOn IS NULL AND subTaskID = '".$_GET['subtask']."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

          $catData[] = $qury;
          $catCount += count($qury);
        }
        
      }else if(isset($_GET['unreview']) && !isset($_GET['fromDate']) && isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID = '".$done."' AND subTaskID = '".$_GET['subtask']."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

          $catData[] = $qury;
          $catCount += count($qury);
        }
      
      }else if(isset($_GET['unreview']) && isset($_GET['fromDate']) && !isset($_GET['subtask']) && isset($_GET['category'])){
        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID = '".$done."' AND taskEndDate BETWEEN '".$_GET['fromDate']."' AND '".$_GET['toDate']."' AND taskApprovedOn IS NULL AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

          $catData[] = $qury;
          $catCount += count($qury);
        }
        
      }else if(isset($_GET['unreview']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && isset($_GET['category'])){

        $cats = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_GET['category'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        
        $catData = array();
        //$record = array();
        $catCount = 0;
        foreach($cats as $task){
          
          $taskIDs = $task['id'];
          
          $qury = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID = '".$done."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

          $catData[] = $qury;
          $catCount += count($qury);
        }
        
      }else if(isset($_GET['unreview']) && !isset($_GET['fromDate']) && isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_GET['subtask']."' AND taskStatusID = '".$done."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }else if(isset($_GET['unreview']) && isset($_GET['fromDate']) && isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_GET['subtask']."' AND taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskApprovedOn IS NULL AND taskStatusID = '".$done."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }else if(isset($_GET['unreview']) && isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskApprovedOn IS NULL AND taskStatusID = '".$done."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }else if(isset($_GET['unreview']) && !isset($_GET['fromDate']) && !isset($_GET['subtask']) && !isset($_GET['category'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID = '".$done."' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");
        
      }
      
      if(isset($_GET['prerevpend']) && isset($_GET['reviewers'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskApprovedOn BETWEEN '$fromDate' AND '$toDate' OR taskStatusID = '$done') AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

      }
      if(isset($_GET['addednewrev']) && isset($_GET['reviewers'])){

        $record = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskSupervisorID = '".$_GET['uid']."' AND isActive = '1' ");

      }

?>
<body>
    <?php
      include_once "partials/navbar.php";
    ?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>
         <?php
          include_once "partials/sidebar.php";
         ?>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-md-12">
                        <div class="statbox widget box box-shadow">
                          <div class="widget-header">
                            <h4 class="mt-2">
                              <?php
                              $dates = "";
                              if(isset($_GET['assignees'])){
                                if(isset($_GET['fromDate'])){
                                  $dates = "FROM ".date('d-m-Y',strtotime($_GET['fromDate']))."&nbspTO&nbsp".date('d-m-Y',strtotime($_GET['toDate']));
                                }
                                echo "ASSIGNEES TASK REPORT ".$dates."<br>";
                                echo "ASSIGNEE: ".$user['userName'];
                              }else if(isset($_GET['reviewers'])){
                                echo "SUPERVISOR TASK REVIEW REPORT ".$dates."<br>";
                                echo "SUPERVISOR: ".$user['userName'];
                              }?>
                              
                              <br>
                            </h4>
                              
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-bordered">
                            <?php 
                            if(isset($_GET['createdby'])){
                            ?>
                            <tr>
                                <th>TASK ID</th>
                                <th>TITLE</th>
                                <th>ADDED BY</th>
                                <th>CREATED ON</th>
                                <th>SUPPLIER</th>
                                <th>STATUS</th>
                            </tr> 
                            <?php  
                            }else{

                            ?>
                            <tr>
                                <th>TASK ID</th>
                                <th>SUB TASK</th>
                                <th>CHANNEL</th>
                                <th>STORE</th>
                                <?php 
                                if(isset($_GET['reviewers'])){
                                ?>
                                  <th>ASSIGNEES</th>
                                <?php  
                                }
                                ?>
                                <th>CREATED ON</th>
                                <th>ENDED ON</th>
                                <th>STATUS</th>
                                <?php 
                                if(isset($_GET['reviewers'])){
                                ?>
                                  <th>APPROVED ON</th>
                                <?php
                                }
                                ?>
                                <?php 
                                if(isset($_GET['assignees'])){
                                ?>
                                  <th>APPROVED ON</th>
                                  <th>SUPERVISOR</th>
                                <?php  
                                }
                                ?>
                            </tr>
                            <?php
                            }
                            if(isset($_GET['createdby'])){
                              foreach($record as $recordList){
                                $createdOn = date('d-m-Y',strtotime($recordList['taskCreationDate']));
                                $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$recordList['taskStatusID'].'"');
                                $addedby = $db_helper->SingleDataWhere('stm_users','id = "'.$recordList['taskAssignedBy'].'"');
                                $suppl = $db_helper->SingleDataWhere('stm_supplier','id = "'.$recordList['taskSupplierID'].'"');

                                ?>
                                <tr>
                                  <td><a href="stmtaskdetail.php?id=<?php echo $recordList['id'] ?>&view#assignees" target="_blank" class="anchor"><?php echo $recordList['id']; ?></a></td>
                                  <td><?php echo $recordList['taskName']; ?></td>
                                  <td><?php echo $addedby['userName']; ?>
                                  </td>
                                  <td><?php echo $createdOn; ?>
                                  </td>
                                  <td><?php echo $suppl['supplierName']; ?>
                                  </td>
                                  <td><?php echo $status['statusName']; ?></td>
                                  
                                </tr>
                              <?php
                              }
                            }else{
                              if(isset($_GET['category'])){
                                foreach($catData as $listData){

                                  foreach($listData as $recordList){
                                  $createdOn = date('d-m-Y',strtotime($recordList['taskCreationDate']));
                                  $taskEndDate = date('d-m-Y',strtotime($recordList['taskEndDate']));
                                  $taskApprovedOn = date('d-m-Y',strtotime($recordList['taskApprovedOn']));

                                  $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$recordList['subTaskID'].'"');
                                  $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$recordList['taskStatusID'].'"');
                                  $supervisor = $db_helper->SingleDataWhere('stm_users','id = "'.$recordList['taskSupervisorID'].'"');
                                  $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$recordList['taskuserID'].'"');
                                  $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$recordList['taskchannelID'].'"');
                                  $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$recordList['taskstoreID'].'"');
                              ?>

                              <tr>
                                <td><a href="stmtaskdetail.php?id=<?php echo $recordList['taskID'] ?>&view#assignees" target="_blank" class="anchor"><?php echo $recordList['taskID']; ?></a></td>
                                <td><?php echo $subTask['subTask']; ?></td>
                                <td><?php echo $chanel['channelName']; ?></td>
                                <td><?php echo $store['storeName']; ?></td>
                                <?php 
                                if(isset($_GET['reviewers'])){
                                  echo "<td>".$assigneoftask['displayName']."</td>";
                                }
                                ?>
                                <td><?php echo $createdOn; ?>
                                </td>
                                <td>
                                    <?php
                                    if($recordList['taskEndDate']) 
                                      echo $taskEndDate;
                                    ?>
                                </td>
                                <td><?php echo $status['statusName']; ?></td>
                                <?php 
                                if(isset($_GET['reviewers'])){
                                ?>
                                <td><?php
                                    if($recordList['taskApprovedOn']){
                                      echo $taskApprovedOn;
                                    }
                                    ?>
                                </td>
                                <?php  
                                }
                                
                                if(isset($_GET['assignees'])){
                                ?>
                                <td><?php
                                    if($recordList['taskApprovedOn']){
                                      echo $taskApprovedOn;
                                    }
                                    ?>
                                </td>
                                <td><?php echo $supervisor['displayName']; ?></td>
                                <?php  
                                }
                                ?>
                                
                              </tr>
                              <?php
                                  }
                                }  
                              }else{
                                foreach($record as $recordList){
                                
                                $createdOn = date('d-m-Y',strtotime($recordList['taskCreationDate']));
                                $taskEndDate = date('d-m-Y',strtotime($recordList['taskEndDate']));
                                $taskApprovedOn = date('d-m-Y',strtotime($recordList['taskApprovedOn']));

                                $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$recordList['subTaskID'].'"');
                                $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$recordList['taskStatusID'].'"');
                                $supervisor = $db_helper->SingleDataWhere('stm_users','id = "'.$recordList['taskSupervisorID'].'"');
                                $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$recordList['taskuserID'].'"');
                                $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$recordList['taskchannelID'].'"');
                                $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$recordList['taskstoreID'].'"');
                            ?>

                            <tr>
                              <td><a href="stmtaskdetail.php?id=<?php echo $recordList['taskID'] ?>&view#assignees" target="_blank" class="anchor"><?php echo $recordList['taskID']; ?></a></td>
                              <td><?php echo $subTask['subTask']; ?></td>
                              <td><?php echo $chanel['channelName']; ?></td>
                              <td><?php echo $store['storeName']; ?></td>
                              <?php 
                              if(isset($_GET['reviewers'])){
                                echo "<td>".$assigneoftask['displayName']."</td>";
                              }
                              ?>
                              <td><?php echo $createdOn; ?>
                              </td>
                              <td>
                                  <?php
                                  if($recordList['taskEndDate']) 
                                    echo $taskEndDate;
                                  ?>
                              </td>
                              <td><?php echo $status['statusName']; ?></td>
                              <?php 
                              if(isset($_GET['reviewers'])){
                              ?>
                              <td><?php
                                  if($recordList['taskApprovedOn']){
                                    echo $taskApprovedOn;
                                  }
                                  ?>
                              </td>
                              <?php  
                              }
                              
                              if(isset($_GET['assignees'])){
                              ?>
                              <td><?php
                                  if($recordList['taskApprovedOn']){
                                    echo $taskApprovedOn;
                                  }
                                  ?>
                              </td>
                              <td><?php echo $supervisor['displayName']; ?></td>
                              <?php  
                              }
                              ?>
                              
                            </tr>
                            <?php    
                              
                              }
                              }
                                
                              
                            }//else
                            ?>
                            </table>
                              
                          </div>
                        </div>
                    </div>
                </div>
            </div><!---layout-px-spacing-->

        <?php
          include_once "partials/footer.php";
        }else{  
          echo "<script>window.location='signin.php'</script>";
        }
        ?>