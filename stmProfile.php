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
                              <h4>Profile Setting</h4>
                          </div>  
                          <div class="widget-content widget-content-area">
                            <div class="col-md-8 offset-md-2">
                              <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Name</label>
                                        <input name="userName" type="text" class="form-control" value="<?php echo $session_data['userName']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label>Display Name</label>
                                    
                                    <input name="displayname" type="text" class="form-control" value="<?php echo $session_data['displayName']; ?>" />
                                    
                                </div>
                                <div class="form-group">
                                    
                                    <label>Email</label>
                                    
                                    <input name="userEmail" type="text" class="form-control" value="<?php echo $session_data['userEmail']; ?>"/>
                                    
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input name="userPhone" type="text" class="form-control" value="<?php echo $session_data['userPhone']; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>WhatsApp</label>
                                    <input name="userWhatsApp" type="text" class="form-control" value="<?php echo $session_data['userWhatsapp']; ?>"/>
                                    
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    
                                    <input name="userCity" type="text" class="form-control" value="<?php echo $session_data['userCity']; ?>"/>
                                    
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    
                                    <textarea name="userAddress" class="form-control"><?php echo $session_data['userAddress']; ?></textarea>
                                    
                                </div>
                                <div class="form-group">
                                    <div class="custom-file-container" data-upload-id="myFirstImage">
                                        <label>Upload (Single File) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image"><img src="images/<?php echo $session_data['userDP']; ?>" width="50" height="50"></a></label>
                                        <label class="custom-file-container__custom-file" >
                                            <input type="file" class="custom-file-container__custom-file__custom-file-input" name="file_name" accept="image/*">
                                            <input type="hidden" name="old_file" value="<?php echo $session_data['userDP']; ?>" />
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>

                                        <div class="custom-file-container__image-preview">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    
                                    <input type="submit" class="btn btn-primary" value="Save Changes" name="saveChanges">
                                    <a href="stmusers.php" class="btn">Cancel</a>
                                    
                                </div>
                              </form>
                              <?php
                                if(isset($_POST['saveChanges'])){
                                    $finalFileName = "";
                                    if(!empty($_FILES['file_name']['name']))
                                       {
                                        $fileName = $_FILES['file_name']['name'];
                                        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                        $newFileName = md5(uniqid());
                                        $fileDest = 'images/'.$newFileName.'.'.$ext;
                                        $justFileName = $newFileName.'.'.$ext;

                                        if ($_FILES["file_name"]["size"] > 5000000) {
                                          echo "Sorry, your file is too large. Please upload less then 5MB";
                                        }else{
                                          
                                            if($_POST['old_file']){
                                              unlink('images/'.$_POST['old_file']);  
                                            }
                                            
                                            move_uploaded_file($_FILES['file_name']['tmp_name'], $fileDest);
                                            $finalFileName = $justFileName;
                                        }         
                                       }else{
                                        $finalFileName .= $_POST['old_file'];
                                       }
                                    
                                    $query = $objUser->user_profile_update($session_id,$_POST['userName'],$_POST['displayname'],$finalFileName,$_POST['userEmail'],$_POST['userPhone'],$_POST['userWhatsApp'],$_POST['userCity'],$_POST['userAddress']);
                                    if($query){
                                ?>
                                    <div class="alert alert-success">
                                      <button class="close" data-dismiss="alert">&times;</button>
                                      <strong>Success!</strong> Profile Updated
                                    </div>
                                <?php
                                    echo "<script>window.location='stmProfile.php'</script>";
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