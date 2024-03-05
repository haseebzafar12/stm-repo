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

      $fromDate = date('Y-m-01');
      $toDate = date('Y-m-d');

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
    
    $user = $db_helper->SingleDataWhere('stm_users','id = "'.$session_id.'"');
    
    if(isset($_GET['opening'])){
      $assignees = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate < '$fromDate' AND (taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskEndDate IS NULL) AND taskuserID = '".$session_id."' AND isActive = '1' ");

      $supervisor = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskApprovedOn BETWEEN '$fromDate' AND '$toDate' OR taskStatusID = '$done') AND taskSupervisorID = '".$session_id."' AND isActive = '1' ");
    }

    if(isset($_GET['assigned'])){
      $assignees = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$session_id."' AND isActive = '1' ");

      $supervisor = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskSupervisorID = '".$session_id."' AND isActive = '1' ");
    }

    if(isset($_GET['completedA'])){
      
      $assigneesCom = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$session_id."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");

    }

    if(isset($_GET['completedS'])){
      
      $superCom = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"(taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskApprovedOn BETWEEN '$fromDate' AND '$toDate') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$session_id."' AND isActive = '1' ");

    }

    if(isset($_GET['pending'])){
      
      $assignees = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID NOT IN ('$done','$approved') AND taskuserID = '".$session_id."' AND isActive = '1'");

      $supervisor = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID = '$done' AND taskSupervisorID = '".$session_id."' AND isActive = '1'");
    }
    
    if(isset($_GET['creator'])){
      $assignees = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$session_id."' AND taskStatusID != '".$stInactive['id']."'");
      
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
                      <?php 
                      if(isset($_GET['completedA'])){
                      ?>
                      <div class="statbox widget box box-shadow">
                          <div class="widget-header">
                            <h4 class="mt-2">
                              ASSIGNEES TASK REPORT FROM <?php echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate)); ?><br>
                                    ASSIGNEE: <?php echo $user['userName']; ?>
                            </h4>  
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th>TASK ID</th>
                                  <th>TITLE</th>
                                  <th>CREATED ON</th>
                                  <th>STATUS</th>
                                  <th>ENDED ON</th>
                                  <th>APPROVED ON</th>
                                  <th>SUPERVISOR</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                
                                  foreach($assigneesCom as $assigList){

                                    $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$assigList['subTaskID'].'"');
                                    $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$assigList['taskStatusID'].'"');
                                    $supervis = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskSupervisorID'].'"');
                                    $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskuserID'].'"');
                                    $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$assigList['taskchannelID'].'"');
                                    $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$assigList['taskstoreID'].'"');
                                ?>
                                  <tr>
                                    <td>
                                      <a href="stmtaskdetail.php?id=<?php echo $assigList['taskID'] ?>&view#assignees" target="_blank" class="anchor">
                                        <?php echo $assigList['taskID']; ?>

                                      </a>
                                    </td>
                                    <td><?php echo $subTask['subTask']; ?></td>
                                    <td>
                                      <?php 
                                        $creationDate = date('d-m-Y',strtotime($assigList['taskCreationDate']));
                                        if($assigList['taskCreationDate']){
                                          echo $creationDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $status['statusName']; ?></td>
                                    <td>
                                      <?php 
                                        $endDate = date('d-m-Y',strtotime($assigList['taskEndDate']));
                                        if($assigList['taskEndDate']){
                                          echo $endDate;
                                        }
                                      ?>
                                    </td>
                                    <td>
                                      <?php 
                                        $approveDate = date('d-m-Y',strtotime($assigList['taskApprovedOn']));
                                        if($assigList['taskApprovedOn']){
                                          echo $approveDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $supervis['displayName']; ?></td>
                                  </tr>
                                <?php    
                                  }
                                ?>
                                
                              </tbody>
                            </table>
                              
                          </div>
                        </div>
                      <?php
                      }else if(isset($_GET['completedS'])){
                      ?>
                      <div class="statbox widget box box-shadow">
                          <div class="widget-header">
                            <h4 class="mt-2">
                              SUPERVISOR TASK REPORT FROM <?php echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate)); ?><br>
                                    SUPERVISOR: <?php echo $user['userName']; ?>
                            </h4>  
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th>TASK ID</th>
                                  <th>TITLE</th>
                                  <th>CREATED ON</th>
                                  <th>ASSIGNEES</th>
                                  <th>STATUS</th>
                                  <th>ENDED ON</th>
                                  <th>APPROVED ON</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                
                                  foreach($superCom as $assigList){

                                    $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$assigList['subTaskID'].'"');
                                    $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$assigList['taskStatusID'].'"');
                                    $assignee = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskuserID'].'"');
                                    $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskuserID'].'"');
                                    $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$assigList['taskchannelID'].'"');
                                    $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$assigList['taskstoreID'].'"');
                                ?>
                                  <tr>
                                    <td>
                                      <a href="stmtaskdetail.php?id=<?php echo $assigList['taskID'] ?>&view#assignees" target="_blank" class="anchor">
                                      <?php echo $assigList['taskID']; ?>
                                      </a>
                                    </td>
                                    <td><?php echo $subTask['subTask']; ?></td>
                                    <td>
                                      <?php 
                                        $creationDate = date('d-m-Y',strtotime($assigList['taskCreationDate']));
                                        if($assigList['taskCreationDate']){
                                          echo $creationDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $supervis['displayName']; ?></td>
                                    <td><?php echo $status['statusName']; ?></td>
                                    <td>
                                      <?php 
                                        $endDate = date('d-m-Y',strtotime($assigList['taskEndDate']));
                                        if($assigList['taskEndDate']){
                                          echo $endDate;
                                        }
                                      ?>
                                    </td>
                                    <td>
                                      <?php 
                                        $approveDate = date('d-m-Y',strtotime($assigList['taskApprovedOn']));
                                        if($assigList['taskApprovedOn']){
                                          echo $approveDate;
                                        }
                                      ?>
                                    </td>
                                    
                                  </tr>
                                <?php    
                                  }
                                ?>
                                
                              </tbody>
                            </table>
                              
                          </div>
                        </div>
                      <?php
                      }else if(isset($_GET['creator'])){
                      ?>
                      <div class="statbox widget box box-shadow">
                          <div class="widget-header">
                            <h4 class="mt-2">
                              ASSIGNEE TASK REPORT FROM <?php echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate)); ?><br>
                                    ASSIGNEE: <?php echo $user['userName']; ?>
                            </h4>  
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th>TASK ID</th>
                                  <th>TITLE</th>
                                  <th>CREATED ON</th>
                                  <th>STATUS</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                
                                  foreach($superCom as $assigList){

                                    $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$assigList['subTaskID'].'"');
                                    $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$assigList['taskStatusID'].'"');
                                    $supervis = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskSupervisorID'].'"');
                                    $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskuserID'].'"');
                                    $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$assigList['taskchannelID'].'"');
                                    $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$assigList['taskstoreID'].'"');
                                ?>
                                  <tr>
                                    <td>
                                      <a href="stmtaskdetail.php?id=<?php echo $assigList['id'] ?>&view#assignees" target="_blank" class="anchor">
                                      <?php echo $assigList['id']; ?>
                                      </a>
                                      </td>
                                    <td><?php echo $subTask['taskName']; ?></td>
                                    <td>
                                      <?php 
                                        $creationDate = date('d-m-Y',strtotime($assigList['taskCreationDate']));
                                        if($assigList['taskCreationDate']){
                                          echo $creationDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $status['statusName']; ?></td>
                                  </tr>
                                <?php    
                                  }
                                ?>
                                
                              </tbody>
                            </table>
                              
                          </div>
                        </div>
                      <?PHP
                      }else{
                      ?>
                      <div class="statbox widget box box-shadow">
                          <div class="widget-header">
                            <h4 class="mt-2">
                              ASSIGNEES TASK REPORT FROM <?php echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate)); ?><br>
                                    ASSIGNEE: <?php echo $user['userName']; ?>
                            </h4>  
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th>TASK ID</th>
                                  <th>TITLE</th>
                                  <th>CREATED ON</th>
                                  <th>STATUS</th>
                                  <th>ENDED ON</th>
                                  <th>APPROVED ON</th>
                                  <th>SUPERVISOR</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                  
                                  foreach($assignees as $assigList){

                                    $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$assigList['subTaskID'].'"');
                                    $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$assigList['taskStatusID'].'"');
                                    $supervis = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskSupervisorID'].'"');
                                    $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$assigList['taskuserID'].'"');
                                    $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$assigList['taskchannelID'].'"');
                                    $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$assigList['taskstoreID'].'"');
                                ?>
                                  <tr>
                                    <td>
                                        <a href="stmtaskdetail.php?id=<?php echo $assigList['taskID'] ?>&view#assignees" target="_blank" class="anchor">
                                        <?php echo $assigList['taskID']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $subTask['subTask']; ?></td>
                                    <td>
                                      <?php 
                                        $creationDate = date('d-m-Y',strtotime($assigList['taskCreationDate']));
                                        if($assigList['taskCreationDate']){
                                          echo $creationDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $status['statusName']; ?></td>
                                    <td>
                                      <?php 
                                        $endDate = date('d-m-Y',strtotime($assigList['taskEndDate']));
                                        if($assigList['taskEndDate']){
                                          echo $endDate;
                                        }
                                      ?>
                                    </td>
                                    <td>
                                      <?php 
                                        $approveDate = date('d-m-Y',strtotime($assigList['taskApprovedOn']));
                                        if($assigList['taskApprovedOn']){
                                          echo $approveDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $supervis['displayName']; ?></td>
                                  </tr>
                                <?php    
                                  }
                                ?>
                                
                              </tbody>
                            </table>
                              
                          </div>
                        </div>
                        <br>
                        <div class="statbox widget box box-shadow">
                          <div class="widget-header">
                            <h4 class="mt-2">
                              SUPERVISOR TASK REPORT FROM <?php echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate)); ?><br>
                                    SUPERVISOR: <?php echo $user['userName']; ?>
                            </h4>  
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th>TASK ID</th>
                                  <th>TITLE</th>
                                  <th>CREATED ON</th>
                                  <th>ASSIGNEES</th>
                                  <th>STATUS</th>
                                  <th>ENDED ON</th>
                                  <th>APPROVED ON</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  foreach($supervisor as $supervisList){

                                    $subTask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$supervisList['subTaskID'].'"');
                                    $status = $db_helper->SingleDataWhere('stm_statuses','id = "'.$supervisList['taskStatusID'].'"');
                                    $assignee = $db_helper->SingleDataWhere('stm_users','id = "'.$supervisList['taskuserID'].'"');
                                    $assigneoftask = $db_helper->SingleDataWhere('stm_users','id = "'.$supervisList['taskuserID'].'"');
                                    $chanel = $db_helper->SingleDataWhere('stm_channels','id = "'.$supervisList['taskchannelID'].'"');
                                    $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$supervisList['taskstoreID'].'"');
                                ?>
                                  <tr>
                                    <td>
                                      <a href="stmtaskdetail.php?id=<?php echo $supervisList['taskID'] ?>&view#assignees" target="_blank" class="anchor">
                                      <?php echo $supervisList['taskID']; ?>
                                      </a>
                                    </td>
                                    <td><?php echo $subTask['subTask']; ?></td>
                                    <td>
                                      <?php 
                                        $creationDate = date('d-m-Y',strtotime($supervisList['taskCreationDate']));
                                        if($supervisList['taskCreationDate']){
                                          echo $creationDate;
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $assignee['displayName']; ?></td>
                                    <td><?php echo $status['statusName']; ?></td>
                                    <td>
                                      <?php 
                                        $endDate = date('d-m-Y',strtotime($supervisList['taskEndDate']));
                                        if($supervisList['taskEndDate']){
                                          echo $endDate;
                                        }
                                      ?>
                                    </td>
                                    <td>
                                      <?php 
                                        $approveDate = date('d-m-Y',strtotime($supervisList['taskApprovedOn']));
                                        if($supervisList['taskApprovedOn']){
                                          echo $approveDate;
                                        }
                                      ?>
                                    </td>
                                    
                                    
                                  </tr>
                                <?php    
                                  }
                                ?>
                                
                              </tbody>
                            </table>
                              
                          </div>
                        </div>
                      <?php 
                      }
                      ?>
                        
                    </div>
                </div>
            </div><!---layout-px-spacing-->

        <?php
          include_once "partials/footer.php";
        }else{  
          echo "<script>window.location='signin.php'</script>";
        }
        ?>