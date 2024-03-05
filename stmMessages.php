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
      }
      if(isset($_SESSION['id'])){
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
                    <ul class="nav nav-tabs mb-3 mt-3" id="simple-tab" role="tablist">
                        
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-inbox-tab" data-toggle="pill" href="#pills-inbox" role="tab" aria-controls="pills-home" aria-selected="true">INBOX</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-sent-tab" data-toggle="pill" href="#pills-sent" role="tab" aria-controls="pills-contact" aria-selected="false">SENT</a>
                        </li>
                        <li class="nav-item mb-2" style="margin-left: 85%;">
                            <button data-target="#messageModal" data-toggle="modal" class="btn btn-success">Create Message</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="simpletabContent">
                        <div class="tab-pane fade show active" id="pills-inbox" role="tabpanel" aria-labelledby="pills-inbox-tab">
                            
                            <table class="table table-striped table-sm">
                                <tr>
                                    <th style="width:16%;">DATE</th>
                                    <th>USER</th>
                                    <th>FROM</th>
                                    <th style="width:7%;">Task #</th>
                                    <th style="width:47%;">MESSAGE</th>
                                    <th>STATUS</th>
                                    <th>DETAIL</th>
                                </tr>
                                <?php 
                                $message = $db_helper->allRecordsRepeatedWhere("stm_message_details","msgTo = '$session_id' ORDER BY IsSeen ASC, id DESC");
                                foreach($message as $allmessages){
                                   $username = $db_helper->SingleDataWhere("stm_users","id = '".$allmessages['msgFrom']."'"); 
                                ?>
                                <tr>
                                    <td><?php echo date('d M Y H:i:s', strtotime($allmessages['createdOn'])); ?></td>
                                    <td><img src="images/<?php echo $username['userDP']; ?>" class="image-size"></td>
                                    <td><?php echo $username['userName']; ?></td>
                                    <td>
                                        <?php
                                         $dataParent = $db_helper->SingleDataWhere('stm_messages','id = "'.$allmessages['messageID'].'"');
                                         if($dataParent['taskID'] != '0'){
                                            echo $dataParent['taskID'];
                                         }else{
                                            echo "";
                                         }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                      $string = strip_tags($allmessages['message']);
                                      if (strlen($string) > 30) {
                                          // truncate string
                                          $stringCut = substr($string, 0, 30);
                                          $endPoint = strrpos($stringCut, ' ');

                                          $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                          $string .= '...';
                                      }
                                      $rejectionMessage = "";
                                      $assginee_task = $db_helper->SingleDataWhere('stm_taskassigned','rejectionMessageID = "'.$allmessages['id'].'"');
                                      
                                     
                                      if($allmessages['isRejection'] == "1"){
                                        $rejectionMessage = '&nbsp<a style="color:red;" href="stmtaskdetail.php?id='.$assginee_task['taskID'].'&message='.$allmessages['id'].'&to='.$allmessages['msgTo'].'#messages" target="_blank">(Rejected Task #'.$assginee_task['taskID'].')</a>';
                                      }
                                      echo $string.$rejectionMessage;
                                    ?>
                                    </td>
                                    <td>
                                         <?php 
                                        if($allmessages['ToSeenDate'] == ""){
                                          echo '<span class="badge outline-badge-danger shadow-none">Unseen</span>';  
                                        }else{
                                        
                                           $date = date('d-m-Y H:i:s',strtotime($allmessages['ToSeenDate']));
                                           echo '<span class="badge outline-badge-info shadow-none">Seen ('.$date.')</span>'; 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                    $messageTaskID = $db_helper->SingleDataWhere('stm_messages','id = "'.$allmessages['messageID'].'"');
                                    if($messageTaskID['taskID'] != 0){
                                    ?>
                                    <a href="stmtaskdetail.php?id=<?php echo $messageTaskID['taskID'] ?>&message=<?php echo $allmessages['id']; ?>&to=<?php echo $allmessages['msgTo'] ?>#messages" target="_blank">
                                      <svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                      </svg>
                                    </a>
                                    
                                    <?php     
                                    }else{
                                    ?>
                                    <a target='_blank' href="stmthread.php?pid=<?php echo $allmessages['id'] ?>&thread=<?php echo $allmessages['messageID'] ?>&to=<?php echo $allmessages['msgTo'] ?>">
                                      <svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                      </svg>
                                    </a>
                                    
                                    <?php
                                    }
                                    ?>
                                    </td>
                                </tr>
                                <?php 
                                }
                                ?>
                            </table>
                            
                        </div>
                        
                        <div class="tab-pane fade" id="pills-sent" role="tabpanel" aria-labelledby="pills-sent-tab">
                             <table class="table table-striped table-sm">
                                <tr>
                                    <th style="width:17%;">DATE</th>
                                    <th>USER</th>
                                    <th>TO</th>
                                    <th style="width:7%;">Task #</th>
                                    <th style="width:47%;">MESSAGE</th>
                                    <th>STATUS</th>
                                    <th>DETAIL</th>
                                </tr>
                                <?php 
                                $message = $db_helper->allRecordsRepeatedWhere("stm_message_details","msgFrom = '$session_id' ORDER BY id DESC");
                                foreach($message as $allmessages){
                                   $username = $db_helper->SingleDataWhere("stm_users","id = '".$allmessages['msgTo']."'"); 
                                ?>
                                <tr>
                                    <td><?php echo date('d M Y H:i:s', strtotime($allmessages['createdOn'])); ?></td>
                                     <td><img src="images/<?php echo $username['userDP']; ?>" class="image-size"></td>
                                    <td><?php echo $username['userName']; ?></td>
                                    <td>
                                        <?php
                                         $dataParent = $db_helper->SingleDataWhere('stm_messages','id = "'.$allmessages['messageID'].'"');
                                         if($dataParent['taskID'] != '0'){
                                            echo $dataParent['taskID'];
                                         }else{
                                            echo "";
                                         }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                      $string = strip_tags($allmessages['message']);
                                      if (strlen($string) > 30) {
                                          // truncate string
                                          $stringCut = substr($string, 0, 30);
                                          $endPoint = strrpos($stringCut, ' ');

                                          $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                          $string .= '...';
                                      }
                                       $rejectionMessage = "";
                                          $assginee_task = $db_helper->SingleDataWhere('stm_taskassigned','rejectionMessageID = "'.$allmessages['id'].'"');
                                          if($allmessages['isRejection'] == "1"){
                                            $rejectionMessage = '&nbsp<a style="color:red;" href="stmtaskdetail.php?id='.$assginee_task['taskID'].'&message='.$allmessages['id'].'&to='.$allmessages['msgTo'].'#messages" target="_blank">(Rejected Task #'.$assginee_task['taskID'].')</a>';
                                          }
                                          echo $string.$rejectionMessage;
                                    ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if($allmessages['ToSeenDate'] == ""){
                                          echo '<span class="badge outline-badge-danger shadow-none">Unseen</span>';  
                                        }else{
                                        
                                           $date = date('d-m-Y H:i:s',strtotime($allmessages['ToSeenDate']));
                                           echo '<span class="badge outline-badge-info shadow-none">Seen ('.$date.')</span>'; 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php 
                                    $messageTaskID = $db_helper->SingleDataWhere('stm_messages','id = "'.$allmessages['messageID'].'"');
                                    if($messageTaskID['taskID'] != 0){
                                    ?>
                                    <a href="stmtaskdetail.php?id=<?php echo $messageTaskID['taskID'] ?>&message=<?php echo $allmessages['id']; ?>&to=<?php echo $allmessages['msgTo'] ?>#messages" target="_blank">
                                          <svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                          </svg>
                                    </a>
                                    
                                    <?php     
                                    }else{
                                    ?>
                                    <a href="stmthread.php?pid=<?php echo $allmessages['id'] ?>&thread=<?php echo $allmessages['messageID'] ?>&to=<?php echo $allmessages['msgTo'] ?>" target='_blank'>
                                          <svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                          </svg>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                    </td>
                                </tr>
                                <?php 
                                }
                                ?>
                            </table>
                        </div>
                    </div>     
                    </div>
                </div>
            </div><!---layout-px-spacing-->
            <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
             <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add new message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="userID" value="<?php echo $session_id; ?>">
                        <div class="form-group">
                            <label>Task (Optional)</label>
                            <select class="form-control taskID" style="width:100%;">
                              <option value="0">Select Task</option>  
                              <?php 
                              $tasks = $db_helper->allRecordsOrderBy("stm_tasks","id DESC");
                              foreach ($tasks as $tasks_row) {
                                 $taskName = $tasks_row['taskName'];
                                 $string = strip_tags($taskName);
                                  if (strlen($string) > 25) {
                                      // truncate string
                                      $stringCut = substr($string, 0, 25);
                                      $endPoint = strrpos($stringCut, ' ');

                                      $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                      
                                  }
                              ?>
                              <option value="<?php echo $tasks_row['id'] ?>">
                                <?php echo $tasks_row['id']." - ".$string; ?>
                              </option>
                              <?php 
                              }
                              ?>
                              </select>
                        </div>
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control assignedTo" style="width:100%;">
                              <option value="">Select User</option>  
                              <?php 
                              $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
                              foreach ($users as $users_row) {
                              ?>
                              <option value="<?php echo $users_row['id'] ?>">
                                <?php echo $users_row['userName'] ?>
                              </option>
                              <?php 
                              }
                              ?>
                              </select>
                        </div>
                        <div class="form-group">
                            <label>Type your message</label>
                            <textarea class="form-control message" rows="10"></textarea>
                            <div class="error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-success directMsg">POST</button>
                      <a data-dismiss="modal" class="btn" href="#">Cancel</a>
                    </div>
               </div> 
             </div>
           </div>
        <?php
          include_once "partials/footer.php";
        }else{  
          echo "<script>window.location='signin.php'</script>";
        }
        ?>