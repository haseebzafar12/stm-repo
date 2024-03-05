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
    $subtask = $_POST['subtaskEmp'];
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
   
   $stm = $db->prepare("select * from stm_listing WHERE LwSku = '$lwsku' Order by StockType ASC");
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
?>