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

    $stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');

    $stNew = $db_helper->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

    $stProg = $db_helper->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
    $stInactive = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
    $new = $stNew['id'];
    $progress = $stProg['id'];
    $done = $status['id']; 
    $approved = $stApprov['id'];

    // $totalRecordsPending = "";
                   
    // $allinactie = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskStatusID = "'.$stInactive['id'].'"');
    // foreach ($allinactie as $allinactieList) {
       
    //    $task_id = $allinactieList['id'];
    //       $dataPending = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskCreationDate NOT BETWEEN "$fromDate" AND "$toDate" AND taskuserID = "29" AND taskStatusID IN("'.$new.'","'.$progress.'") AND isActive ="1" ');
    //         foreach ($dataPending as $key => $value) {
    //            if($value['taskStatusID'] != $stInactive['id']){
    //                 $totalRecordsPending = count($dataPending);     
    //            }
    //         }  
       
    // }
    
    // echo $totalRecordsPending;
    // exit();
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
                             <div class="col-md-12">
                               <form method="post">
                               <div class="row">
                                <div class="col-md-2">
                                  <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From Date ...">
                                </div>
                                <div class="col-md-2">
                                    <input id="toFlatpickr" name="todate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To Date ...">
                                </div>
                                <div class="col-md-2">
                                  <select class="form-control" name="createdBy">
                                      <option value="0">Created By</option>
                                      <?php 
                                      $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                                      foreach ($users as $allusers) { 
                                      ?>
                                      <option value="<?php echo $allusers['id'] ?>">
                                          <?php echo $allusers['displayName']; ?>
                                      </option>
                                      <?php 
                                      }
                                      ?>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <select class="form-control" name="supervisor">
                                      <option value="0">Select Supervisor</option>
                                      <?php 
                                      $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                                      foreach ($users as $allusers) { 
                                      ?>
                                      <option value="<?php echo $allusers['id'] ?>">
                                          <?php echo $allusers['displayName']; ?>
                                      </option>
                                      <?php 
                                      }
                                      ?>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <select class="form-control" name="assignees">
                                      <option value="0">Select Assignee</option>
                                      <?php 
                                      $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                                      foreach ($users as $allusers) { 
                                      ?>
                                      <option value="<?php echo $allusers['id'] ?>">
                                          <?php echo $allusers['displayName']; ?>
                                      </option>
                                      <?php 
                                      }
                                      ?>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <select class="form-control" name="status">
                                      <option value="0">Select Status</option>
                                      <?php 
                                      $status = $db_helper->allRecordsOrderBy("stm_statuses","id ASC");
                                      foreach ($status as $statusesList) { 
                                      ?>
                                      <option value="<?php echo $statusesList['id'] ?>">
                                          <?php echo $statusesList['statusName']; ?>
                                      </option>
                                      <?php 
                                      }
                                      ?>
                                  </select>
                                </div>
                               </div>
                               <div class="row mt-2">
                                    <div class="col-md-2">
                                      <select class="form-control" name="category">
                                          <option value="0">Select Category</option>
                                          <?php 
                                          $categories = $db_helper->allRecordsOrderBy("stm_tasktypes","id ASC");
                                          foreach ($categories as $allcategories) { 
                                          ?>
                                          <option value="<?php echo $allcategories['id'] ?>">
                                              <?php echo $allcategories['tasktypeName']; ?>
                                          </option>
                                          <?php 
                                          }
                                          ?>
                                      </select>
                                    </div>
                                    <div class="col-md-2">
                                      <select class="form-control" name="subTask">
                                          <option value="0">Select SubTask</option>
                                          <?php 
                                          $subtask = $db_helper->allRecordsOrderBy("stm_subtask","subTask ASC");
                                          foreach ($subtask as $subtasks) { 
                                          ?>
                                          <option value="<?php echo $subtasks['id'] ?>">
                                              <?php echo $subtasks['subTask']; ?>
                                          </option>
                                          <?php 
                                          }
                                          ?>
                                      </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" name="filter" value="Filter" class="btn btn-primary">
                                    </div>
                               </div>
                             </div>
                             </form>
                            </div>    
                          </div>
                          <div class="widget-content widget-content-area">
                            <table class="table table-striped table-sm table-bordered">
                            <tr>
                                <th>TaskID</th>
                                <th>Created On</th>
                                <th>Ended On</th>
                                <th>Approved On</th>
                            </tr>    
                            <?php 
                            if(isset($_POST['filter'])){

                               if(isset($_POST['fromdate']) AND !empty($_POST['fromdate'])){
                                
                                if(isset($_POST['createdBy']) AND !empty($_POST['createdBy'])){
                                  $filter_set = " t1.taskCreationDate BETWEEN '".$_POST['fromdate']."' AND '".$_POST['todate']."' AND t1.taskAssignedBy = '".$_POST['createdBy']."' ";  
                                }

                                if(isset($_POST['supervisor']) AND isset($_POST['status'])){
                                  $filter_set = " t2.taskEndDate BETWEEN '".$_POST['fromdate']."' AND '".$_POST['todate']."' AND t2.taskSupervisorID = '".$_POST['supervisor']." AND t2.taskStatusID = '".$_POST['status']."' ";  
                                }


                                $data = $db->prepare("SELECT * FROM stm_tasks t1 LEFT JOIN stm_taskassigned t2 on t1.id = t2.taskID WHERE ".$filter_set." ");
                                
                                $data->execute();
                                $total_data = $data->fetchAll();

                                foreach($total_data as $list){
                                ?>
                                <tr>
                                  <td><?php echo $list['taskID']; ?></td>
                                  <td><?php echo $list['taskCreationDate']; ?></td>
                                  <td><?php echo $list['taskEndDate']; ?></td>
                                  <td><?php echo $list['taskApprovedOn']; ?></td>
                                </tr>
                                <?php   
                                }
                                
                               } 

                            }
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