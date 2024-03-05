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
                            <div class="row mt-2">
                             <div class="col-md-12">
                               <form method="post">
                               <div class="row">
                                <div class="col-md-2">
                                  <select name="supplier_type" class="form-control form-control-sm supplier_type">
                                    <option>Supplier Type</option>
                                    <?php
                                    $suppliers = $db_helper->allRecords('stm_suppliers_type');
                                    foreach($suppliers as $supplier_data){
                                    ?>
                                    <option value="<?php echo $supplier_data['id'] ?>">
                                        <?php echo $supplier_data['supplierType'] ?>
                                    </option> 
                                    <?php   
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="col-md-2" id="supplier">
                                  <select name="supplier" class="supplier form-control form-control-sm">
                                    <option>Select Supplier</option>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <select name="listing_type" class="form-control form-control-sm">
                                    <option>Select Status</option>
                                    <option value="pending">Pending Tasks</option>
                                    <option value="completed">Completed Tasks</option>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                    <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From Date ...">
                                  
                                </div>
                                <div class="col-md-2">
                                  <input id="toFlatpickr" name="todate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To Date ...">
                                </div>
                                <div class="col-md-2">
                                  <input type="submit" name="report" class="btn btn-info" value="Get Report">
                                </div>
                               </div>
                             </div>
                             </form>
                            </div>    
                          </div>
                          <div class="widget-content widget-content-area">
                            <?php
                            if(isset($_POST['report'])){
                             $supplier = $_POST['supplier'];
                             $listing_type = $_POST['listing_type'];
                             $fromDate = $_POST['fromdate'];
                             $toDate = $_POST['todate'];
                             if($toDate < $fromDate){
                            ?>
                                <div class="alert alert-danger" id="success-alert" role="alert">
                                  To Date never be less then From Date
                                </div>
                            <?php
                             }else if($supplier == ""){
                                ?>
                                <div class="alert alert-danger" id="success-alert" role="alert">
                                  Supplier is required
                                </div>
                            <?php
                             }else if($listing_type == ""){
                                ?>
                                <div class="alert alert-danger" id="success-alert" role="alert">
                                  Listing Type is required
                                </div>
                            <?php
                             }else{
                             
                            $statusName = $db_helper->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');

                            $main_supplier = $db_helper->SingleDataWhere('stm_suppliers_type','supplierType = "Main Supplier"');
                            $other_supplier = $db_helper->SingleDataWhere('stm_suppliers_type','supplierType = "Other Supplier"');

                            $post_supplier = $db_helper->SingleDataWhere('stm_supplier', 'id = "'.$_POST['supplier'].'"');

                            $supplierName = $db_helper->SingleDataWhere('stm_supplier','id = "'.$_POST['supplier'].'"');

                            if($post_supplier['supplierTypeID'] == $main_supplier['id']){

                                if($_POST['listing_type'] == "completed"){

                             $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$supplier' AND taskStatusID = '".$statusName['id']."' ");
                                
                             if($tasks_report){
                            ?>
                               <table class="table table-bordered">
                                    <tr style="background-color: #ffcccc;">
                                        <td colspan="8" style="text-align:center;">
                                            <h4>Completed Listing for <?php echo  $supplierName['supplierName']; ?> - <?php echo date('d-m-Y',strtotime($_POST['fromdate'])) ?> TO <?php echo date('d-m-Y',strtotime($_POST['todate'])) ?></h4></td>
                                    </tr>
                                    <tr style="background-color:#eee;">
                                        <th>Task #</th>
                                        <th>Task Name</th>
                                        <th>Assigned On</th>
                                        <th>Amazon OS</th>
                                        <th>Ebay OS</th>
                                        <th>Ebay AM</th>
                                        <th>Website OS</th>
                                        <th>Website AM</th>
                                    </tr>     
                            <?php
                               foreach ($tasks_report as $tasks_data) {
                            ?>
                                    
                                    <tr>
                                        <td><a href="stmtaskdetail.php?id=<?php echo $tasks_data['id']; ?>"><?php echo $tasks_data['id'] ?></a></td>
                                        <td><?php echo $tasks_data['taskName'] ?></td>
                                        <td>
                                        <?php echo date("d-m-Y", strtotime($tasks_data['taskCreationDate'])); ?>  
                                        </td>
                                
                                        <td>
                                            <?php 
                                            $amz_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Amazon"');
                                            $data = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskChannelID = '".$amz_data['id']."' AND subTaskName = 'Channel Listing'");
                                            
                                            if($data){
                                                $amz_task_user = $data['taskuserID'];
                                                $assignee_name = $db_helper->SingleDataWhere('stm_users','id = "'.$data['taskuserID'].'" ');
                                                
                                                echo $assignee_name['userName'];
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "onlinestreet-company"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                                                
                                                    echo $assignee_nam['userName'];       
                                                }
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Online"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                                                
                                                    echo $assignee_nam['userName'];       
                                                }
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "OnlineStreet"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                                                
                                                    echo $assignee_nam['userName'];       
                                                }
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Candles"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  $assignee_nam = $db_helper->SingleDataWhere('stm_users','id = "'.$data_user_store['taskuserID'].'" ');
                                                
                                                    echo $assignee_nam['userName'];       
                                                }
                                            
                                            ?>
                                        </td>
                                    </tr>
                                
                            <?php
                               }//loop

                            ?>
                            </table>
                            <?php
                            }
                            }else if($_POST['listing_type'] == "pending"){
                            $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$supplier' AND taskStatusID != '".$statusName['id']."' ");
                              
                             if($tasks_report){
                            ?>
                               <table class="table table-bordered">
                                    <tr style="background-color: #ffcccc;">
                                        <td colspan="5" style="text-align:center;">
                                            <h4>Pending Listing for <?php echo  $supplierName['supplierName']; ?> - <?php echo date('d-m-Y',strtotime($_POST['fromdate'])) ?> TO <?php echo date('d-m-Y',strtotime($_POST['todate'])) ?></h4></td>
                                    </tr>
                                    <tr style="background-color:#eee;">
                                        <th>Task #</th>
                                        <th>Task Name</th>
                                        <th>Assigned On</th>
                                        <th>30 Days+ OLD</th>
                                        <th>15 Days+ OLD</th>
                                    </tr>     
                            <?php
                               foreach ($tasks_report as $tasks_data) {
                            ?>
                                    
                                    <tr>
                                        <td><a href="stmtaskdetail.php?id=<?php echo $tasks_data['id']; ?>"><?php echo $tasks_data['id'] ?></td>
                                        <td><?php echo $tasks_data['taskName'] ?></td>
                                        <td>
                                        <?php echo date("d-m-Y", strtotime($tasks_data['taskCreationDate'])); ?>  
                                        </td>
                                        <?php 
                                            $current_date = date('d-m-Y');
                                            $task_creation_date = date("d-m-Y", strtotime($tasks_data['taskCreationDate']));
                                            $diff = strtotime($current_date) - strtotime($task_creation_date);
                                            $daysCount = abs(round($diff / 86400));
                                            
                                        ?>
                                        <td>
                                            <?php 
                                            if($daysCount >= '30'){
                                                echo $daysCount;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if($daysCount >= '15'){
                                                echo $daysCount;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                            <?php
                               }//loop

                               ?>
                                   </table>
                               <?php
                             }//if task report
                            }//else if
                            }else if($_POST['supplier_type'] == $other_supplier['id']){

                            if($_POST['listing_type'] == "completed"){

                             $records = $db_helper->allRecordsRepeatedWhere('stm_supplier','supplierTypeID = "'.$_POST['supplier_type'].'" ');
                             
                             foreach ($records as $data) {

                                $other_suppliers = $data['id'];

                                $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$other_suppliers' AND taskStatusID = '".$statusName['id']."' ");
                                 
                                if($tasks_report){
                            ?>
                               <table class="table table-bordered">
                                    <tr style="background-color: #ffcccc;">
                                        <td colspan="8" style="text-align:center;"><h4>Completed Requests/Tasks for Other Supplier - From <?php echo date('d-m-Y',strtotime($_POST['fromdate'])) ?> TO <?php echo date('d-m-Y',strtotime($_POST['todate'])) ?></h4></td>
                                    </tr>
                                    <tr style="background-color:#eee;">
                                        <th>Task #</th>
                                        <th>Task Name</th>
                                        <th>Assigned On</th>
                                        <th>Amz OS Link</th>
                                        <th>Ebay OS Link</th>
                                        <th>Ebay AM Link</th>
                                        <th>Website OS Link</th>
                                        <th>Website AM Link</th>
                                    </tr>     
                            <?php
                               foreach ($tasks_report as $tasks_data) {
                            ?>
                                    
                                    <tr>
                                        <td><a href="stmtaskdetail.php?id=<?php echo $tasks_data['id']; ?>"><?php echo $tasks_data['id'] ?></td>
                                        <td><?php echo $tasks_data['taskName'] ?></td>
                                        <td>
                                        <?php echo date("d-m-Y", strtotime($tasks_data['taskCreationDate'])); ?>  
                                        </td>
                                
                                        <td>
                                            <?php 
                                            $amz_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Amazon"');
                                            $data = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskChannelID = '".$amz_data['id']."' AND subTaskName = 'Channel Listing'");
                                            
                                            if($data){
                                                
                                                echo $data['taskURL'];
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "onlinestreet-company"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  echo $data_user_store['taskURL'];      
                                                }
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Ebay"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Online"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                     echo $data_user_store['taskURL'];       
                                                }
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "OnlineStreet"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing'");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  echo $data_user_store['taskURL'];    
                                                }
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $ebay_data = $db_helper->SingleDataWhere('stm_channels','channelName = "Website"');
                                            $store_data = $db_helper->SingleDataWhere('stm_stores', 'storechannelID = "'.$ebay_data['id'].'" AND storeName = "Amaj Candles"');
                                            $data_user_store = $db_helper->SingleDataWhere("stm_taskassigned","taskID = '".$tasks_data['id']."' AND taskstoreID = '".$store_data['id']."' AND subTaskName = 'Channel Listing' ");
                                                if($data_user_store['taskstoreID']==$store_data['id']){
                                                  echo $data_user_store['taskURL'];       
                                                }
                                            
                                            ?>
                                        </td>
                                    </tr>
                                
                            <?php
                               }//loop

                            ?>
                            </table>
                            <?php
                            }  
                                 
                             }
                             
                            }else if($_POST['listing_type'] == "pending"){

                             $records = $db_helper->allRecordsRepeatedWhere('stm_supplier','supplierTypeID = "'.$_POST['supplier_type'].'" ');
                            ?>
                            <table class="table table-bordered">
                                <tr style="background-color: #ffcccc;">
                                    <td colspan="6" style="text-align:center;"><h4>Pending Requests/Tasks for Other Supplier - From <?php echo date('d-m-Y',strtotime($_POST['fromdate'])) ?> TO <?php echo date('d-m-Y',strtotime($_POST['todate'])) ?></h4></td>
                                </tr>
                                <tr style="background-color:#eee;">
                                    <th>Task #</th>
                                    <th>Task Name</th>
                                    <th>Assigned On</th>
                                    <th>Brand</th>
                                    <th>Assignee</th>
                                    <th>Comments</th>
                                </tr>
                            <?php 
                             foreach ($records as $data) {

                                $other_suppliers = $data['id'];

                                $tasks_report = $db_helper->allRecordsRepeatedWhere('stm_tasks', "taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskSupplierID = '$other_suppliers' AND taskStatusID != '".$statusName['id']."' ");
                              
                             if($tasks_report){

                               foreach ($tasks_report as $tasks_data) {
                            ?>
                                    
                                    <tr>
                                        <td><a href="stmtaskdetail.php?id=<?php echo $tasks_data['id']; ?>"><?php echo $tasks_data['id'] ?></td>
                                        <td><?php echo $tasks_data['taskName'] ?></td>
                                        <td>
                                        <?php echo date("d-m-Y", strtotime($tasks_data['taskCreationDate'])); ?>  
                                        </td>
                                        <td>
                                            <?php
                                            $brands = $db_helper->SingleDataWhere('stm_brands','id = "'.$tasks_data['taskBrandID'].'"');
                                            echo $brands['brandName'];
                                            ?>
                                        </td>
                                        <td>
                                           <?php
                                           $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-DONE"');
                                           $assignee_data = $db_helper->SingleDataWhere('stm_taskassigned','taskID = "'.$tasks_data['id'].'" AND taskStatusID !="'.$status['id'].'" AND subTaskName = "Channel Listing" ');
                                           
                                           $employe = $db_helper->SingleDataWhere('stm_users','id = "'.$assignee_data['taskuserID'].'"');

                                           echo $employe['userName'];
                                           ?>
                                        </td>
                                        <td>
                                            <?php echo $tasks_data['taskComments']; ?>
                                        </td>
                                    </tr>
                            <?php
                               }//loop
                             ?>
                                  
                               <?php
                             }//if task report

                              
                             }//first Loop
                             ?> </table><?php
                            }//else if listing type pending   

                            }//other supplier else





                            }//else
                            }//isset

                            ?>
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