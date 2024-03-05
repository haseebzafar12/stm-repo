<?php 
      session_start();
      
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
      include('smtp/PHPMailerAutoload.php');
       // if(isset($_SESSION['admin']))
  // {
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db); 
      $db_helper = new db_helper($db);
?>

<body class="form">
    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <h1 class="">Password Recovery</h1>
                        <p class="signup-link">Enter your email and instructions will sent to you!</p>
                        <?php
                           if(isset($_POST['admin']))
                            {
                              $email = $_POST['email'];
                              $tb = "stm_users";
                              $whe = "userEmail = '$email'";
                              $userEmail = $db_helper->SingleDataWhere($tb, $whe);
                              
                                if($email == $userEmail['userEmail'])
                                {
                                    
                                    $id = $userEmail['id'];
                                    $pass = $objUser->generatePIN(5);
                                    $password = md5($pass);
                                    $objUser->update_password($password, $id);
                                        $msg = "<center><img src='https://swiftitsol.net/stm/images/stm.png'></center>";
      $msg .= "<br><h3>Welcome Back ".ucfirst($userEmail['userName']).",</h3>";
      $msg .= "<p>You recently requested to reset the password for your STM (SITS Task Management) account. If you did not request a password reset, please ignore this email. Please find below your new password.</p>";
      
        $msg .= "<p style='height:115px; text-align:center; padding-top:10%; font-size:22px; width:100%; background-color:#eee;'><b>".$pass."</b></p>";
        
        $msg .= "<p>Best regards</p>";
        $msg .= "<p>System Administrator</p>";
        $msg .= "<p>SITS Task Management (STM)</p>";
        $msg .= "<p>A product of Swift IT Solutions Pvt. Ltd.</p>";
        function smtp_mailer($to,$subject, $msg){
            $mail = new PHPMailer(); 
            //$mail->SMTPDebug=3;
            $mail->IsSMTP(); 
            $mail->SMTPAuth = true; 
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = "mail.swiftitsol.net";
            $mail->Port = "465"; 
            $mail->IsHTML(true);
            
            $mail->CharSet = 'UTF-8';
            $mail->Username = "stm@swiftitsol.net";
            $mail->Password = '*+on2&12#$$r';
            $mail->SetFrom("stm@swiftitsol.net");
            $mail->Subject = $subject;

            $mail->Body = $msg;
            $mail->AddAddress($to);
            $mail->SMTPOptions=array('ssl'=>array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
                'allow_self_signed'=>false
            ));
            if(!$mail->Send()){
                echo $mail->ErrorInfo;
            }else{
                ?>
                    <div class="alert alert-success">
                        <button class="close" data-dismiss="alert"></button>
                        Password has been sent to your email
                    </div>
                <?php
            }
        }

        smtp_mailer($email,'Forget Password',$msg);
                                    
            }else{
            ?> 
            <div class="alert alert-danger">
              <button class="close" data-dismiss="alert">&times;</button>
              <strong>Error!</strong> Email not found
            </div>
        <?php 
            }
        }
        ?>
                        <form method="post">
                            <div class="form">

                                <div id="email-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                    <input id="email" name="email" type="text" value="" placeholder="Email">
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" name="admin" value="">Reset</button>
                                        <a href="signin.php" class="btn btn-warning">Go Back to Log in</a>
                                    </div>                                    
                                </div>

                            </div>
                        </form>                       
                        <p class="terms-conditions">© 2022 All Rights Reserved.<br><a href="https://swiftitsol.net/stm/">SWIFT TASK MANAGEMENT.</a></p>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="form-image">
            <div class="l-image">
            </div>
        </div>
    </div>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/authentication/form-1.js"></script>

</body>
</html>