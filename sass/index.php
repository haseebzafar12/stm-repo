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
   $fromDate = date('Y-m-01');
   $toDate = date('Y-m-d');
   $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');

    $stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');

    $stNew = $db_helper->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

    $stProg = $db_helper->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
    $stInactive = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
    $rejected = $db_helper->SingleDataWhere('stm_statuses','statusName = "Rejected"');
    $forRev = $db_helper->SingleDataWhere('stm_statuses','statusName = "7-For Review"');
    $reviewed = $db_helper->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');
    $new = $stNew['id'];
    $progress = $stProg['id'];
    $done = $status['id']; 
    $approved = $stApprov['id'];
    $users = $db_helper->allRecordsOrderBy("stm_users","userName ASC");
?>
<body>
    <?php
      include_once "partials/navbar.php";
    ?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
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
    <?php 
      if($session_id == $shmsData['id'] OR $session_id == $arData['id'] OR $session_id == $awaisData['id'] OR $session_id == $ashData['id']){  
    ?>
    <div class="row">
        <div class="col-md-8">
          <table class="table table-bordered table-sm">
              <thead style="background-color: #00a4b4;">
                  <tr>
                      <th colspan="4" style="color:white;">Tasks summary by category for <?php if(isset($_POST['fromdate']) AND $_POST['todate']){ echo date('M-Y',strtotime($_POST['fromdate'])); }else{
                        echo date('M-Y');
                      } ?><svg id="catsBodyIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu" style="float: right;"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></th>
                  </tr>
              </thead>
              <tbody id="catsBody">
                  <tr>
                      <td colspan="4">
                          <form class="form-horizontal" method="post">
                              <select class="form-control" name="supplier" style="width:21%; height:33px; float:left;">
                                  <option value="">Supplier</option>
                                   <?php 
                                    $supplier = $db_helper->allRecords('stm_supplier');
                                    foreach($supplier as $suppliers){
                                   ?>
                                  <option value="<?php echo $suppliers['id'] ?>"
                                    <?php
                                    if(isset($_POST['supplier'])){
                                        if($_POST['supplier'] == $suppliers['id']){
                                          echo "selected = 'selected'";
                                        }    
                                    }
                                    ?>>
                                    <?php echo $suppliers['supplierName'] ?>
                                  </option>
                                  <?php } ?>
                              </select>
                              <select class="form-control" name="category" style="width:21%; height:33px; float:left; margin-left: 2px;">
                                  <option value="">Category</option>
                                   <?php 
                                    $category = $db_helper->allRecords('stm_tasktypes');
                                    foreach($category as $categories){
                                   ?>
                                  <option value="<?php echo $categories['id'] ?>"
                                    <?php
                                    if(isset($_POST['category'])){
                                        if($_POST['category'] == $categories['id']){
                                          echo "selected = 'selected'";
                                        }    
                                    }
                                    ?>>
                                    <?php echo $categories['tasktypeName'] ?>
                                  </option>
                                  <?php } ?>
                              </select>
                              <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From ..." <?php
                                    if(isset($_POST['fromdate'])){
                                      echo "value = '".$_POST['fromdate']."'";
                                    }
                                    ?> style="width:15%; height:34px;" required='required'>
                              <input id="toFlatpickr" name="todate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To ..." style="width:15%; height:34px;"      <?php
                                    if(isset($_POST['todate'])){
                                      echo "value = '".$_POST['todate']."'";
                                    }
                                    ?> required>&nbsp
                              <input type="submit" name="filterCats" class="btn btn-success btn-sm" value="Filter">
                              <a href="index.php" class="btn btn-warning btn-sm">Reset</a>
                          </form>
                          
                      </td>
                        <?php 
                          if(isset($_POST['filterCats'])){
                            $supplierPost = $_POST['supplier'];
                            $fromPost     = $_POST['fromdate'];
                            $toPost     = $_POST['todate'];
                          }
                        ?>
                  </tr>
                    
                  <tr>
                      <th>Categories</th>
                      <th>Requests</th>
                      <th>Completed</th>
                      <th>Pending</th>
                  </tr>
                  <?php
                     
                     if(isset($_POST['category']) AND !empty($_POST['category'])){
                        $cats = $db_helper->SingleDataWhere('stm_tasktypes','id = "'.$_POST['category'].'"');
                        $catPost = $_POST['category'];
                        if($supplierPost != "" AND $catPost != "" AND !empty($fromPost) AND !empty($toPost)){
                            $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                        }else if($supplierPost != "" AND $catPost != "" AND empty($fromPost) AND empty($toPost)){
                            $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                        }else if($supplierPost == "" AND $catPost != "" AND empty($fromPost) AND empty($toPost)){
                            $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                        }else if($supplierPost == "" AND $catPost != "" AND !empty($fromPost) AND !empty($toPost)){
                            $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                        }
                        $totalnewRequest = count($newRequest);

                        if($supplierPost == "" AND $catPost == "" AND !empty($fromPost) AND !empty($toPost)){
                            $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                         }else if($supplierPost != "" AND $catPost != "" AND !empty($fromPost) AND !empty($toPost)){
                            $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                         }else if($supplierPost != "" AND $catPost != "" AND empty($fromPost) AND empty($toPost)){
                            $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                         }else if($supplierPost == "" AND $catPost != "" AND empty($fromPost) AND empty($toPost)){
                            $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                         }else if($supplierPost == "" AND $catPost != "" AND !empty($fromPost) AND !empty($toPost)){
                            $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
                         }
                        

                        $totalcomplet = count($complet);

                        if($supplierPost != "" AND $catPost != "" AND !empty($fromPost) AND !empty($toPost)){
                            $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
                         }else if($supplierPost != "" AND $catPost != "" AND empty($fromPost) AND empty($toPost)){
                            $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
                         }else if($supplierPost == "" AND $catPost != "" AND empty($fromPost) AND empty($toPost)){
                            $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
                         }else if($supplierPost == "" AND $catPost != "" AND !empty($fromPost) AND !empty($toPost)){
                            $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
                         }
                         $totalpendin = count($pendin);
                     ?>
                        <tr> 
                            <td><?php echo $cats['tasktypeName']; ?></td>
                            <td>
                                <?php 
                                if(isset($_POST['filterCats'])){
                                  if($totalnewRequest){
                                    $supGet = '';
                                    $get = '';
                                    if($supplierPost != ''){
                                        $supGet = "&supplier=".$supplierPost."";
                                    }
                                    $dates = '';
                                    if($fromPost != '' AND $toPost != '' AND $supplierPost != ''){
                                        $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.'&categoryNew&allget';
                                    }
                                    if($fromPost != '' AND $toPost != '' AND $supplierPost == ''){
                                      $get = '&fromDate='.$fromPost.'&toDate='.$toPost.'&categoryNew&dates';  
                                    }
                                    if($fromPost == '' AND $toPost == '' AND $supplierPost != ''){
                                      $get = "&categoryNew&supp";  
                                    }
                                    if($fromPost == '' AND $toPost == '' AND $supplierPost == ''){
                                      $get = "&categoryNew&all";  
                                    }
                                    echo '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].''.$get.'">'.$totalnewRequest.'</a>';
                                  }
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(isset($_POST['filterCats'])){
                                  if($totalcomplet){
                                    $supGet = '';
                                    $get = '';
                                    if($supplierPost != ''){
                                        $supGet = "&supplier=".$supplierPost."";
                                    }
                                    $dates = '';
                                    if($fromPost != '' AND $toPost != '' AND $supplierPost != ''){
                                        $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.'&categoryComp&allget';
                                    }
                                    if($fromPost != '' AND $toPost != '' AND $supplierPost == ''){
                                      $get = '&fromDate='.$fromPost.'&toDate='.$toPost.'&categoryComp&dates';  
                                    }
                                    if($fromPost == '' AND $toPost == '' AND $supplierPost != ''){
                                      $get = "&categoryComp&supp";  
                                    }
                                    if($fromPost == '' AND $toPost == '' AND $supplierPost == ''){
                                      $get = "&categoryComp&all";  
                                    }
                                    echo '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].''.$get.'">'.$totalcomplet.'</a>';
                                  }
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(isset($_POST['filterCats'])){
                                  if($totalpendin){
                                    $supGet = '';
                                    $get = '';
                                    if($supplierPost != ''){
                                        $supGet = "&supplier=".$supplierPost."";
                                    }
                                    $dates = '';
                                    if($fromPost != '' AND $toPost != '' AND $supplierPost != ''){
                                        $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.'&categoryPend&allget';
                                    }
                                    if($fromPost != '' AND $toPost != '' AND $supplierPost == ''){
                                      $get = '&fromDate='.$fromPost.'&toDate='.$toPost.'&categoryPend&dates';    
                                    }
                                    if($fromPost == '' AND $toPost == '' AND $supplierPost != ''){
                                      $get = "&categoryPend&supp";  
                                    }
                                    if($fromPost == '' AND $toPost == '' AND $supplierPost == ''){
                                      $get = "&categoryPend&all";  
                                    }
                                    echo '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].''.$get.'">'.$totalpendin.'</a>';
                                  }
                                }
                                ?>
                            </td>
                        </tr>
                     <?php    
                     }else{
                        $datacats = $db_helper->allRecordsOrderBy('stm_tasktypes','tasktypeName ASC');
                        foreach($datacats as $cats){ 
                     ?>
                         <tr> 
                            <td><?php echo $cats['tasktypeName']; ?></td>
                            <?php
                                
                                if(isset($_POST['filterCats'])){
                                 if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
                                    $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                                 }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
                                    $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                                 }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
                                    $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                                 }
                                }else{
                                 $newRequest = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
                                    
                                }

                                $totalnewRequest = count($newRequest);   
                            ?>
                            <td align="right">
                                <?php 
                                if(isset($_POST['filterCats'])){
                                  if($totalnewRequest){
                                    $supGet = '';
                                    if($supplierPost != ''){
                                        $supGet = "&supplier=".$supplierPost."";
                                    }
                                    $dates = "";
                                    if($fromPost != '' AND $toPost != ''){
                                        $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.' ';
                                    }
                                    echo '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].'&newRequestDate">'.$totalnewRequest.'</a>';
                                  }
                                }else{
                                   if($totalnewRequest){ 
                                     echo '<a class="anchor" target="_blank" href="stm_cats_detail.php?type='.$cats['id'].'&newRequest">'.$totalnewRequest.'</a>';
                                    }
                                }
                                ?>
                            </td>
                            <?php
                                if(isset($_POST['filterCats'])){
                                 if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
                                    $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                                 }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
                                    $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                                 }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
                                    $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
                                 }
                                }else{
                                 $complet = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
                                    
                                }
                                $totalcomplet = count($complet);
                            ?>
                            <td align="right">
                                <?php 
                                if(isset($_POST['filterCats'])){
                                  if($totalcomplet){
                                    $supGet = '';
                                    if($supplierPost != ''){
                                        $supGet = "&supplier=".$supplierPost."";
                                    }
                                    $dates = "";
                                    if($fromPost != '' AND $toPost != ''){
                                        $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.' ';
                                    } 
                                    echo '<a target="_blank" class="anchor" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].'&completedDate">'.$totalcomplet.'</a>';
                                  }
                                }else{
                                   if($totalcomplet){ 
                                     echo '<a target="_blank" class="anchor" href="stm_cats_detail.php?type='.$cats['id'].'&completed">'.$totalcomplet.'</a>';
                                    }
                                }
                                ?>
                            </td>
                            <?php
                                if(isset($_POST['filterCats'])){
                                 if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
                                    $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
                                 }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
                                    $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
                                 }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
                                    $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
                                 }
                                }else{
                                 $pendin = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
                                    
                                }
                                $totalpendin = count($pendin);
                            ?>
                            <td align="right">
                                <?php 
                                if(isset($_POST['filterCats'])){
                                  if($totalpendin){
                                    $supGet = '';
                                    if($supplierPost != ''){
                                        $supGet = "&supplier=".$supplierPost."";
                                    }
                                    $dates = "";
                                    if($fromPost != '' AND $toPost != ''){
                                        $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.' ';
                                    }  
                                    echo '<a target="_blank" class="anchor" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].'&pendingDate">'.$totalpendin.'</a>';
                                  }
                                }else{
                                   if($totalpendin){ 
                                     echo '<a target="_blank" class="anchor" href="stm_cats_detail.php?type='.$cats['id'].'&pending">'.$totalpendin.'</a>';
                                    }
                                }
                                ?>
                            </td>
                          </tr>
                     <?php  
                        }  
                     } 
                     
                  ?>
                 
                 
              </tbody>
          </table>  
        </div>
    </div>
    <hr>                        
                 
    <table class="table table-bordered table-sm" id="directTable">
        <thead style="background-color: #00a4b4;">
            <tr>
                <th colspan="6">
                    CONSOLIDATED TASK SUMMARY FROM <?php
                        echo date('d-m-Y',strtotime($fromDate))."&nbspTO&nbsp".date('d-m-Y',strtotime($toDate));
                    ?>
                    <svg id="menu-ex" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu" style="float: right;"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </th>
            </tr>
        </thead>
        <tbody id="tableBdy">
        <tr>
            <th>NAME</th>
            <th style="text-align:center;">CREATED</th>
            <th style="text-align:center;">DONE (as assignee)</th>
            <th style="text-align:center;">PENDING</th>
            <th style="text-align:center;">REVIEWED (as supervisor)</th>
            <th style="text-align:center;">PENDING (ready for review)</th>
        </tr>
        <?php 
            
            foreach ($users as $allusers) {

            $dataCreatedBy = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$allusers['id']."' AND taskStatusID != '18'");
            $totalRecordsCreated = count($dataCreatedBy);    

            $dataCompleted = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$allusers['id']."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
            $totalRecordsCompleted = count($dataCompleted);

            $pendThisMonth = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID NOT IN ('16','21') AND taskuserID = '".$allusers['id']."' AND isActive = '1'");

            $total_pending_task = count($pendThisMonth);

            $approvedOn = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"(taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskApprovedOn BETWEEN '$fromDate' AND '$toDate') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

            $totalapprovedOn = count($approvedOn);

            $unreviewed = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskApprovedOn IS NULL AND taskStatusID = '$done' AND taskSupervisorID = '".$allusers['id']."' AND isActive = '1' ");

            $totalUnreviewed = count($unreviewed);
        ?>
            <tr>
                <td><?php echo $allusers['userName']; ?></td>
                <td align="right">
                    <?php 
                    if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                    ?>
                    <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&createdby&assignees" target="_blank">   
                    <?php 
                    }
                    if($totalRecordsCreated){
                    echo $totalRecordsCreated;     
                    }
                    ?>
                    </a>
                </td>
                <td align="right">
                    <?php 
                    if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                    ?>
                    <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&compthismon&assignees" target="_blank">
                    <?php
                    } 
                    if($totalRecordsCompleted){
                    echo $totalRecordsCompleted;     
                    }
                    ?>
                    </a>
                </td>
                <td align="right">
                    <?php 
                    if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                    ?>
                    <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&pendthismon&assignees" target="_blank">
                    <?php
                    } 
                    if($total_pending_task){
                     echo $total_pending_task;     
                    }
                    ?>
                    </a>
                </td>
                <td align="right">
                    <?php 
                    if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                    ?>
                    <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&comprev&reviewers" target="_blank">
                    <?php
                    } 
                    if($totalapprovedOn){
                        echo $totalapprovedOn;     
                    }
                    ?>
                    </a>
                </td>
                <td align="right">
                    <?php 
                    if($session_id == $allusers['id'] OR $session_id == $shmsData['id'] OR $session_id == $awaisData['id']){
                    ?>
                    <a class="anchor" href="detailReport.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&uid=<?php echo $allusers['id']; ?>&unreview&reviewers" target="_blank">
                    <?php
                    } 
                    if($totalUnreviewed){
                        echo $totalUnreviewed;
                    }
                    ?>
                    </a>
                </td>

            </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
    <?php }else{
          
          $preComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate < '$fromDate' AND (taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskEndDate IS NULL) AND taskuserID = '".$session_id."' AND isActive = '1' ");

          $totalPreviousCompleted = count($preComp);

          $previousnotComp = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate < '$fromDate' AND (taskApprovedOn BETWEEN '$fromDate' AND '$toDate' OR taskStatusID = '$done') AND taskSupervisorID = '".$session_id."' AND isActive = '1' ");

          $totalPrevComp = count($previousnotComp);

          $newAssigned = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$session_id."' AND isActive = '1' ");
          $totalnewAssigned = count($newAssigned);

          $readyCurrent = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskSupervisorID = '".$session_id."' AND isActive = '1' ");

          $totalreadyCurrent = count($readyCurrent);

          $dataCompleted = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskEndDate BETWEEN '$fromDate' AND '$toDate' AND taskuserID = '".$session_id."' AND taskStatusID IN ('".$done."','".$approved."') AND isActive = '1'");
          $totalRecordsCompleted = count($dataCompleted);

          $approvedOn = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"(taskEndDate BETWEEN '$fromDate' AND '$toDate' OR taskApprovedOn BETWEEN '$fromDate' AND '$toDate') AND taskStatusID IN ('".$approved."','".$rejected['id']."') AND taskSupervisorID = '".$session_id."' AND isActive = '1' ");

          $totalapprovedOn = count($approvedOn);

          $dataCreatedBy = $db_helper->allRecordsRepeatedWhere('stm_tasks',"taskCreationDate BETWEEN '$fromDate' AND '$toDate' AND taskAssignedBy = '".$session_id."' AND taskStatusID != '18'");
          $totalRecordsCreated = count($dataCreatedBy); 

          $penassignee = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID NOT IN ('$done','$approved') AND taskuserID = '".$session_id."' AND isActive = '1'");
          $totalpending = count($penassignee);

          $penSuper = $db_helper->allRecordsRepeatedWhere('stm_taskassigned',"taskStatusID = '$done' AND taskSupervisorID = '".$session_id."' AND isActive = '1'");
          $totalpenSuper = count($penSuper);
       
    ?>
    <div class="row">
        <div class="col-md-4">
          <table class="table table-bordered table-sm" style="width:95%">
              <thead style="background-color: #00a4b4;">
                  <tr>
                      <th colspan="2" style="color:white;">CURRENT MONTH TASKS SUMMARY <svg id="menu-ex" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu" style="float: right;"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></th>
                     
                  </tr>
              </thead>
              <tbody id="tableBdy">
                  <tr>
                      <td>OPENING (start of the month)</td>
                      <td align="right">
                        <a class="anchor" href="userreport.php?opening" target="_blank">
                            <?php echo $totalPreviousCompleted+$totalPrevComp; ?>
                        </a>
                      </td>
                  </tr>
                  <tr>
                      <td>ASSIGNED (during the month)</td>
                      <td align="right">
                        <a class="anchor" href="userreport.php?assigned" target="_blank">
                        <?php echo $totalnewAssigned+$totalreadyCurrent; ?>
                        </a>
                      </td>
                  </tr>
                  <tr>
                      <td>COMPLETED (as assignee)</td>
                      <td align="right">
                        <a class="anchor" href="userreport.php?completedA" target="_blank">
                            <?php echo $totalRecordsCompleted; ?>
                        </a>
                      </td>
                  </tr>
                  <tr>
                      <td>REVIEWED (as supervisor)</td>
                      <td align="right">
                        <a class="anchor" href="userreport.php?completedS" target="_blank">
                        <?php echo $totalapprovedOn; ?>
                        </a>
                      </td>
                  </tr>
                  <tr>
                      <td>IN HAND (not completed)</td>
                      <td align="right">
                        <a class="anchor" href="userreport.php?pending" target="_blank">
                        <?php echo $totalpending+$totalpenSuper; ?>
                        </a>
                      </td>
                  </tr>
                  <tr>
                      <td>ADDED IN STM (as creator)</td>
                      <td align="right">
                        <a class="anchor" href="userreport.php?creator" target="_blank">
                        <?php echo $totalRecordsCreated; ?>
                        </a>
                      </td>
                  </tr>
              </tbody>
          </table>  
            
        </div>
    </div>
    
    <?php  
    
          
    } ?>
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