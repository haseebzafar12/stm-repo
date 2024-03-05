<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
  if(isset($_SESSION['id'])  OR isset($_SESSION['user']))
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
        <div class="navbar navbar-fixed-top">
           <?php
              include_once "partials/navbar.php";
            ?>
        </div>
        <div class="container-fluid">
            
            <div class="row-fluid">
                <!--/span-->
                <div class="span12" id="content">
                           
                    <div class="row-fluid">
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Change Password</div>
                            </div>
                            <div class="block-content">
                                <?php
                                  if(isset($_POST['saveChanges'])){
                                    $oldPass = md5($_POST['oldPass']);
                                    $newPass = md5($_POST['newPass']);
                                    $confirmNewPass = md5($_POST['ConfirmnewPass']);
                                    $table = "stm_users";
                                    $where = "UserPassword = '$oldPass' and id = '$session_id'";
                                    $data_user = $db_helper->SingleDataWhere($table,$where);

                                    $db_user_password = $data_user['userPassword'];
                                    //if(strlen($newPass) < 8){
                                      
                                    if($db_user_password == $oldPass){
                                      if($newPass == $confirmNewPass){
                                        if($newPass == $db_user_password){
                                        ?>
                                            <div class="alert alert-warning">
                                              <button class="close" data-dismiss="alert">&times;</button>
                                              <strong>Error!</strong> New password are equal to old password, Please change it.
                                            </div> 
                                        <?php
                                        }else{   
                                          if( $objUser->update_user_change_password($newPass, $session_id)){
                                            ?>
                                                <div class="alert alert-success">
                                                  <button class="close" data-dismiss="alert">&times;</button>
                                                  <strong>Success!</strong> Password changed successfully
                                                </div> 
                                                <a href="index.php" class="btn btn-success btn-sm">Go back</a>         
                                            <?php
                                          }
                                        } 
                                      }else{
                                        ?>
                                          <div class="alert alert-danger">
                                            <button class="close" data-dismiss="alert">&times;</button>
                                            <strong>Error!</strong> New password and Confirm password does not match
                                          </div>
                                      <?php  
                                      }

                                    }else{
                                      ?>
                                          <div class="alert alert-danger">
                                            <button class="close" data-dismiss="alert">&times;</button>
                                            <strong>Error!</strong> Old password is not matched
                                          </div>
                                      <?php   
                                    }
                                    
                                  }
                                  
                                 ?>
                               <form method="post" class="form-horizontal" style="margin-left: 20%; !important; margin-right: 10%;">
                                <div class="control-group">
                                      <label class="control-label">Old Password</label>
                                      <div class="controls">
                                        <input type="password" class="form-control" name="oldPass" required="required">
                                      </div>
                                </div>
                                <div class="control-group">
                                      <label class="control-label">New Password</label>
                                      <div class="controls">
                                      <input type="password" class="form-control" name="newPass" required="required">
                                      </div>
                                </div>
                                <div class="control-group">
                                      <label class="control-label">Confirm Password</label>
                                      <div class="controls">
                                      <input type="password" class="form-control" name="ConfirmnewPass" required="required">
                                      </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="submit" class="btn btn-primary" value="Save Changes" name="saveChanges">
                                        <a href="stmusers.php" class="btn">Cancel</a>
                                    </div>
                                </div>
                                   
                               </form>
                            </div>
                        </div>
                        <!-- /block -->
                    </div>
                    
                </div><!---content--->
            </div>
           
            <hr>
            <footer>
                <p>&copy; Swift Task Managment 2022</p>
            </footer>
        </div>
        <!--/.fluid-container-->
        <?php 
          include_once ('partials/footer.php');
      }else{
          echo "<script>alert('Please Login')</script>";  
          echo "<script>window.location='signin.php'</script>";
      }
        ?>