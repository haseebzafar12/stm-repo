<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
  if(isset($_SESSION['id']))
  {
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db);
      $db_helper = new db_helper($db);

      $session_id = $_SESSION['id'];
      $tb = "stm_users";
      $wh = "id = '$session_id'";
      $session_data = $db_helper->SingleDataWhere($tb, $wh);

      $uid = $_GET['utid'];

      $tb1 = "stm_users";
      $wh1 = "id = '".$_GET['uid']."'";
      $recData = $db_helper->SingleDataWhere($tb1, $wh1);
      
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
                              <h4>Edit User</h4>
                          </div>  
                          <div class="widget-content widget-content-area">
                            <div class="col-md-8 offset-md-2">
                              <form method="post" class="form-horizontal">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" value="<?php echo $recData['userName'] ?>" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Display Name</label>
                                    
                                    <input type="text" value="<?php echo $recData['displayName'] ?>" class="form-control" name="displayname">
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="usertype">
                                        <?php
                                          $tbl = "stm_usertypes";
                                          $userTypes = $db_helper->allRecords($tbl);

                                          foreach($userTypes as $list){

                                        ?>
                                        <option value="<?php echo $list['id']; ?>"
                                            <?php 
                                            if($uid == $list['id']){
                                                echo "selected='selected'";
                                            }
                                        ?>>
                                            <?php echo $list['usertypeName']; ?>
                                        </option>
                                        <?php    
                                          }
                                        ?>
                                        
                                        </select>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="updateType" class="btn btn-primary" class="btn btn-primary" value="Save Changes">
                                    <a href="stmusers.php" class="btn btn-danger">Go Back</a>
                                </div>
                              </form>
                              <?php
                                if(isset($_POST['updateType'])){
                                    $usertypeID = $_POST['usertype'];
                                    $data = $objUser->user_edit($_POST['displayname'],$usertypeID,$_GET['uid']);
                                    if($data){
                                ?>
                                    <div class="alert alert-success">
                                      <button class="close" data-dismiss="alert">&times;</button>
                                      <strong>Success!</strong> User Type Updated
                                    </div>
                                <?php
                                    echo "<script>alert('User Updated');
                                    window.location='stmusers.php'</script>";
                                    }else{
                                ?>
                                    <div class="alert alert-warning">
                                      <button class="close" data-dismiss="alert">&times;</button>
                                      <strong>Error!</strong> Something went wrong
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
          echo "<script>alert('You don't have a persmission to edit this page')</script>";  
          echo "<script>window.location='signin.php'</script>";
      }
        ?>