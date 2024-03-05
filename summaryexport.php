<?php ob_start();
session_start();
  header("Content-Type: application/xlsx");   
  header("Content-Disposition: attachment; filename=file.xls");  
  header("Pragma: no-cache"); 
  header("Expires: 0");
      
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
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

    $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');

    $stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');

    $stNew = $db_helper->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

    $stProg = $db_helper->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
    $stInactive = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
    $new = $stNew['id'];
    $progress = $stProg['id'];
    $done = $status['id']; 
    $approved = $stApprov['id'];

    $shmsData = $db_helper->SingleDataWhere('stm_users','userEmail = "shamsgulzar@gmail.com"');
    $annData = $db_helper->SingleDataWhere('stm_users','userEmail = "quratulain@swiftitsol.net"');
    $awaisData = $db_helper->SingleDataWhere('stm_users','userEmail = "awaisnadeembajwa05@gmail.com"');
    $output = "";
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $date = date('d-m-Y',strtotime($fromDate))." TO ".date('d-m-Y',strtotime($toDate));
  
  $output .= '<table border="1" id="directTable">
      <thead>
      <tr>
          <th colspan="7" style="color:#555; font-size:16px; font-weight: 700;">
              ASSIGNEES TASK SUMMARY FROM '.$date.'
          </th>
      </tr>
      <tr>
          <th>NAME</th>
          <th style="text-align:center;">TASKS CREATED</th>
          <th style="text-align:center;">OPENING (PENDING)</th>
          <th style="text-align:center;">ADDED NEW</th>
          <th style="text-align:center;">TOTAL TASKS</th>
          <th style="text-align:center;">COMPLETED</th>
          <th style="text-align:center;">PENDING</th>
      </tr>
      </thead>';
      $output .= '<tbody>';
          $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
          foreach ($users as $allusers) {    
          
          $dataCreatedBy = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$allusers['id']."' AND taskStatusID != '18'");
          $totalRecordsCreated = count($dataCreatedBy);

          $preComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate < '$fromDate' AND (taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskEndDate IS NULL) AND taskuserID = '".$allusers['id']."' AND isActive = '1' ");

          $totalPreviousCompleted = count($preComp);

          $newAssigned = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$allusers['id']."' AND isActive = '1' ");
          $totalnewAssigned = count($newAssigned);
         
          $total_task = intval($totalnewAssigned) + intval($totalPreviousCompleted);  
         
          $dataCompleted = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$allusers['id']."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
          $totalRecordsCompleted = count($dataCompleted); 
          
          $total_pending_task = intval($total_task) - intval($totalRecordsCompleted);
          
      $output .= '<tr>';    
      $output .= '<td>'.$allusers['userName'].'</td>';
      $output .= '<td align="center">';
      if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
      $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&createdby&assignees" target="_blank">';}
      if($totalRecordsCreated){
        $output .= $totalRecordsCreated;     
      }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&preuserpending&assignees" target="_blank">';}
              if($totalPreviousCompleted){
                $output .= $totalPreviousCompleted;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&addednewassignee&assignees" target="_blank">';}
              if($totalnewAssigned){
                $output .= $totalnewAssigned;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&totalassignee&assignees" target="_blank">';}
              if($total_task){
                $output .= $total_task;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&compthismon&assignees" target="_blank">';}
              if($totalRecordsCompleted){
                $output .= $totalRecordsCompleted;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&pendthismon&assignees" target="_blank">';}
              if($total_pending_task){
                $output .= $total_pending_task;     
              }
      $output .='</a></td>';

      $output .='</tr>';
      
      }
      
      $output .= '</tbody>';
  $output .='</table>';

  $output .= '<table border="1" id="directTable">
      <thead>
      <tr>
          <th colspan="7" style="color:#555; font-size:16px; font-weight: 700;">
              SUPERVISOR TASK REVIEW SUMMARY FROM '.$date.'
          </th>
      </tr>
      <tr>
          <th>NAME</th>
          <th style="text-align:center;">OPENING (Pending)</th>
          <th style="text-align:center;">ADDED NEW</th>
          <th style="text-align:center;">TOTAL FOR REVIEW</th>
          <th style="text-align:center;">REVIEWED</th>
          <th style="text-align:center;">UN-REVIEWED</th>
      </tr>
      </thead>';
      $output .= '<tbody>';
          $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                    foreach ($users as $allusers) {    
                   
            // $previousnotComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskStatusID = '$done' OR taskApprovedOn BETWEEN '$fromDate' AND '$toDate') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

             $previousnotComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskApprovedOn BETWEEN '$fromDate' AND '$toDate' OR taskStatusID = '$done') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

            $totalPrevComp = count($previousnotComp);

            $readyCurrent = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

            $totalreadyCurrent = count($readyCurrent);

            $totalReview = intval($totalPrevComp) + intval($totalreadyCurrent);

            $approvedOn = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskApprovedOn BETWEEN '$fromDate' AND '$toDate' AND taskStatusID = '$approved' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

            $totalapprovedOn = count($approvedOn);

            $unreviewed = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskApprovedOn IS NULL AND taskEndDate IS NOT NULL AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

            $totalUnreviewed = count($unreviewed);
          
      $output .= '<tr>';    
      $output .= '<td>'.$allusers['userName'].'</td>';
      $output .= '<td align="center">';
      if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
      $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&prerevpend&reviewers" target="_blank">';}
      if($totalPrevComp){
        $output .= $totalPrevComp;     
      }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&addednewrev&reviewers" target="_blank">';}
              if($totalreadyCurrent){
                $output .= $totalreadyCurrent;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&totalrev&reviewers" target="_blank">';}
              if($totalReview){
                $output .= $totalReview;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&comprev&reviewers" target="_blank">';}
              if($totalapprovedOn){
                $output .= $totalapprovedOn;     
              }
      $output .='</a></td>';
      $output .= '<td align="center">';
              if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
              $output .= '<a class="anchor" href="https://swiftitsol.net/stm/detailReport.php?fromDate='.$fromDate.'&toDate='.$toDate.'&uid='.$allusers['id'].'&unreview&reviewers" target="_blank">';}
              if($totalUnreviewed){
                $output .= $totalUnreviewed;     
              }
      $output .='</a></td>';
    
      $output .='</tr>';
      
      }
      
      $output .= '</tbody>';
      $output .='</table>';

  echo $output;
  ?>