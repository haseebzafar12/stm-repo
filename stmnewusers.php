<?php ob_start();
session_start();

include('smtp/PHPMailerAutoload.php');

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
                          <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <form method="post">
                                        <fieldset>
                                            <div class="alert alert-error" style='display:none;'>
                                                <button class="close" data-dismiss="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>
                                            <div class="alert alert-success" style='display:none;'>
                                                <button class="close" data-dismiss="alert"></button>
                                                Your form validation is successful!
                                            </div>
                                            <div class="form-group">
                                                <label>Full Name<span style="color:red;">*</span></label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Display Name<span style="color:red;">*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="displayname" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label>Email<span style="color:red;">*</span></label>
                                                <div class="controls">
                                                    <input name="email" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                  <label>Type</label>
                                                  <div class="form-group">
                                                    <select class="form-control" name="usertype" required>
                                                    <?php
                                                      $tbl = "stm_usertypes";
                                                      $userTypes = $db_helper->allRecords($tbl);
                                                      foreach($userTypes as $list){

                                                    ?>
                                                    <option value="<?php echo $list['id']; ?>">
                                                        <?php echo $list['usertypeName']; ?>
                                                    </option>
                                                    <?php    
                                                      }
                                                    ?>
                                                    
                                                    </select>
                                                  </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label>Department<span style="color:red;">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-control" name="department">
                                                        <option>Select </option>
                                                        <?php
                                                          $tbl = "stm_departments";
                                                          $userTypes = $db_helper->allRecords($tbl);
                                                          foreach($userTypes as $list){
                                                        ?>
                                                        <option value="<?php echo $list['id']; ?>">
                                                            <?php echo $list['departmentName']; ?>
                                                        </option>
                                                        <?php    
                                                          }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                
                                                <input type="submit" class="btn btn-primary" value="Submit" name="addUser">
                                                <a href="stmusers.php" class="btn">Go Back</a>
                                                
                                            </div>
                                        </fieldset>
                                    </form>
                                    <?php
                                if(isset($_POST['addUser'])){

                                $usertypesPost = $_POST['usertype'];
                                $password_for_user = '123';  
                                $password = md5($password_for_user);
                                
                                date_default_timezone_set("Asia/Karachi");
                                $creationDate = date('Y-m-d H:i:s', time()); 
                                $statusID = "1";

                                $table = "stm_users";
                                $wher = "userEmail = '".$_POST['email']."'";
                                $dataEmail = $db_helper->SingleDataWhere($table, $wher);
                                if($_POST['displayname'] == ""){
                                ?>
                                    <div class="alert alert-warning">
                                        <button class="close" data-dismiss="alert"></button>
                                        Displayname is required
                                    </div>  
                                <?php   
                                }else if($dataEmail){
                                ?>
                                    <div class="alert alert-warning">
                                        <button class="close" data-dismiss="alert"></button>
                                        Email already exist
                                    </div>
                                <?php
                                }else{
                                    $query = $objUser->stm_adduser($usertypesPost,
                                    $_POST['department'],
                                    $_POST['name'],
                                    $_POST['displayname'],
                                    $_POST['email'],
                                    $password,
                                    $creationDate,
                                    $session_id);
                                    if($query){
                                          $mail = new PHPMailer(); 
                                          //$mail->SMTPDebug=3;
                                          $mail->IsSMTP(); 
                                          $mail->SMTPAuth = true; 
                                          $mail->SMTPSecure = 'ssl'; 
                                          $mail->Host = "mail.swiftitsol.net";
                                          $mail->Port = "465"; 
                                          $mail->IsHTML(true);
                                          $mail->Subject = 'STM - User Created';

                                          $mail->CharSet = 'UTF-8';
                                          $mail->Username = "stm@swiftitsol.net";
                                          $mail->Password = '*+on2&12#$$r';
                                          $mail->SetFrom("stm@swiftitsol.net");
                                          $mail->AddAddress($_POST['email']); 
                                          
      $msg = "<center><img src='https://swiftitsol.net/stm/images/stm.png'></center>";
      $msg .= "<br><h3>Dear ".ucfirst($_POST['name']).",</h3>";
      $msg .= "<p>Your account has been created on our STM (SITS TASK MANANGEMENT) System.Below are your system generated credentials. Please change the password immediately after login.</p>";
      $msg .= "<a href='https://swiftitsol.net/stm/signin.php' target='_blank' style='padding:10px; background-color:#3973ac; color:#fff; text-decoration:none;'>LOGIN TO YOUR ACCOUNT</a>";
        $msg .= "<ul>";
        $msg .= "<li>Email : ".$_POST['email']."</li>";
        $msg .= "<li>Password : ".$password_for_user."</li>";
        $msg .= "</ul>";
        $msg .= "<p>Best regards</p>";
        $msg .= "<p>System Administrator</p>";
        $msg .= "<p>SITS Task Management (STM)</p>";
        $msg .= "<p>A product of Swift IT Solutions Pvt. Ltd.</p>";

        
        $mail->Body = $msg;
                                          
      $mail->SMTPOptions=array('ssl'=>array(
          'verify_peer'=>false,
          'verify_peer_name'=>false,
          'allow_self_signed'=>false
      ));
      if(!$mail->Send()){
          echo $mail->ErrorInfo;
      }else{
        echo '<div class="alert alert-success">
                <button class="close" data-dismiss="alert"></button>
                User Created Successfully
            </div>';
      }
}
   
}
}
?>    
                                </div>
                                
                                
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