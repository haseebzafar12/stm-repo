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


    $data = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID = '21' AND taskEndDate > '2022-11-30' AND taskApprovedOn IS NULL");
    ?>
    <table class="table table-striped table-sm">
    <tr>
    	<td>taskID</td>
    	<td>taskStatusID</td>
    </tr>
    <?php
    foreach ($data as $list) {
      $objUser->backup_approvedOn($list['id'], $list['taskEndDate']);
    ?>
    <tr>
    	<td><?php echo $list['id']; ?></td>
    	<td><?php echo $list['taskStatusID']; ?></td>
    </tr>
    <?php	   	
    }
    ?>
    </table>
    <?php   
}

?>