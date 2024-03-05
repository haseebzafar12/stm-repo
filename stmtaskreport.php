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
                            <div class="row mt-2">
                             <div class="col-md-3">
                               <span><h4>All Tasks Report</h4></span>   
                             </div>
                             <div class="col-md-9">
                               <form method="post">
                               <div class="row">
                                <div class="col-md-4">
                                  <input type="date" name="fromdate" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" name="todate" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="submit" name="report" class="btn btn-info" value="Get Report">
                                </div>
                               </div>
                             </div>
                             </form>
                            </div>    
                          </div>
                          <div class="widget-content widget-content-area">
                            <?php
                            if(isset($_POST['report'])){
                             $fromDate = $_POST['fromdate'];
                             $toDate = $_POST['todate'];
                             if($toDate < $fromDate){
                            ?>
                                <div class="alert alert-danger" id="success-alert" role="alert">
                                  To Date never be less then From Date
                                </div>
                            <?php
                             }else{

                                $delSTID = $db_helper->SingleDataWhere('stm_statuses','statusName = "0-Deleted"');

                                $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskStatusID != '".$delSTID['id']."'");
                                if($tasks_report){
                                ?>
                            <table>
                            <tr>
                            <td style="font-size: 16px;">From Date: <?php echo date("d-m-Y", strtotime($_POST['fromdate'])); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>AND</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-size: 16px;">To Date: <?php echo date("d-m-Y", strtotime($_POST['todate'])); ?></td>

                            </tr>
                            </table>
                            <table class="table">
                                <tr style="background-color: #c2d1f0;">
                                    <td style="width:25%">SubTask</td>
                                    <td style="width:15%">Channel</td>
                                    <td style="width:15%">Store</td>
                                    <td style="width:15%">Assignee</td>
                                    <td style="width:15%">Start Date</td>
                                    <td style="width:15%">End Date</td>
                                </tr>
                            </table>
                            <?php 
                                foreach($tasks_report as $tasks_data){
                            ?>
                            <br><br>
                            <table class="table table-bordered">
                            <tr style="background-color: #d6d6c2;">
                                <th>Task #</th>
                                <td><?php echo $tasks_data['id'] ?></td>
                                <th>Task Name</th>
                                <td><?php echo $tasks_data['taskName'] ?></td>
                                <th>Created On</th>
                                <td><?php echo date("d-m-Y", strtotime($tasks_data['taskCreationDate'])); ?></td>
                                <th>Created By</th>
                                <?php $createdBy = $db_helper->SingleDataWhere('stm_users', 'id = "'.$tasks_data['taskAssignedBy'].'"'); ?>
                                <td><?php echo $createdBy['userName'] ?></td>
                                <th>Reviewed By</th>
                                <?php $suprvisr = $db_helper->SingleDataWhere('stm_users', 'id = "'.$tasks_data['taskSupervisorID'].'"'); ?>
                                <td><?php echo $suprvisr['userName'] ?></td>
                            </tr>
                            </table>
                            <?php 
                            $assignees = $db_helper->allRecordsRepeatedWhere('stm_taskassigned', 'taskID = "'.$tasks_data['id'].'"');
                            foreach($assignees as $assignees_data){
                                $subtask = $db_helper->SingleDataWhere('stm_subtask','id = "'.$assignees_data['subTaskID'].'"');
                            ?>
                            <table class="table table-sm table-hover">
                                <tbody>
                                <tr>
                                <td style="width:25%"><?php echo $subtask['subTask']; ?></td>

                                <?php $channel = $db_helper->SingleDataWhere('stm_channels','id = "'.$assignees_data['taskchannelID'].'"'); ?>
                                <td style="width:15%"><?php echo $channel['channelName']; ?></td>

                                 <?php $store = $db_helper->SingleDataWhere('stm_stores','id = "'.$assignees_data['taskstoreID'].'"'); ?>
                                <td style="width:15%"><?php echo $store['storeName']; ?></td>

                                <?php $assignee = $db_helper->SingleDataWhere('stm_users','id = "'.$assignees_data['taskuserID'].'"'); ?>
                                <td style="width:15%"><?php echo $assignee['userName']; ?></td>

                                <td style="width:15%">
                                    <?php
                                    if($assignees_data['taskStartDate'] == ""){
                                        echo "";
                                    }else{
                                        echo date("d-m-Y", strtotime($assignees_data['taskStartDate']));    
                                    } 
                                    
                                ?></td>

                                <td style="width:15%"><?php
                                    if($assignees_data['taskEndDate'] == ""){
                                        echo "";
                                    }else{
                                        echo date("d-m-Y", strtotime($assignees_data['taskEndDate']));    
                                    } ?></td>    
                                </tr>
                                </tbody>
                            </table>
                            <?php
                                }//2nd loop
                              }//1st loop

                                }else{
                                ?>
                                 <div class="alert alert-danger" id="success-alert" role="alert">
                                  No Records Found
                                 </div>
                                 <?php
                                }
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