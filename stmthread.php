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
        date_default_timezone_set('ASIA/Karachi');
        $ToSeenDate = date('Y-m-d H:i:s');
        
        $pidData = $db_helper->SingleDataWhere('stm_message_details','id = "'.$_GET['pid'].'"');
        if(isset($_GET['from'])){
          if($pidData['msgFrom'] == $session_id){
            $objUser->updateUserMessage($_GET['pid'],"1");  
          }
        }else if(isset($_GET['to'])){
          if($pidData['msgTo'] == $session_id){
            $objUser->updateToSeenDate($_GET['pid'],$ToSeenDate);
          }
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
                      <div class="widget-content widget-content-area">
                        <div class="row">
                          <div class="col-md-12">
                            <input type="hidden" class="userID" value="<?php echo $session_id; ?>">
                            <input type="hidden" class="pID" value="<?php echo $_GET['pid'] ?>">
                            <input type="hidden" class="thread" value="<?php echo $_GET['thread'] ?>">
                            
                            <div class="form-group">
                              
                              <label>Type your message</label>
                              <textarea class="form-control msg" rows="8"></textarea>  
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-10">
                            <button type="button" class="btn btn-success replyPost" style="float:left;">POST</button>
                          </div>
                        </div>
                        <br>
                        <div class="message-body">
                      
                        <?php 
                          $message_detail = $db_helper->allRecordsRepeatedWhere("stm_message_details","messageID = '".$_GET['thread']." ORDER By id DESC'");
                          foreach($message_detail as $message_details){

                          $createdBy = $db_helper->SingleDataWhere("stm_users","id = '".$message_details['msgFrom']."'");

                          $assignedTo = $db_helper->SingleDataWhere("stm_users","id = '".$message_details['msgTo']."'");
                        ?>
                        <div class="row">
                          <div class="col-md-10">
                            <div class="row">
                              <div class="col-md-12">
                                <p><b><?php echo date('d/m/Y H:i:s', strtotime($message_details['createdOn']))."&nbsp&nbsp"?>
                                  From: <?php echo $createdBy['userName']."<br>"; ?>
                                  To: <?php echo $assignedTo['userName']; ?></b>
                                </p>            
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-10">
                                  <?php 
                                    echo $message_details['message'];
                                  ?>
                              </div>
                            </div>
                            <hr>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                      
                      
                    </div>
                        
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