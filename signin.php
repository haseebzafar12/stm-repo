<?php ob_start();
session_start();
include_once('common/config.php');
include_once('common/user.php');
include_once('common/db_helper.php');
// if(isset($_SESSION['admin']))
// {
$dbcon = new Database();
$db = $dbcon->getConnection();
$objUser = new user($db);
$db_helper = new db_helper($db);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SWIFT TASK MANAGEMENT - Login Page</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
</head>

<body class="form">


    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <h1 class="">Sign In</h1>
                        <p class="">Log in to your account to continue.</p>
                        <?php
                        if (isset($_POST['admin'])) {
                            $email = $_POST['email'];
                            $password = md5($_POST['password']);
                            $useradmin = $objUser->userSession($email, $password);
                            if ($email == $useradmin['userEmail'] && $password == $useradmin['userPassword']) {
                                if ($useradmin['isActive'] != 1) {
                        ?>
                                    <div class="alert alert-danger">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong>Error!</strong> You are in-active, Please contact to administrator to activate your account
                                    </div>
                                <?php
                                } else {
                                    $userTypeID = $useradmin['usertypeID'];
                                    $tb = "stm_usertypes";
                                    $whe = "id = '$userTypeID'";
                                    $UserDataType = $db_helper->SingleDataWhere($tb, $whe);
                                    if ($UserDataType['usertypeName'] == 'Admin' or $UserDataType['usertypeName'] == 'Manager') {

                                        $_SESSION['id'] = $useradmin['id'];
                                        $_SESSION["login_time_stamp"] = time();
                                        $objUser->stm_login_details($useradmin['id']);
                                        $_SESSION['login_details_id'] = $db_helper->lastID();
                                        echo "<script>window.location='stmtasks.php?opentask'</script>";
                                    } else {

                                        //$_SESSION['id'] = $useradmin['id'];
                                        $_SESSION['user'] = $useradmin['id'];
                                        $_SESSION["login_time_stamp"] = time();
                                        $objUser->stm_login_details($useradmin['id']);
                                        $_SESSION['login_details_id'] = $db_helper->lastID();
                                        echo "<script>window.location='stmtasks.php?opentask'</script>";
                                    }
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger">
                                    <button class="close" data-dismiss="alert">&times;</button>
                                    <strong>Error!</strong> Invalid Login
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <form class="text-left" method="post">
                            <div class="form">

                                <div id="username-field" class="field-wrapper input">
                                <svg style="top: 20px; !important" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="email" name="email" type="text" class="form-control" style="padding: 0px 0 10px 35px !important;" placeholder="Email" required>
                                </div>

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <svg style="top: 20px; !important" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" style="padding: 0px 0 10px 35px !important;" required>
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper toggle-pass">
                                        <p class="d-inline-block">Show Password</p>
                                        <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="field-wrapper">
                                        <button name="admin" type="submit" class="btn btn-primary" value="">Log In</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                        <div class="forget-ps" style="margin:20px 0px 20px 0px;">
                            <a style="color:cornflowerblue; text-decoration:underline;" href="forget.php" class="forgot-pass-link">Forgot Password?</a>
                        </div>

                        <p style="margin-bottom: 0px;" class="terms-conditions">© 2022 All Rights Reserved.<br><a href="https://swiftitsol.net/stm/">SWIFT TASK MANAGEMENT.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/authentication/form-2.js"></script>

</body>

</html>