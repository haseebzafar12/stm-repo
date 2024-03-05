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
                              <h4>Change Password</h4>
                          </div>  
                          <div class="widget-content widget-content-area">
                            <div class="col-md-8">
                                <form method="post">
                                    <div class="form-group">
                                          
                                      <label>Old Password</label>
                                      <input type="password" class="form-control" name="oldPass" required="required">
                                          
                                    </div>
                                    <div class="form-group">
                                          
                                      <label>New Password</label>
                                      <input type="password" class="form-control" name="newPass" required="required">
                                          
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="ConfirmnewPass" required="required">
                                    </div>
                                    <div class="form-group">    
                                        <input type="submit" class="btn btn-primary" value="Save Changes" name="saveChanges">
                                        <a href="index.php" class="btn btn-success btn-sm">Go back</a>
                                    </div>
                                       
                                </form>
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