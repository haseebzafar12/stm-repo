<?php
ob_start();
session_start();

include('../../smtp/PHPMailerAutoload.php');     
include_once ('../config.php');
include_once ('../db_helper.php');
include_once ('../user.php');

$dbcon = new Database();
$db = $dbcon->getConnection();
$db_helper = new db_helper($db);
$objUser = new User($db);


$session_id = "";
if(isset($_SESSION['user'])){
$session_id = $_SESSION['user'];
}else if(isset($_SESSION['id'])){
$session_id = $_SESSION['id'];
}

if(!empty($_FILES)){

    $fileName = $_FILES['file_name']['name'];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(uniqid());
    $fileDest = '../../upload/'.$newFileName.'.'.$ext;
    $justFileName = $newFileName.'.'.$ext;
    if(move_uploaded_file($_FILES['file_name']['tmp_name'], $fileDest)){
        $ary = array("fileName" => $justFileName, "ext" => $ext);
        echo json_encode($ary);
    }
    
}

?>