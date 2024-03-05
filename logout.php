<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');

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

	session_destroy();
	// $objUser->isLOGOUT($session_id);
	echo "<script>window.location='signin.php'</script>";
?>