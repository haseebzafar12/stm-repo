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
                            <div class="row mt-3">
                             <div class="col-md-3">
                               <span><h4>Task Summary</h4></span>   
                             </div>
                             <div class="col-md-9">
                               <form method="post">
                               <div class="row">
                                <div class="col-md-3">
                                  <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From Date ...">
                                </div>
                                <div class="col-md-3">
                                    <input id="toFlatpickr" name="todate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To Date ...">
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" name="report" class="btn btn-success" value="Get Data">
                                    <?php 
                                    if(isset($_POST['report']))
                                    {
                                     $fromDate = date('Y-m-d',strtotime($_POST['fromdate']));
                                     $toDate = date('Y-m-d',strtotime($_POST['todate']));
                                    ?>
                                    
                                    <a target="_blank" class="btn btn-primary" href="summaryexport.php?fromDate=<?php echo $fromDate ?>&toDate=<?php echo $toDate; ?>">Export</a>
                                    <button type="button" class="btn btn-warning print" onclick='printDiv();'>Print</button>
                                    <?php 
                                    }
                                    ?>
                                </div>
                                
                               </div>
                             </div>
                             </form>
                            </div>    
                          </div>
                          <div class="widget-content widget-content-area" id="contentTable">
                             
                             <?php
                                if(isset($_POST['report'])){

                                 $fromDate = date('Y-m-d',strtotime($_POST['fromdate']));
                                 $toDate = date('Y-m-d',strtotime($_POST['todate']));
                                 if($toDate < $fromDate){
                                ?>
                                    <div class="alert alert-danger" id="success-alert" role="alert">
                                      To Date never be less then From Date
                                    </div>
                                <?php
                                 }else{
                                ?>
<div class="row">    
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm" id="directTable">
                <thead>
                <tr>
                    <th colspan="7" style="color:#555; font-size:16px; font-weight: 700;">
                        ASSIGNEES TASK SUMMARY FROM <?php
                            echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate));
                        ?>
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
                </thead>
                <tbody>
                <?php 
                    $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                    foreach ($users as $allusers) {    
                    
                    $dataCreatedBy = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$allusers['id']."' AND taskStatusID != '18'");
                    $totalRecordsCreated = count($dataCreatedBy);

                    $preComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate < '$fromDate' AND (taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskStatusID NOT IN ('".$done."','".$approved."')) AND taskuserID = '".$allusers['id']."' AND isActive = '1' ");

                    $totalPreviousCompleted = count($preComp);

                    $newAssigned = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$allusers['id']."' AND isActive = '1' ");
                    $totalnewAssigned = count($newAssigned);
                   
                    $total_task = intval($totalnewAssigned) + intval($totalPreviousCompleted);  
                   
                    $dataCompleted = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$allusers['id']."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
                    $totalRecordsCompleted = count($dataCompleted); 
                    
                    $total_pending_task = intval($total_task) - intval($totalRecordsCompleted);
                    
                ?>
                
                <tr>    
                    <td><?php echo $allusers['userName']; ?></td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&createdby&assignees" target="_blank">   
                        <?php 
                        }
                        if($totalRecordsCreated){
                        echo $totalRecordsCreated;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&preuserpending&assignees" target="_blank">
                        <?php 
                        }
                        if($totalPreviousCompleted){
                        echo $totalPreviousCompleted;     
                        }
                        ?>
                        </a>
                    </td>
                    
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&addednewassignee&assignees" target="_blank">
                        <?php
                        } 
                        if($totalnewAssigned){
                        echo $totalnewAssigned;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&totalassignee&assignees" target="_blank">
                        <?php 
                        }
                        if($total_task){
                        echo $total_task;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&compthismon&assignees" target="_blank">
                        <?php
                        } 
                        if($totalRecordsCompleted){
                        echo $totalRecordsCompleted;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&pendthismon&assignees" target="_blank">
                        <?php
                        } 
                        if($total_pending_task){
                         echo $total_pending_task;     
                        }
                        ?>
                        </a>
                    </td>
                    
                </tr>
                <?php 
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>
<div class="row">    
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm" id="directTable1">
                <thead>
                <tr>
                    <th colspan="6" style="color: #555; font-size:16px; font-weight: 700;">
                        SUPERVISOR TASK REVIEW SUMMARY FROM <?php
                            echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate));
                        ?>
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
                </thead>
                <tbody>
                <?php 
                    $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                    foreach ($users as $allusers) {    
                   
                    // $previousnotComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskStatusID = '$done' OR taskApprovedOn BETWEEN '$fromDate' AND '$toDate') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

                     $previousnotComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskApprovedOn BETWEEN '$fromDate' AND '$toDate' OR taskStatusID = '$done') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

                    $totalPrevComp = count($previousnotComp);

                    $readyCurrent = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

                    $totalreadyCurrent = count($readyCurrent);

                    $totalReview = intval($totalPrevComp) + intval($totalreadyCurrent);

                    $approvedOn = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"(taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskApprovedOn BETWEEN '$fromDate' AND '$toDate') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

                    $totalapprovedOn = count($approvedOn);

                    $unreviewed = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskApprovedOn IS NULL AND taskStatusID = '$done' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

                    $totalUnreviewed = count($unreviewed);

                    // $totalUnreviewed = intval($totalReview) - intval($totalapprovedOn);
                ?>
                <tr>    
                    <td><?php echo $allusers['userName']; ?></td>
                     <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&prerevpend&reviewers" target="_blank">
                        <?php
                        } 
                        if($totalPrevComp){
                            echo $totalPrevComp;     
                        }
                        ?>
                        </a>
                    </td>
                    
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&addednewrev&reviewers" target="_blank">
                        <?php
                        } 
                        if($totalreadyCurrent){
                            echo $totalreadyCurrent;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                       <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&totalrev&reviewers" target="_blank">
                        <?php
                        } 
                        if($totalReview){
                            echo $totalReview;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&comprev&reviewers" target="_blank">
                        <?php
                        } 
                        if($totalapprovedOn){
                            echo $totalapprovedOn;     
                        }
                        ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php 
                        if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                        ?>
                        <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&unreview&reviewers" target="_blank">
                        <?php
                        } 
                        if($totalUnreviewed){
                            echo $totalUnreviewed;
                        }
                        ?>
                        </a>
                    </td>
                </tr>
                <?php 
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>                      

                                    </div>

                                     
                                <?php
                                 }//else close

                                }

                                ?>
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