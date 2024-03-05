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

      $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');

    $stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');
    $done = $status['id']; 
    $approved = $stApprov['id'];
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
                            <div class="row mt-3">
                             <div class="col-md-3">
                               <span><h4>Task Summary</h4></span>   
                             </div>
                             <div class="col-md-9">
                               <form method="post">
                               <div class="row">
                                <div class="col-md-4">
                                  <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From Date ...">
                                </div>
                                <div class="col-md-4">
                                    <input id="toFlatpickr" name="todate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To Date ...">
                                </div>
                                <div class="col-md-3">
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

                                 $fromDate = date('Y-m-d',strtotime($_POST['fromdate']));
                                 $toDate = date('Y-m-d',strtotime($_POST['todate']));
                                 if($toDate < $fromDate){
                                ?>
                                    <div class="alert alert-danger" id="success-alert" role="alert">
                                      To Date never be less then From Date
                                    </div>
                                <?php
                                 }else{
                                ?>
<div class="row">
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th colspan="30" style="font-size:16px; font-weight: 700;">
                    Tasks Reviewed <?php
                    if($_POST['fromdate'] AND $_POST['todate']){
                        echo " FROM ".date('d-m-Y',strtotime($_POST['fromdate'])).
                        "&nbspTo&nbsp".date('d-m-Y',strtotime($_POST['todate']));
                    }?>
                </th>
            </tr>
        <?php 
            $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");?>
            <tr>
            <?php
            foreach ($users as $allusers) {
            ?>
              <th><?php echo $allusers['displayName']; ?></th>
            <?php } ?>    
            </tr>
            <tr>
                <?php
foreach ($users as $allusers) {                 
if($_POST['fromdate'] AND $_POST['todate']){       
    
    $dataSuper = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskApprovedOn BETWEEN '$fromDate' AND '$toDate' AND taskSupervisorID IN (".$allusers['id'].") AND taskStatusID  = '".$approved."' ");  

}else{
    
    $dataSuper = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskSupervisorID = "'.$allusers['id'].'" AND taskStatusID  = "'.$approved.'" ');
                                                
}

    $totalRecordsAPproved = count($dataSuper);
    echo "<td>".$totalRecordsAPproved."</td>";
}
                ?>
            </tr>
        </table>
    </div>
</div>
</div><br><br>                                
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered">
                                        <tr>
                                            <th colspan="3" style="font-size:16px; font-weight: 700;">Designing Tasks Completed <?php
                                                if($_POST['fromdate'] AND $_POST['todate']){
                                                    echo " FROM ".date('d-m-Y',strtotime($_POST['fromdate'])).
                                                    "&nbspTo&nbsp".date('d-m-Y',strtotime($_POST['todate']));
                                                }?></th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $department = $db_helper->SingleDataWhere('stm_departments','departmentName = "Graphich Designing"');
                                            $desig = $db_helper->allRecordsRepeatedWhere('stm_users','departmentID = "'.$department['id'].'"');
                                            
                                            foreach($desig as $designers){
                                              echo "<th>".$designers['displayName']."</th>";
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <?php

                                            $desigs = $db_helper->allRecordsRepeatedWhere('stm_users','departmentID = "'.$department['id'].'"');
                                            foreach($desigs as $alldesignes){


                                            if($_POST['fromdate'] AND $_POST['todate']){
                                               
        $data = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$alldesignes['id']."' AND taskStatusID IN (".$done.",".$approved.") ");  

                                            }else{
        $data = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskuserID = "'.$alldesignes['id'].'" AND taskStatusID IN ('.$done.','.$approved.')');
                                                
                                            }
                                               $totalRecords = count($data);
                                               echo "<td>".$totalRecords."</td>";

                                            }
                                            
                                            ?>
                                        </tr>
                                    </table></div>
                                    <br><br>
                                    <div class="col-md-12">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th colspan="30" style="font-size:16px; font-weight: 700;">Task Completed <?php
                                                if($_POST['fromdate'] AND $_POST['todate']){
                                                    echo "FROM ".date('d-m-Y',strtotime($_POST['fromdate'])).
                                                    "&nbspTO&nbsp".date('d-m-Y',strtotime($_POST['todate']));
                                                }?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <?php
                                            
                                            
                                            foreach ($users as $allusers) {
                                              echo "<th>".$allusers['displayName']."</th>";
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <?php
                                            $desigs = $db_helper->allRecordsRepeatedWhere('stm_users','departmentID = "'.$department['id'].'"');
                                            foreach ($users as $allusers) {
                                            
                                            if($_POST['fromdate'] AND $_POST['todate']){
                                               
    $dataCompleted = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$allusers['id']."' AND taskStatusID IN ('".$done."','".$approved."') ");  

                                            }else{
        $dataCompleted = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskuserID = "'.$allusers['id'].'" AND taskStatusID IN ("'.$done.'","'.$approved.'")');
                                                
                                            }
                                                
                                               $totalRecordsCompleted = count($dataCompleted);
                                               echo "<td>".$totalRecordsCompleted."</td>";

                                            }
                                            
                                            ?>
                                        </tr>
                                    </table>
                                    </div>        
                                    </div>
<br><br>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tr>
                <th colspan="30" style="font-size:16px; font-weight: 700;">Tasks Pending Task As Off <?php
                    echo date('d-m-Y',strtotime($_POST['todate']));
                ?></th>
            </tr>
            <tr>
                <?php 
                foreach ($users as $allusers) {
                ?>
                <th><?php echo $allusers['displayName'] ?></th>
                <?php 
                }
                ?>
            </tr>
            <tr>
                <?php
               
                foreach ($users as $allusers) {
                
                if($_POST['fromdate'] AND $_POST['todate']){
                
                   $dataPending = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskuserID = "'.$allusers['id'].'" AND taskStatusID NOT IN ("'.$done.'","'.$approved.'")');
                    
                   $totalRecordsPending = count($dataPending);
                   echo "<td>".$totalRecordsPending."</td>";

                }
                }
                
                ?>
            </tr>
        </table>
    </div>
</div>
<br><br>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tr>
                <th colspan="30" style="font-size:16px; font-weight: 700;">Tasks Created By The Bellow Team <?php
                if($_POST['fromdate'] AND $_POST['todate']){
                    echo "FROM ".date('d-m-Y',strtotime($_POST['fromdate'])).
                    "&nbspTO&nbsp".date('d-m-Y',strtotime($_POST['todate']));
                }?></th>
            </tr>
            <tr>
                <?php 
                foreach ($users as $allusers) {
                ?>
                <th><?php echo $allusers['displayName'] ?></th>
                <?php 
                }
                ?>
            </tr>
            <tr>
                <?php
               
                foreach ($users as $allusers) {
                
                if($_POST['fromdate'] AND $_POST['todate']){
                                                   
                $dataCreatedBy = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$allusers['id']."' ");  

                                                }else{
                $dataCreatedBy = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskAssignedBy = '".$allusers['id']."' ");
                                                    
                }
                    
                   $totalRecordsCreated = count($dataCreatedBy);
                   echo "<td>".$totalRecordsCreated."</td>";

                }
                
                ?>
            </tr>
        </table>
    </div>
</div>                                         

                                    </div>

                                     
                                <?php
                                 }//else close

                                }

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