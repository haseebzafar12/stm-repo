<?php
ob_start();
session_start();

include('../../smtp/PHPMailerAutoload.php');     
include_once ('../config.php');
include_once ('../db_helper.php');
include_once ('../user.php');
include_once ('../announceClass.php');

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

if(isset($_POST['post_m']) && $_POST['post_m'] == "delImage"){
    if(unlink('../../upload/'.$_POST['file'])){
        echo '1';
    }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "filterEmp"){
    
    $catEmp     = $_POST['catEmp'];
    $subtask    = $_POST['subtaskEmp'];
    $fromDate   = $_POST['fDate'];
    $toDate     = $_POST['tDate'];

      $output = "";
      $users = $DB_HELPER_CLASS->allRecordsOrderBy('stm_users','userName ASC');
      foreach($users as $allusers){
        
        $createdLink = "";
        $complLink = "";
        $pendLink = "";
        $unreviewLink = "";
        $approvedLink = "";

        if(!empty($_POST['catEmp']) AND !empty($_POST['subtaskEmp']) AND !empty($_POST['fDate']) AND !empty($_POST['tDate'])){

            $cats = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_POST['catEmp'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
            $totalRecordsCompleted = 0;
            $totalApprovedOn = 0;
            $total_pending_task = 0;
            $totalUnreviewed = 0;
            foreach($cats as $task){
                $taskIDs = $task['id'];
                $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ('.$taskIDs.') AND taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND subTaskID = "'.$_POST['subtaskEmp'].'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');
               
                $totalRecordsCompleted += count($dataCompleted);    
               
               $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND subTaskID = '".$subtask."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

               if($pendThisMonth){
                $total_pending_task += count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&subtask='.$subtask.'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';
               }

               $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND (taskEndDate BETWEEN '".$_POST['fDate']."' AND '".$_POST['tDate']."' OR taskApprovedOn BETWEEN '".$_POST['fDate']."' AND '".$_POST['tDate']."') AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND subTaskID = '".$_POST['subtaskEmp']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               $totalApprovedOn += count($approvedOn);

               $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID = '".$done['id']."' AND taskEndDate BETWEEN '".$_POST['fDate']."' AND '".$_POST['tDate']."' AND taskApprovedOn IS NULL AND subTaskID = '".$_POST['subtaskEmp']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               if($unreviewed){
                $totalUnreviewed += count($unreviewed);
                $unreviewLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
               }

            }
            if($totalRecordsCompleted > 0){
              $complLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }
            if($approvedOn){
              $approvedLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalApprovedOn.'</a>';    
            }
            
        }else if(!empty($_POST['catEmp']) AND !empty($_POST['subtaskEmp']) AND empty($_POST['fDate']) AND empty($_POST['tDate'])){
            $cats = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_POST['catEmp'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
            $totalRecordsCompleted = 0;
            $totalApprovedOn = 0;
            $totalUnreviewed = 0;
            $total_pending_task = 0; 
            foreach($cats as $task){
                $taskIDs = $task['id'];
                $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ('.$taskIDs.') AND subTaskID = "'.$_POST['subtaskEmp'].'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');
               
                $totalRecordsCompleted += count($dataCompleted);    
               
               $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND subTaskID = '".$_POST['subtaskEmp']."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

               if($pendThisMonth){
                $total_pending_task += count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';
               }

               $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND subTaskID = '".$_POST['subtaskEmp']."' AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               $totalApprovedOn += count($approvedOn);

               $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND subTaskID = '".$_POST['subtaskEmp']."' AND taskStatusID = '".$done['id']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               if($unreviewed){
                $totalUnreviewed += count($unreviewed);
                $unreviewLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
               }

            }
            if($totalRecordsCompleted > 0){
              $complLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }
            if($approvedOn){
              $approvedLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalApprovedOn.'</a>';    
            }
        
        }else if(!empty($_POST['catEmp']) AND empty($_POST['subtaskEmp']) AND !empty($_POST['fDate']) AND !empty($_POST['tDate'])){

            $cats = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_POST['catEmp'].'" AND taskStatusID != "'.$stInactive['id'].'" ');

           $totalRecordsCompleted = 0;
           $totalApprovedOn = 0;
           $total_pending_task = 0;
           $totalUnreviewed = 0;
            foreach($cats as $task){
                $taskIDs = $task['id'];
                $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ('.$taskIDs.') AND taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');
              
               
                $totalRecordsCompleted += count($dataCompleted);    
               
               $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN (".$taskIDs.") AND taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

               if($pendThisMonth){
                $total_pending_task += count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';
               }

               $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND (taskEndDate BETWEEN '".$_POST['fDate']."' AND '".$_POST['tDate']."' OR taskApprovedOn BETWEEN '".$_POST['fDate']."' AND '".$_POST['tDate']."') AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               $totalApprovedOn += count($approvedOn);

               $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ("'.$task['id'].'") AND taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskApprovedOn IS NULL AND taskStatusID = "'.$done['id'].'" AND taskSupervisorID = "'.$allusers['id'].'" AND isActive = "1" ');

               if($unreviewed){
                $totalUnreviewed += count($unreviewed);
                $unreviewLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
               }

            }
            if($totalRecordsCompleted > 0){
              $complLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }
            if($approvedOn){
              $approvedLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&fromDate='.$_POST['fDate'].'&toDate='.$_POST['tDate'].'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalApprovedOn.'</a>';    
            }
        
        }else if(!empty($_POST['catEmp']) AND empty($_POST['subtaskEmp']) AND empty($_POST['fDate']) AND empty($_POST['tDate'])){    
         
            $cats = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$_POST['catEmp'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
            $totalRecordsCompleted = 0;
            $totalApprovedOn = 0;
            $total_pending_task = 0;
            $totalUnreviewed = 0;

            foreach($cats as $task){
                $taskIDs = $task['id'];
                $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID IN ('.$taskIDs.') AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');
               
                $totalRecordsCompleted += count($dataCompleted);    
               
               $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN (".$taskIDs.") AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

               if($pendThisMonth){
                $total_pending_task += count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';
               }

               $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               $totalApprovedOn += count($approvedOn);

               $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskID IN ('".$taskIDs."') AND taskStatusID = '".$done['id']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

               if($unreviewed){
                $totalUnreviewed += count($unreviewed);
                $unreviewLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
               }

            }
            if($totalRecordsCompleted > 0){
              $complLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }
            if($approvedOn){
              $approvedLink = '<a class="anchor" href="detailReport.php?category='.$_POST['catEmp'].'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalApprovedOn.'</a>';    
            }

        }else if(empty($_POST['catEmp']) AND !empty($_POST['subtaskEmp']) AND empty($_POST['fDate']) AND empty($_POST['tDate'])){

            
            $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','subTaskID = "'.$_POST['subtaskEmp'].'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');

            if($dataCompleted){
                $totalRecordsCompleted = count($dataCompleted);
                $complLink = '<a class="anchor" href="detailReport.php?subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }

            $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_POST['subtaskEmp']."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

            if($pendThisMonth){
                $total_pending_task = count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';   
            }
            $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned'," subTaskID = '".$_POST['subtaskEmp']."' AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($approvedOn){
                $totalapprovedOn = count($approvedOn);
                $approvedLink = '<a class="anchor" href="detailReport.php?subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalapprovedOn.'</a>';   
            }
            $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_POST['subtaskEmp']."' AND taskStatusID = '".$done['id']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($unreviewed){
              $totalUnreviewed = count($unreviewed);
              $unreviewLink = '<a class="anchor" href="detailReport.php?subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
            }
        
        }else if(empty($_POST['catEmp']) AND !empty($_POST['subtaskEmp']) AND !empty($_POST['fDate']) AND !empty($_POST['tDate'])){

            $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','subTaskID = "'.$_POST['subtaskEmp'].'" AND taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');

            if($dataCompleted){
                $totalRecordsCompleted = count($dataCompleted);
                $complLink = '<a class="anchor" href="detailReport.php?subtask='.$_POST['subtaskEmp'].'&fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }

            $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_POST['subtaskEmp']."' AND taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

            if($pendThisMonth){
                $total_pending_task = count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';   
            }
            $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_POST['subtaskEmp']."' AND taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($approvedOn){
                $totalapprovedOn = count($approvedOn);
                $approvedLink = '<a class="anchor" href="detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&subtask='.$_POST['subtaskEmp'].'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalapprovedOn.'</a>';   
            }
            $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"subTaskID = '".$_POST['subtaskEmp']."' AND taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskApprovedOn IS NULL AND taskStatusID = '".$done['id']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($unreviewed){
              $totalUnreviewed = count($unreviewed);
              $unreviewLink = '<a class="anchor" href="detailReport.php?subtask='.$_POST['subtaskEmp'].'&fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
            }
        
        }else if(empty($_POST['catEmp']) AND empty($_POST['subtaskEmp']) AND !empty($_POST['fDate']) AND !empty($_POST['tDate'])){

            $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskEndDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');

            if($dataCompleted){
                $totalRecordsCompleted = count($dataCompleted);
                $complLink = '<a class="anchor" href="detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }

            $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

            if($pendThisMonth){
                $total_pending_task = count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';   
            }
            $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"(taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' OR taskApprovedOn BETWEEN '".$fromDate."' AND '".$toDate."') AND taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($approvedOn){
                $totalapprovedOn = count($approvedOn);
                $approvedLink = '<a class="anchor" href="detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalapprovedOn.'</a>';   
            }
            $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '".$fromDate."' AND '".$toDate."' AND taskApprovedOn IS NULL AND taskStatusID = '".$done['id']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($unreviewed){
              $totalUnreviewed = count($unreviewed);
              $unreviewLink = '<a class="anchor" href="detailReport.php?&fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
            }
        
        }else if(empty($_POST['catEmp']) AND empty($_POST['subtaskEmp']) AND empty($_POST['fDate']) AND empty($_POST['tDate'])){

            $dataCompleted = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskStatusID IN ("16","21") AND taskuserID = "'.$allusers['id'].'" AND isActive = "1" ');

            if($dataCompleted){
                $totalRecordsCompleted = count($dataCompleted);
                $complLink = '<a class="anchor" href="detailReport.php?uid='.$allusers['id'].'&compthismon&assignees" target="_blank">'.$totalRecordsCompleted.'</a>';    
            }

            $pendThisMonth = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

            if($pendThisMonth){
                $total_pending_task = count($pendThisMonth);
                $pendLink = '<a class="anchor" href="detailReport.php?uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">'.$total_pending_task.'</a>';   
            }
            $approvedOn = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID IN ('".$stApprov['id']."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($approvedOn){
                $totalapprovedOn = count($approvedOn);
                $approvedLink = '<a class="anchor" href="detailReport.php?uid='.$allusers['id'].'&comprev&reviewers" target="_blank">'.$totalapprovedOn.'</a>';   
            }
            $unreviewed = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID = '".$done['id']."' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");
            if($unreviewed){
              $totalUnreviewed = count($unreviewed);
              $unreviewLink = '<a class="anchor" href="detailReport.php?uid='.$allusers['id'].'&unreview&reviewers" target="_blank">'.$totalUnreviewed.'</a>';
            }
        }

        $output .= '<tr>';
          $output .= '<td>'.$allusers['userName'].'</td>';
          $output .= '<td align="right">'.$complLink.'</td>';
          $output .= '<td align="right">'.$pendLink.'</td>';
          $output .= '<td align="right">'.$approvedLink.'</td>';
          $output .= '<td align="right">'.$unreviewLink.'</td>';
        $output .= '</tr>';
      }
      echo $output;
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "tokken"){

    $date = date('Y-m-d');
    $objUser->stm_insert_tokken(4,$_POST['currentToken'],$date);
    echo "1";

}
if(isset($_POST['post_m']) && $_POST['post_m'] == "chat-box"){
    
    $userID = $_POST['userID'];
    
    $users = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$userID.'"');
    $ary = array("userName" => $users['userName']);
    echo json_encode($ary);

}
if(isset($_POST['post_m']) && $_POST['post_m'] == "send-message"){

    $toID = $_POST['to_user_id'];
    $fromID = $session_id;

    $file = "";
    // $chatUsers = $DB_HELPER_CLASS->SingleDataWhere('stm_chat','fromID = "'.$_POST['to_user_id'].'" AND toID = "'.$session_id.'"');
    // if($chatUsers['fromID'] == $_POST['to_user_id'] && $chatUsers['toID'] == $session_id){
        
    // }
    $objUser->update_read_status('1',$session_id);

    if(isset($_POST['file'])){
        $file = $_POST['file'];
    }

    $chatMessage = addslashes($_POST['chat_message']);
    date_default_timezone_set('Asia/Karachi');
    $date = date('Y-m-d H:i:s');
    
    $insertQ = $objUser->stm_send_mesage($fromID,$toID,$chatMessage,'0',$date,$date,$file);

    $output = "";
    $output .= '<div class="chat-conversation-box">';
    $output .= '<div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">';
    $output .='<div class="chat">';
    $load = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_chat','(fromID = '.$fromID.' AND toID = '.$toID.') OR (fromID = '.$toID.' AND toID = '.$fromID.') ORDER BY createdOn ASC');
        $user_to = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$toID.'"');
        $user_from = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$fromID.'"');
        foreach($load as $loadContent){
            $extension = pathinfo($loadContent['attachement'], PATHINFO_EXTENSION);
            $dataToID = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$loadContent['toID'].'"');

            if($loadContent['fromID'] == $fromID){
                
                $file = "";
                if($loadContent['attachement'] != ""){
                   if($extension == 'jpeg' OR $extension == 'jpg' OR $extension == 'png' OR $extension == 'gif' OR $extension == 'webp'){
                    $file = "<img src='././upload/".$loadContent['attachement']."' height='90' width='90' class='rounded-circle attachement' data-file='".$loadContent['attachement']."'><br>"; 
                   }else{
                    $file = "<div class='attachement' data-file='".$loadContent['attachement']."'>".$loadContent['attachement']."</div><br>";
                   } 
                    
                } 
               $img = "";
               if(!$user_from['userDP']){
                $img = "<img src='././assets/img/90x90.jpg' height='20' width='20' class='rounded-circle'>";
               }else{
                $img = "<img src='././images/".$user_from['userDP']."' height='20' width='20' class='rounded-circle'>";
               }
               
               $output .= '<div class="labelDate me"><small>'.$img.' - </small><em>'.date('d-m-Y H:i A',strtotime($loadContent['createdOn'])).'</em></div>';
               $output .= '<div class="bubble me">'.$file.$loadContent['message'].'</div>';      
                
            }else{
               $img = "";
               if(!$user_to['userDP']){
                $img = "<img src='././assets/img/90x90.jpg' height='20' width='20' class='rounded-circle'><br>";
               }else{
                $img = "<img src='././images/".$user_to['userDP']."' height='20' width='20' class='rounded-circle'>";
               }
                $file = "";
                if($loadContent['attachement'] != ""){
                   if($extension == 'jpeg' OR $extension == 'jpg' OR $extension == 'png' OR $extension == 'gif' OR $extension == 'webp'){
                    $file = "<img src='././upload/".$loadContent['attachement']."' height='90' width='90' class='rounded-circle attachement' data-file='".$loadContent['attachement']."'><br>"; 
                   }else{
                    $file = "<div class='attachement' data-file='".$loadContent['attachement']."'>".$loadContent['attachement']."</div><br>";
                   } 
                    
                } 
               // $output .="<small>".$img." - <em>".date('d-m-Y H:i:s',strtotime($loadContent['createdOn']))."</em></small><br>";
                 
               $output .= '<div class="labelDate you"><small>'.$img.' - </small><em>'.date('d-m-Y H:i A',strtotime($loadContent['createdOn'])).'</em></div>';
               $output .= '<div class="bubble you">'.$file.$loadContent['message'].'</div>';

            } 
           
        }
        
    $output .='</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    echo $output;

}
if(isset($_POST['post_m']) && $_POST['post_m'] == "loadChat"){
    
    $toID = $_POST['to_user_id'];    
    
    $fromID = $session_id;
    
    $output = "";
    $output .= '<div class="chat-conversation-box">';
    $output .= '<div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">';
    $output .='<div class="chat">';
    
        $load = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_chat','(fromID = '.$fromID.' AND toID = '.$toID.') OR (fromID = '.$toID.' AND toID = '.$fromID.') ORDER BY createdOn ASC');

        $user_to = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$toID.'"');
        $user_from = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$fromID.'"');
        foreach($load as $loadContent){
            $extension = pathinfo($loadContent['attachement'], PATHINFO_EXTENSION);
            $dataToID = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$loadContent['toID'].'"');
            $d = date('d-m-Y',strtotime($loadContent['createdOn']))."&nbsp";
            $time = date('h:i A', strtotime($loadContent['createdOn'])); 
            if($loadContent['fromID'] == $fromID){
                
                $file = "";
                if($loadContent['attachement'] != ""){
                   if($extension == 'jpeg' OR $extension == 'jpg' OR $extension == 'png' OR $extension == 'gif' OR $extension == 'webp'){
                    $file = "<img src='././upload/".$loadContent['attachement']."' height='90' width='90' class='rounded-circle attachement' data-file='".$loadContent['attachement']."'><br>"; 
                   }else{
                    $file = "<div class='attachement' data-file='".$loadContent['attachement']."'>".$loadContent['attachement']."</div><br>";
                   } 
                    
                } 
               $img = "";
               if(!$user_from['userDP']){
                $img = "<img src='././assets/img/90x90.jpg' height='20' width='20' class='rounded-circle'>";
               }else{
                $img = "<img src='././images/".$user_from['userDP']."' height='20' width='20' class='rounded-circle'>";
               }
               
               $output .= '<div class="labelDate me"><small>'.$img.'  </small><em>'.$d.$time.'</em></div>';
               $output .= '<div class="bubble me">'.$file.$loadContent['message'].'</div>';      
                
            }else{
               $img = "";
               if(!$user_to['userDP']){
                $img = "<img src='././assets/img/90x90.jpg' height='20' width='20' class='rounded-circle'>";
               }else{
                $img = "<img src='././images/".$user_to['userDP']."' height='20' width='20' class='rounded-circle'>";
               }
                $file = "";
                if($loadContent['attachement'] != ""){
                   if($extension == 'jpeg' OR $extension == 'jpg' OR $extension == 'png' OR $extension == 'gif' OR $extension == 'webp'){
                    $file = "<img src='././upload/".$loadContent['attachement']."' height='90' width='90' class='rounded-circle attachement' data-file='".$loadContent['attachement']."'><br>"; 
                   }else{
                    $file = "<div class='attachement' data-file='".$loadContent['attachement']."'>".$loadContent['attachement']."</div><br>";
                   } 
                    
                }
               
               $output .= '<div class="labelDate you"><small>'.$img.'  </small><em>'.$d.$time.'</em></div>';
               $output .= '<div class="bubble you">'.$file.$loadContent['message'].'</div>';

            } 
           
        }
        
    $objUser->stm_update_chat_status($fromID,$toID);
    $output .='</div>';
    $output .= '</div>';
    $output .= '</div>';

    echo $output;
    
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "attend"){

    $attenDate = $_POST['attenDate'];
    
    if($_POST['attend'] == "p"){
        $present = 1;
        $leave = 0;
    }else{
        $present = 0;
        $leave = 1;
    }
    $list = $DB_HELPER_CLASS->SingleDataWhere('stm_attendance','userID = "'.$_POST['userID'].'" AND attendanceDate = "'.$attenDate.'"');
    if($list['userID'] == $_POST['userID'] && $list['attendanceDate'] == $attenDate){
        echo "error";
    }else{
        
        $objUser->stm_attendance($_POST['userID'],$present,$leave,$attenDate);
        
        if($_POST['attend'] == "l"){
            
            $year = date('Y',strtotime($attenDate));
            
            $detailData = $DB_HELPER_CLASS->SingleDataWhere('stm_attendance_user_details','userID = "'.$_POST['userID'].'" AND Year = "$year"');

            if($detailData['Year'] == $year && $detailData['userID'] == $_POST['userID']){

            }else{
                
                if($_POST['leave'] == "ab"){
                  $ab = 1;  
                }else{
                  $ab = 0;  
                }
                
                if($_POST['leave'] == "cl"){
                  $cl = 1;  
                }else{
                  $cl = 0;  
                }
                
                if($_POST['leave'] == "sl"){
                  $sl = 1;  
                }else{
                  $sl = 0;  
                }

                if($_POST['leave'] == "hl"){
                  $hl = 1;  
                }else{
                  $hl = 0;  
                }
                $totalSpend = 1;
                $totalRemaining = 24 - 1;
                $objUser->stm_attendance_detail($_POST['userID'], $cl, $sl, $ab,$hl,$totalSpend,$totalRemaining,$year);
            }
            
               
        }    
    }
    
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "loadContent"){
  
  
  $userD = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_users','id != '.$session_id.' ORDER by readStatus DESC,onlineStatus DESC,userName ASC');
  
  $output = '';
  foreach($userD as $users){
    
    $status = '';
    
    //date_default_timezone_set('Asia/Karachi');
    $currentTime = strtotime(date('Y-m-d H:i:s').'-10 second');
    $current_timestamp = date('Y-m-d H:i:s',$currentTime);

    $data = $DB_HELPER_CLASS->SingleDataWhere('stm_login_details','user_id = '.$users['id'].' ORDER BY last_activity DESC ');
  
    $user_last_activity = $data['last_activity'];
    if($user_last_activity > $current_timestamp){
       
       $status = "<span class='user-meta-time'><span style='font-size:9px;'><img src='images/online.png' height='20' width='20'></span>";
       $objUser->update_online_status(1,$users['id']);

    }else{
        
        if($data['last_activity']){
            $status = 'Last Seen '.$DB_HELPER_CLASS->timeago($data['last_activity']);
        }
        $objUser->update_online_status(0,$users['id']);       
    }

    $statement = $db->prepare('SELECT * from stm_chat WHERE fromID = "'.$users['id'].'" AND toID = "'.$session_id.'" AND isOpen = "0" ');
    $statement->execute();
    $cunt = $statement->rowCount();
    $result = $statement->fetchAll();
    $unseen = "";

    if($cunt > 0){
        $unseen = "<span class='badge badge-danger'>".$cunt."</span>";

    }else{
         
        $row = $DB_HELPER_CLASS->SingleDataWhere('stm_chat','fromID = "'.$users['id'].'" AND toID = "'.$session_id.'" AND isOpen = "1"');

        $objUser->update_read_status(0,$row['fromID']);
    }

    $istype = $DB_HELPER_CLASS->SingleDataWhere('stm_login_details','user_id = "'.$users['id'].'" ORDER By last_activity DESC');
    $typing = "";
    if($istype['is_type'] == '1'){
        $typing = "<small><em>Typing...</em></small>";
    }
    
    $output .= '<div class="person" data-chat="person_'.$users['id'].'" data-touserid="'.$users['id'].'" data-username="'.$users['userName'].'" data-image="'.$users['userDP'].'">';
        $output .= '<div class="user-info">';
            $output .= '<div class="f-head">';
                if($users['userDP'] != ""){
                    $output .= '<img src="images/'.$users['userDP'].'" alt="avatar">';
                }else{
                    $output .= '<img src="assets/img/90x90.jpg" alt="avatar">';
                }
            $output .= '</div>';
            $output .= '<div class="f-body">';
                $output .= '<div class="meta-info">';
                    $output .= '<span class="user-name" data-name="'.$users['userName'].'">'.$users['displayName'].'&nbsp'.$unseen.'</span><br><small><em>'.$status.$typing.'</em></small>';
                    
                $output .= '</div>';
                $output .= '<span class="preview"></span>';
            $output .= '</div>';
        $output .= '</div>';

        $output .=  '</div>';  
  }
    
  echo $output; 
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "update_last_activity"){

    $data = $objUser->update_last_activity($_SESSION['login_details_id']);
    if($data){
        echo "1";
    }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "focus_message"){

    $data = $objUser->update_isType($_POST['isType'],$_SESSION['login_details_id']);
    if($data){
        echo "1";
    }
}
// if(isset($_POST['post_m']) && $_POST['post_m'] == "updateReadStatus"){
   
//     $objUser->update_read_status('0',$_POST['to_user_id']);

// }
if(isset($_POST['post_m']) && $_POST['post_m'] == "notification"){
    
   $statement = $db->prepare("SELECT id from stm_chat WHERE toID='".$session_id."' AND isOpen = '0' ");
    $statement->execute();
    $count = $statement->rowCount();

    echo $count;
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "load_lwsku_detail"){
   
   $lwsku = $_POST['lwsku']; 
   
   $stm = $db->prepare("select * from stm_listing WHERE LwSku = '$lwsku' Order by ChannelID ASC,StoreID ASC,StoreItemID ASC,ItemID ASC,StoreSKU ASC,StockType ASC");
   $stm->execute();
   $result = $stm->fetchAll();
   $output = "";
   $output .= "<table class='table table-striped table-sm'>";
   $output .= "<tr>";
        $output .= "<td>LW.SKU</td>";
        $output .= "<td>Channel</td>";
        $output .= "<td>Store</td>";
        $output .= "<td>ItemID/Asin</td>";
        $output .= "<td>Store SKU</td>";
        $output .= "<td>Type</td>";
     $output .= "</tr>";
   foreach($result as $list){

     $channel = $DB_HELPER_CLASS->SingleDataWhere('stm_channels','id = "'.$list['ChannelID'].'"');
     $store = $DB_HELPER_CLASS->SingleDataWhere('stm_stores','id = "'.$list['StoreID'].'"');

     $channelID = $list['ChannelID'];
     $itemID = "";
     if($channelID == '1'){
        $itemID = "<a href='https://www.amazon.co.uk/dp/".$list['StoreItemID']."' target='_blank' style='text-decoration:underline;'>".$list['StoreItemID']."</a>";
     }else if($channelID == '2'){
        $itemID = "<a target='_blank' href='https://www.ebay.co.uk/itm/".$list['ItemID']."' style='text-decoration:underline;'>".$list['ItemID']."</a>";
     }

     $output .= "<tr>";
        $output .= "<td>".$list['LwSku']."</td>";
        $output .= "<td>".$channel['channelName']."</td>";
        $output .= "<td>".$store['storeName']."</td>";
        $output .= "<td>".$itemID."</td>";
        $output .= "<td>".$list['StoreSKU']."</td>";
        $output .= "<td>".$list['StockType']."</td>";
     $output .= "</tr>";   
   }
   $output .= "</table>";

   echo $output;  
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "lwsku_detail"){
   
   $lwsku = $_POST['lwsku']; 
   
   $stm = $db->prepare("select * from stm_sales WHERE LwSku = '$lwsku'");
   $stm->execute();
   $result = $stm->fetchAll();
   $output = "";
   $output .= "<table class='table table-striped table-bordered table-sm'>";
   $output .= "<tr>";
        $output .= "<td>LWSKU</td>";
        $output .= "<td>Sale Date</td>";
        $output .= "<td>Store SKU</td>";
        $output .= "<td>Qty</td>";
        $output .= "<td>Amount</td>";
        $output .= "<td>Channel</td>";
        $output .= "<td>Store</td>";

     $output .= "</tr>";
   foreach($result as $list){

     $channel = $DB_HELPER_CLASS->SingleDataWhere('stm_channels','id = "'.$list['MarketID'].'"');
     $store = $DB_HELPER_CLASS->SingleDataWhere('stm_stores','id = "'.$list['StoreID'].'"');
     

     $output .= "<tr>";
        $output .= "<td>".$list['LwSku']."</td>";
        $output .= "<td>".date('d M',strtotime($list['SaleDate']))."</td>";
        $output .= "<td>".$list['StoreSKU']."</td>";
        $output .= "<td>".$list['Qty']."</td>";
        $output .= "<td>".$list['Amount']."</td>";
        $output .= "<td>".$channel['channelName']."</td>";
        $output .= "<td>".$store['storeName']."</td>";
     $output .= "</tr>";   
   }
   $output .= "</table>";

   echo $output;  
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "discontinue"){
    $masterID = $_POST['masterID'];
    $up = $db->prepare("Update stm_itemmaster set ItemStatus = '0' where id = '$masterID'");
    $up->execute();
    echo $_POST['post_m'];
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "active-user"){
    $userID = $_POST['userID'];
    $up = $db->prepare("Update stm_users set isActive = '1' where id = '$userID'");
    $up->execute();
    echo $_POST['post_m'];
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "inactive-user"){
    $userID = $_POST['userID'];
    $up = $db->prepare("Update stm_users set isActive = '0' where id = '$userID'");
    $up->execute();
    echo $_POST['post_m'];
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "continue"){
    $masterID = $_POST['masterID'];
    
    $up = $db->prepare("Update stm_itemmaster set ItemStatus = '1' where id = '$masterID'");
    $up->execute();
    echo $_POST['post_m'];
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "continue_mul"){
    
    $masterID = $_POST['ids'];
    $ids = explode(',', $masterID);
    foreach($ids as $pid){
        $up = $db->prepare("Update stm_itemmaster set ItemStatus = '1' where id = '$pid'");
        $up->execute();
        
    }
    
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "discontinue_mul"){
    
    $masterID = $_POST['ids'];

    $ids = explode(',', $masterID);
    foreach($ids as $pid){
        
       $up = $db->prepare("Update stm_itemmaster set ItemStatus = '0' where id = '$pid'");
       $up->execute();
            
    }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "DNlist"){

    $masterdata = $DB_HELPER_CLASS->SingleDataWhere('stm_itemmaster','id = "'.$_POST['masterID'].'"');

    $amzOsDlist = "";
    $amzQcDlist = "";
    $ebyOsDlist = "";
    $ebyAoDlist = "";

    if($masterdata['ToListAmzOs'] == '0'){
        $amzOsDlist = "checked";
    }else{
        $amzOsDlist = "";
    }

    if($masterdata['ToListAmzQc'] == '0'){
        $amzQcDlist = "checked";
    }else{
        $amzQcDlist = "";
    }

    if($masterdata['ToListEbayOs'] == '0'){
        $ebyOsDlist = "checked";
    }else{
        $ebyOsDlist = "";
    }
    
    if($masterdata['ToListEbayAo'] == '0'){
        $ebyAoDlist = "checked";
    }else{
        $ebyAoDlist = "";
    }
    
    $amzOslist = "";
    $amzQclist = "";
    $ebyOslist = "";
    $ebyAolist = "";

    if($masterdata['ToListAmzOs'] == '1'){
        $amzOslist = "checked";
    }else{
        $amzOslist = "";
    }

    if($masterdata['ToListAmzQc'] == '1'){
        $amzQclist = "checked";
    }else{
        $amzQclist = "";
    }

    if($masterdata['ToListEbayOs'] == '1'){
        $ebyOslist = "checked";
    }else{
        $ebyOslist = "";
    }
    
    if($masterdata['ToListEbayAo'] == '1'){
        $ebyAolist = "checked";
    }else{
        $ebyAolist = "";
    }

    $amzOsNull = "";
    $amzQcNull = "";
    $ebyOsNull = "";
    $ebyAoNull = "";

    if(is_null($masterdata['ToListAmzOs'])){
        $amzOsNull = "checked";
    }else{
        $amzOsNull = "";
    }

    if(is_null($masterdata['ToListAmzQc'])){
        $amzQcNull = "checked";
    }else{
        $amzQcNull = "";
    }

    if(is_null($masterdata['ToListEbayOs'])){
        $ebyOsNull = "checked";
    }else{
        $ebyOsNull = "";
    }
    
    if(is_null($masterdata['ToListEbayAo'])){
        $ebyAoNull = "checked";
    }else{
        $ebyAoNull = "";
    }

    $amzOsListed = "";
    $amzQcListed = "";
    $ebayOsListed = "";
    $ebayAoListed = "";

    if($masterdata['AmzOsListed'] == 1){
        $amzOsListed = 'style="background-color:#b3e6cc;"';    
    }else{
        $amzOsListed = 'style="background-color:#ffcccc;"';
    }
    
    if($masterdata['AmzQcListed'] == 1){
        $amzQcListed = 'style="background-color:#b3e6cc;"';    
    }else{
        $amzQcListed = 'style="background-color:#ffcccc;"';
    }

    if($masterdata['EbayOsListed'] == 1){
        $ebayOsListed = 'style="background-color:#b3e6cc;"';   
    }else{
        $ebayOsListed = 'style="background-color:#ffcccc;"';
    }

    if($masterdata['EbayAoListed'] == 1){
        $ebayAoListed = 'style="background-color:#b3e6cc;"';   
    }else{
        $ebayAoListed = 'style="background-color:#ffcccc;"';
    }

   $output = '';
   $output .='<input type="hidden" class="DNlist" value="'.$_POST['masterID'].'">'; 
   $output .='<div class="form-group row" id="form-group">';
    $output .='<div class="col-md-6 offset-md-3">';
    $output .='<table class="table table-bordered">';
        $output .='<tr>';
          $output .='<th>Channel/Store</th>';  
          $output .='<th>Listed On</th>';
          $output .='<th>List On</th>';
          $output .="<th>Don't List On</th>";
          $output .="<th>Reset</th>";
        $output .='</tr>';
        $output .='<tr>';
          $output .='<td>Amz (OS)</td>';  
          $output .='<td '.$amzOsListed.'></td>';
          $output .='<td><input type="radio" name="AmzOs" value="1" '.$amzOslist.'></td>';
          $output .='<td><input type="radio" name="AmzOs" value="0" '.$amzOsDlist.'></td>';
          $output .='<td><input type="radio" name="AmzOs" value="null" '.$amzOsNull.'></td>';
        $output .='</tr>';
        $output .='<tr>';
          $output .='<td>Amz (QC)</td>';  
          $output .='<td '.$amzQcListed.'></td>';
          $output .='<td><input type="radio" name="AmzQc" value="1" '.$amzQclist.'></td>';
          $output .='<td><input type="radio" name="AmzQc" value="0" '.$amzQcDlist.'></td>';
          $output .='<td><input type="radio" name="AmzQc" value="null" '.$amzQcNull.'></td>';
        $output .='</tr>'; 
        $output .='<tr>';
          $output .='<td>Ebay (OS)</td>';  
          $output .='<td '.$ebayOsListed.'></td>';
          $output .='<td><input type="radio" name="EbayOs" value="1" '.$ebyOslist.'></td>';
          $output .='<td><input type="radio" name="EbayOs" value="0" '.$ebyOsDlist.'></td>';
          $output .='<td><input type="radio" name="EbayOs" value="null" '.$ebyOsNull.'></td>';
        $output .='</tr>';
        $output .='<tr>';
          $output .='<td>Ebay (AO)</td>';  
          $output .='<td '.$ebayAoListed.'></td>';
          $output .='<td><input type="radio" name="EbayAo" value="1" '.$ebyAolist.'></td>';
          $output .='<td><input type="radio" name="EbayAo" value="0" '.$ebyAoDlist.'></td>';
          $output .='<td><input type="radio" name="EbayAo" value="null" '.$ebyAoNull.'></td>';
        $output .='</tr>';    
    $output .='</table>';
    $output .='</div>';
   $output .='</div>';
  echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "channelsubmit"){
    
    $amzos_output = "";
    $amzqc_output = "";
    $ebayos_output = "";
    $ebayao_output = "";
    $amzos_bg = "";

    $masterID = $_POST['masterID'];

    $res = $DB_HELPER_CLASS->SingleDataWhere('stm_itemmaster','id = "'.$masterID.'"');
    
    if(isset($_POST['amzoschecked']) AND $_POST['amzoschecked'] == '0'){
        $up = $db->prepare("update stm_itemmaster set ToListAmzOs = '0' Where id = '".$masterID."'");
        $up->execute();
        if($res['AmzOsListed'] == 1){
          $amzos_output .= "<center><img src='././images/tickcross.png' height='25' width='25'></center>";    
        }else if($res['AmzOsListed'] == 0){
            $amzos_output .= "<center><img src='././images/x.png' height='25' width='25'></center>";
        }
        
    
    }else if(isset($_POST['amzoschecked']) AND $_POST['amzoschecked'] == '1'){

      $up = $db->prepare("update stm_itemmaster set ToListAmzOs = '1' Where id = '".$masterID."'");
      $up->execute();

      if(is_null($res['AmzOsListed']) OR  $res['AmzOsListed']== 0){
        $amzos_output .= "<center><img src='././images/tickmark.png' height='25' width='25'></center>";  
      }else if($res['AmzOsListed']== 1){
        $amzos_output .= "green";
      }
      
    }else if(isset($_POST['amzoschecked']) AND $_POST['amzoschecked']=='null'){
        $up = $db->prepare("update stm_itemmaster set ToListAmzOs = NULL Where id = '".$masterID."'");
        $up->execute();
        if($res['AmzOsListed'] == 1){
            $amzos_output .= "null"; 
        }else if($res['AmzOsListed'] == 0){
            $amzos_output .= "zero";
        }
    }

    if(isset($_POST['amzqcchecked']) AND $_POST['amzqcchecked'] == '0'){
        $up = $db->prepare("update stm_itemmaster set ToListAmzQc = '0' Where id = '".$masterID."'");
        $up->execute();
        if($res['AmzQcListed'] == 1){
            $amzqc_output .= "<center><img src='././images/tickcross.png' height='25' width='25'></center>";
        }else if($res['AmzQcListed'] == 0){
            $amzqc_output .= "<center><img src='././images/x.png' height='25' width='25'></center>";    
        }
        
    }else if (isset($_POST['amzqcchecked']) AND $_POST['amzqcchecked'] == '1'){
        $up = $db->prepare("update stm_itemmaster set ToListAmzQc = '1' Where id = '".$masterID."'");
        $up->execute();
        if($res['AmzQcListed'] == 0){
          $amzqc_output .= "<center><img src='././images/tickmark.png' height='25' width='25'></center>";  
        }else if($res['AmzOsListed']== 1){
            $amzqc_output .= "green";
        }
        
    }else if (isset($_POST['amzqcchecked']) AND $_POST['amzqcchecked'] == 'null'){
        $up = $db->prepare("update stm_itemmaster set ToListAmzQc = NULL Where id = '".$masterID."'");
        $up->execute();
        if($res['AmzQcListed'] == 1){
            $amzqc_output .= "null"; 
        }else if($res['AmzQcListed'] == 0){
            $amzqc_output .= "zero";
        }
    }

    if(isset($_POST['ebayoschecked']) AND $_POST['ebayoschecked'] == '0'){
        $up = $db->prepare("update stm_itemmaster set ToListEbayOs = '0' Where id = '".$masterID."'");
        $up->execute();

        if($res['EbayOsListed'] == 1){
            $ebayos_output .= "<center><img src='././images/tickcross.png' height='25' width='25'></center>";
        }else if($res['EbayOsListed'] == 0){
            $ebayos_output .= "<center><img src='././images/x.png' height='25' width='25'></center>";    
        }

        
    }else if (isset($_POST['ebayoschecked']) AND $_POST['ebayoschecked'] == '1'){
        $up = $db->prepare("update stm_itemmaster set ToListEbayOs = '1' Where id = '".$masterID."'");
        $up->execute();
        if($res['EbayOsListed'] == 0){
            $ebayos_output .= "<center><img src='././images/tickmark.png' height='25' width='25'></center>";
        }else if($res['EbayOsListed']== 0){
            $ebayos_output .= "green";
        }
        
    }else if (isset($_POST['ebayoschecked']) AND $_POST['ebayoschecked'] == 'null'){
        $up = $db->prepare("update stm_itemmaster set ToListEbayOs = NULL Where id = '".$masterID."'");
        $up->execute();
        if($res['EbayOsListed'] == 1){
            $ebayos_output .= "null"; 
        }else if($res['EbayOsListed'] == 0){
            $ebayos_output .= "zero";
        }
    }

    if(isset($_POST['ebayaochecked']) AND $_POST['ebayaochecked'] == '0'){
        $up = $db->prepare("update stm_itemmaster set ToListEbayAo = '0' Where id = '".$masterID."'");
        $up->execute();

        if($res['EbayAoListed'] == 1){
            $ebayao_output .= "<center><img src='././images/tickcross.png' height='25' width='25'></center>";
        }else if($res['EbayAoListed'] == 0){
            $ebayao_output .= "<center><img src='././images/x.png' height='25' width='25'></center>";    
        }

        
    }else if (isset($_POST['ebayaochecked']) AND$_POST['ebayaochecked'] == '1'){
        $up = $db->prepare("update stm_itemmaster set ToListEbayAo = '1' Where id = '".$masterID."'");
        $up->execute();
        if($res['EbayAoListed'] == 0){
            $ebayao_output .= "<center><img src='././images/tickmark.png' height='25' width='25'></center>";    
        }else if($res['EbayAoListed'] == 1){
            $ebayao_output .= "green";
        }
        
    }else if (isset($_POST['ebayaochecked']) AND $_POST['ebayaochecked'] == 'null'){
        $up = $db->prepare("update stm_itemmaster set ToListEbayAo = NULL Where id = '".$masterID."'");
        $up->execute();
        if($res['EbayAoListed'] == 1){
            $ebayao_output .= "null"; 
        }else if($res['EbayAoListed'] == 0){
            $ebayao_output .= "zero";
        }
    }
    
    $aray = array('amzos_output' => $amzos_output,'amzqc_output' => $amzqc_output,'ebayos_output' => $ebayos_output,'ebayao_output' => $ebayao_output);
    //print_r($aray);
    echo json_encode($aray);
  
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "listSubmit"){

    $masterids = $_POST['masterIDs'];

    $allIds = explode(',', $masterids);

    foreach($allIds as $pIds){
        
        $res = $DB_HELPER_CLASS->SingleDataWhere('stm_itemmaster','id = "'.$pIds.'"');

        if(isset($_POST['amzoschecked']) AND $_POST['amzoschecked'] == '0'){
                $up = $db->prepare("update stm_itemmaster set ToListAmzOs = '0' Where id = '".$pIds."'");
                $up->execute();
           
        
        }else if(isset($_POST['amzoschecked']) AND $_POST['amzoschecked'] == '1'){

          $up = $db->prepare("update stm_itemmaster set ToListAmzOs = '1' Where id = '".$pIds."'");
          $up->execute();

          
        }else if(isset($_POST['amzoschecked']) AND $_POST['amzoschecked']=='null'){
            
            $up = $db->prepare("update stm_itemmaster set ToListAmzOs = NULL Where id IN ('".$pIds."')");
            $up->execute();
            
        }

        if(isset($_POST['amzqcchecked']) AND $_POST['amzqcchecked'] == '0'){
            $up = $db->prepare("update stm_itemmaster set ToListAmzQc = '0' Where id = '".$pIds."'");
            $up->execute();
            
            
        }else if (isset($_POST['amzqcchecked']) AND $_POST['amzqcchecked'] == '1'){
            $up = $db->prepare("update stm_itemmaster set ToListAmzQc = '1' Where id = '".$pIds."'");
            $up->execute();
           
            
        }else if (isset($_POST['amzqcchecked']) AND $_POST['amzqcchecked'] == 'null'){
            $up = $db->prepare("update stm_itemmaster set ToListAmzQc = NULL Where id = '".$pIds."'");
            $up->execute();
           
        }

        if(isset($_POST['ebayoschecked']) AND $_POST['ebayoschecked'] == '0'){
            $up = $db->prepare("update stm_itemmaster set ToListEbayOs = '0' Where id = '".$pIds."'");
            $up->execute();

            
            
        }else if (isset($_POST['ebayoschecked']) AND $_POST['ebayoschecked'] == '1'){
            $up = $db->prepare("update stm_itemmaster set ToListEbayOs = '1' Where id = '".$pIds."'");
            $up->execute();
            
        }else if (isset($_POST['ebayoschecked']) AND $_POST['ebayoschecked'] == 'null'){
            $up = $db->prepare("update stm_itemmaster set ToListEbayOs = NULL Where id = '".$pIds."'");
            $up->execute();
            
        }

        if(isset($_POST['ebayaochecked']) AND $_POST['ebayaochecked'] == '0'){
            $up = $db->prepare("update stm_itemmaster set ToListEbayAo = '0' Where id = '".$pIds."'");
            $up->execute();

            
            
        }else if (isset($_POST['ebayaochecked']) AND$_POST['ebayaochecked'] == '1'){
            $up = $db->prepare("update stm_itemmaster set ToListEbayAo = '1' Where id = '".$pIds."'");
            $up->execute();
            
            
        }else if (isset($_POST['ebayaochecked']) AND $_POST['ebayaochecked'] == 'null'){
            $up = $db->prepare("update stm_itemmaster set ToListEbayAo = NULL Where id = '".$pIds."'");
            $up->execute();
           
        }

    }
}

?>