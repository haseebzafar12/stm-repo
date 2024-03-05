<?php
ob_start();
session_start();

include_once ('partials/header.php');
include_once ('common/config.php');
include_once ('common/user.php');
include_once ('common/db_helper.php');
include_once ('common/announceClass.php');

$dbcon = new Database();
$db = $dbcon->getConnection();
$DB_HELPER_CLASS = new db_helper($db);
$objUser = new User($db);
$objAnnouce = new announceClass($db);

$session_id = "";
if(isset($_SESSION['user'])){
$session_id = $_SESSION['user'];
}else if(isset($_SESSION['id'])){
$session_id = $_SESSION['id'];
}


$done = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "5-Done"');

$stApprov = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "Approved"');

$stNew = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

$stProg = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
$stInactive = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "In-Active"');
$rejected = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "Rejected"');
$forRev = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "7-For Review"');
$reviewed = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');
    
    $catEmp     = '1';
    $subtask    = '1';
    $fromDate   = '2023-01-01';
    $toDate     = '2023-01-09';

    $cats = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "7" AND taskStatusID != "'.$stInactive['id'].'" ');
    $sum = 0;
    $data = array();
    foreach($cats as $task){
      $taskIDs = $task['id'];
      $record = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ("'.$taskIDs.'") AND taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND subTaskID = "'.$subtask.'" AND taskStatusID IN ("16","21") AND taskuserID = "8" AND isActive = "1" ');
       $data[] = $record;
    }
   
    // //echo $taskIDs."<br>";
    // echo "<pre>";
    // print_r($data);
    foreach($data as $recordList){
      foreach($recordList as $list){
        echo $list['id'];
      }
    }

?>