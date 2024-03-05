<?php
ob_start();
session_start();
      include_once ('../config.php');
      include_once ('../db_helper.php');
      include_once ('../user.php');

      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $DB_HELPER_CLASS = new db_helper($db);
      $objUser = new User($db);

     $session_id = "";
     if(isset($_SESSION['user'])){
      $session_id = $_SESSION['user'];
     }else if(isset($_SESSION['id'])){
      $session_id = $_SESSION['id'];
     }

     if(isset($_POST['post_m']) && $_POST['post_m'] == "stmtasktypes"){
        $output = "";
        $supplier = $_POST['supplier'];
 $listing_type = $_POST['listing_type'];
 $fromDate = $_POST['fromdate'];
 $toDate = $_POST['todate'];
 if($toDate < $fromDate){
    $output .= '<div class="alert alert-danger" id="success-alert" role="alert">
      To Date never be less then From Date
    </div>';
 }else if($supplier == ""){
   
    $output .= '<div class="alert alert-danger" id="success-alert" role="alert">
      Supplier is required
    </div>';
}else if($listing_type == ""){
    $output .= '<div class="alert alert-danger" id="success-alert" role="alert">
      Listing Type is required
    </div>';
 }else{
$output .= '<table>
<tr style="font-size: 16px;">
<td style="font-size: 16px;"><span style="font-weight: 600;">From Date:</span>'.date("d-m-Y", strtotime($_POST['fromdate'])).'</td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td>';
$output .= '<span style="font-weight: 600;">To Date:</span>'.date("d-m-Y", strtotime($_POST['todate']));
$output .= '</td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$supplierType = $db_helper->SingleDataWhere("stm_suppliers_type","id = ".$_POST['supplier_type']." ");
$output .= '<td><span style="font-weight: 600;">SupplierType:</span>'.$supplierType['supplierType'].'</td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$supplierName = $db_helper->SingleDataWhere('stm_supplier','id = "'.$_POST['supplier'].'"');
$output .= '<td><span style="font-weight: 600;">Supplier:</span>'.$supplierName['supplierName'].'</td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';
$output .= '<td></td>';

$output .= '<td><span style="font-weight: 600;">Status: </span>'.$_POST['listing_type'].'</td>';
$output .= '</tr>';
$output .= '</table>';
$statusName = $db_helper->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');

$main_supplier = $db_helper->SingleDataWhere('stm_suppliers_type','supplierType = "Main Supplier"');
$other_supplier = $db_helper->SingleDataWhere('stm_suppliers_type','supplierType = "Other Supplier"');

$post_supplier = $db_helper->SingleDataWhere('stm_supplier', 'id = "'.$_POST['supplier'].'"');

if($post_supplier['supplierTypeID'] == $main_supplier['id']){

 if($_POST['listing_type'] == "completed"){

 $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$supplier' AND taskStatusID = '".$statusName['id']."' ");
    
 if($tasks_report){
   $output .= '<table class="table table-bordered">
        <tr style="background-color:#eee;">
            <th>Task #</th>
            <th>Task Name</th>
            <th>Assigned On</th>
            <th>Amazon OS</th>
            <th>Ebay OS</th>
            <th>Ebay AM</th>
            <th>Website OS</th>
            <th>Website AM</th>
        </tr>';     
  foreach ($tasks_report as $tasks_data) {
       $output .= '<tr>';
            $output .= '<td>'.$tasks_data['id'].'</td>';
            $output .= '<td>'.$tasks_data['taskName'].'</td>';
            $output .= '<td>'.date("d-m-Y", strtotime($tasks_data['taskCreationDate'])).'</td>';
            $output .= '<td>'.
                $amz_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Amazon"');
                $data = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskChannelID = '".$amz_data['id']."' AND subTaskName = 'Channel Listing'");
                if($data){
                    $amz_task_user = $data['taskuserID'];
                    $assignee_name = $db_helper->SingleDataWhere('stm_users','id = "'.$data['taskuserID'].'" ');
                    $assignee_name['userName']; };
            $output .='</td>';
            $output .= '<td>';
                $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "onlinestreet-company"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                    $assignee_nam['userName'];       
                    };
            $output .='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Online"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                    
                        echo $assignee_nam['userName'];       
                    };
            $output .='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "OnlineStreet"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                      $assignee_nam['userName'];       
                    };
            $output .='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Candles"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                      $assignee_nam['userName'];       
                    };
            $output .= '</td>';
            $output .= '</tr>';
                }//loop
$output .='</table>';
}
}else if($_POST['listing_type'] == "pending"){
$tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$supplier' AND taskStatusID != '".$statusName['id']."' ");
 if($tasks_report){
   $output .= '<table class="table table-bordered">
        <tr style="background-color:#eee;">
            <th>Task #</th>
            <th>Task Name</th>
            <th>Assigned On</th>
            <th>30 Days+ OLD</th>
            <th>15 Days+ OLD</th>
        </tr>';
   foreach ($tasks_report as $tasks_data) {
        $output .= '<tr>';
            $output .= '<td>'.$tasks_data['id'].'</td>';
            $output .= '<td>'.$tasks_data['taskName'].'</td>';
            $output .= '<td>'.date("d-m-Y", strtotime($tasks_data['taskCreationDate'])).'</td>';
                $current_date = date('d-m-Y');
                $task_creation_date = date("d-m-Y", strtotime($tasks_data['taskCreationDate']));
                $diff = strtotime($current_date) - strtotime($task_creation_date);
                $daysCount = abs(round($diff / 86400));
            $output .= '<td>';
                if($daysCount >= '30'){ $daysCount;};
            $output .='</td>';
            $output .= '<td>';
                if($daysCount >= '15'){ $daysCount;};
            $output .='</td>';
        $output .= '</tr>';
   }//loop
       $output .= '</table>';
 }//if task report
}//else if
}else if($post_supplier['supplierTypeID'] == $other_supplier['id']){


if($_POST['listing_type'] == "completed"){
 

 $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$supplier' AND taskStatusID = '".$statusName['id']."' ");

 if($tasks_report){
   $output .= '<table class="table table-bordered">
        <tr style="background-color:#eee;">
            <th>Task #</th>
            <th>Task Name</th>
            <th>Assigned On</th>
            <th>Amz OS Link</th>
            <th>Ebay OS Link</th>
            <th>Ebay AM Link</th>
            <th>Website OS Link</th>
            <th>Website AM Link</th>
        </tr>';
   foreach ($tasks_report as $tasks_data) {
        $output .= '<tr>';
            $output .= '<td>'.$tasks_data['id'].'</td>';
            $output .= '<td>'.$tasks_data['taskName'].'</td>';
            $output .= '<td>'.date("d-m-Y", strtotime($tasks_data['taskCreationDate'])).'</td>';
            $output .= '<td>';
                $amz_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Amazon"');
                $data = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskChannelID = '".$amz_data['id']."' AND subTaskName = 'Channel Listing'");
                if($data){
                    $data['taskURL'];
                };
            $output.='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "onlinestreet-company"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      $data_user_store['taskURL'];      
                    };
            $output .='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Online"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                         $data_user_store['taskURL'];       
                    };
            $output .='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "OnlineStreet"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      echo $data_user_store['taskURL'];    
                    };
            $output .='</td>';
            $output .= '<td>';
            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Candles"');
                $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing' ");
                    if($data_user_store['taskstoreID']==$store_data['id']){
                      $data_user_store['taskURL'];       
                    };
            $output .='</td>';
        $output .= '</tr>';
   }//loop

$output .= '</table>';
}  
}else if($_POST['listing_type'] == "pending"){
    $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$supplier' AND taskStatusID != '".$statusName['id']."' ");
  
 if($tasks_report){
$output .= '<table class="table table-bordered">
        <tr style="background-color:#eee;">
            <th>Task #</th>
            <th>Task Name</th>
            <th>Assigned On</th>
            <th>Brand</th>
            <th>Assignee</th>
            <th>Comments</th>
        </tr>';
   foreach ($tasks_report as $tasks_data) {     
    $output .= '<tr>';
            $output .= '<td>'.$tasks_data['id'].'</td>';
            $output .= '<td>'.$tasks_data['taskName'].'</td>';
            $output .= '<td>'.date("d-m-Y", strtotime($tasks_data['taskCreationDate'])).'</td>';
            $output .= '<td>';
            $brands = $db_helper->SingleDataWhere('stm_brands','id = "'.$tasks_data['taskBrandID'].'"');
                $brands['brandName'];
            $output .='</td>';
            $output .= '<td>';
            $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-DONE"');
               $assignee_data = $db_helper->SingleDataWhere('stm_taskassigned','taskID = "'.$tasks_data['id'].'" AND taskStatusID !="'.$status['id'].'" AND subTaskName = "Channel Listing" ');
               $employe = $db_helper->SingleDataWhere('stm_users','id = "'.$assignee_data['taskuserID'].'"');
                $employe['userName'].'</td>';
            $output .= '<td>'.$tasks_data['taskComments'].'</td>';
        $output .= '</tr>';
   }//loop
       $output .= '</table>';
 }//if task report
}//else if listing type pending   

}//other supplier else

}//else

echo  $output;
}