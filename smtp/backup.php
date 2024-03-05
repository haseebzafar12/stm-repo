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


    $data = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '2022-11-01' AND '2022-11-30' AND taskStatusID = '21' AND taskApprovedOn IS NULL ");
    ?>
    <table class="table table-striped table-sm">
    <tr>
    	<td>taskID</td>
    	<td>taskStatusID</td>
    	<td>taskSupervisorID</td>
    	<td>taskEndDate</td>
    	<td>taskApprovedOn</td>
    </tr>
    <?php
    foreach ($data as $list) {
    ?>
    <tr>
    	<td><?php echo $list['taskID']; ?></td>
    	<td><?php echo $list['taskStatusID']; ?></td>
    	<td><?php echo $list['taskSupervisorID']; ?></td>
    	<td><?php echo $list['taskEndDate']; ?></td>
    	<td><?php echo date('m-d-Y',strtotime($list['taskEndDate'] . ' + 1 day')); ?></td>
    </tr>
    <?php	   	
    }
    ?>
    </table>
    <?php   
}

?>