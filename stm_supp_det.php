<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
  if(isset($_SESSION['id']))
  {
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db);
      $db_helper = new db_helper($db);

      $session_id = $_SESSION['id'];
      $fromDate = '';
      if(isset($_GET['fromDate'])){
        $fromDate = $_GET['fromDate'];  
      }
      $toDate = '';
      if(isset($_GET['toDate'])){
        $toDate = $_GET['toDate'];  
      }
      $type = '';
      if(isset($_GET['category'])){
        $type = $_GET['category'];  
      }
      $suplGet = $_GET['supps'];
      
      $tb = "stm_users";
      $wh = "id = '$session_id'";
      $session_data = $db_helper->SingleDataWhere($tb, $wh);

    $status = $db_helper->SingleDataWhere('stm_statuses','statusName = "5-Done"');

    $rejected = $db_helper->SingleDataWhere('stm_statuses','statusName = "Rejected"');

    $stApprov = $db_helper->SingleDataWhere('stm_statuses','statusName = "Approved"');

    $stNew = $db_helper->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

    $stProg = $db_helper->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
    $stInactive = $db_helper->SingleDataWhere('stm_statuses','statusName = "In-Active"');
    $forRev = $db_helper->SingleDataWhere('stm_statuses','statusName = "7-For Review"');
    $reviewed = $db_helper->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');

    $new = $stNew['id'];
    $progress = $stProg['id'];
    $done = $status['id']; 
    $approved = $stApprov['id'];

    if(isset($_GET['newRequest'])){
      $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskStatusID != "'.$stInactive['id'].'"');
    }else if(isset($_GET['newRequestDate'])){
        if(isset($_GET['category']) AND isset($_GET['fromDate'])){
          $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$type.' AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskSupplierID = "'.$suplGet.'" AND taskStatusID != "'.$stInactive['id'].'" ');
        }else if(isset($_GET['category']) AND !isset($_GET['fromDate'])){
          $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
        }else if(isset($_GET['fromDate']) AND isset($_GET['toDate'])){
          $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = '.$suplGet.' AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID != "'.$stInactive['id'].'" ');
        }
    }

    if(isset($_GET['completed'])){
      $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskStatusID = '.$reviewed['id'].' AND taskStatusID != '.$stInactive['id'].'');
    }else if(isset($_GET['completedDate'])){
       if(isset($_GET['category']) AND !isset($_GET['fromDate']) AND !isset($_GET['toDate'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskTypeID = "'.$_GET['category'].'" AND taskStatusID = '.$reviewed['id'].' AND taskStatusID != '.$stInactive['id'].'');
       }else if(isset($_GET['fromDate']) AND isset($_GET['toDate']) AND !isset($_GET['category'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID = '.$reviewed['id'].' AND taskStatusID != '.$stInactive['id'].'');
       }else if(isset($_GET['fromDate']) AND isset($_GET['toDate']) AND isset($_GET['category'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskTypeID = "'.$_GET['category'].'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID = '.$reviewed['id'].' AND taskStatusID != '.$stInactive['id'].'');
       }

    }

    if(isset($_GET['pending'])){
      $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
    }else if(isset($_GET['pendingDate'])){
        if(isset($_GET['category']) AND !isset($_GET['fromDate']) AND !isset($_GET['toDate'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskTypeID = "'.$_GET['category'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
       }else if(isset($_GET['fromDate']) AND isset($_GET['toDate']) AND !isset($_GET['category'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
       }else if(isset($_GET['fromDate']) AND isset($_GET['toDate']) AND isset($_GET['category'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$suplGet.'" AND taskTypeID = "'.$_GET['category'].'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
       }
    }

      //all get
      if(isset($_GET['supplierNew']) && isset($_GET['allget'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskSupplierID = "'.$_GET['supps'].'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID != "'.$stInactive['id'].'"');
      }
      if(isset($_GET['supplierComp']) && isset($_GET['allget'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID != "'.$stInactive['id'].'" ');
      }
      if(isset($_GET['supplierPend']) && isset($_GET['allget'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
      }

      // dates

      if(isset($_GET['supplierNew']) && isset($_GET['dates'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$_GET['supps'].'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID != "'.$stInactive['id'].'"');
      }

      if(isset($_GET['supplierComp']) && isset($_GET['dates'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$_GET['supps'].'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
      }

      if(isset($_GET['supplierPend']) && isset($_GET['dates'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$_GET['supps'].'" AND taskCreationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
      }

      // Suppl and cat

      if(isset($_GET['supplierNew']) && isset($_GET['ctANDsp'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID != "'.$stInactive['id'].'"');
      }
      if(isset($_GET['supplierComp']) && isset($_GET['ctANDsp'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
      }
      if(isset($_GET['supplierPend']) && isset($_GET['ctANDsp'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskTypeID = '.$_GET['category'].' AND taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
      }

      //all supp

      if(isset($_GET['supplierNew']) && isset($_GET['allsupp'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID != "'.$stInactive['id'].'"');
      }
      if(isset($_GET['supplierComp']) && isset($_GET['allsupp'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
      }
      if(isset($_GET['supplierPend']) && isset($_GET['allsupp'])){
        $record = $db_helper->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$_GET['supps'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
      }
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
                            <h4 class="mt-2">
                              <?php
                              $month = "";
                              if(isset($_GET['fromDate'])){
                                $month = "For ".date('M-Y',strtotime($_GET['fromDate']));  
                              }
                              ?>
                              Category Task Report <?php echo $month; ?>  
                            </h4>  
                          </div>  
                          <div class="widget-content widget-content-area">
                            
                            <table class="table table-striped table-sm table-striped">
                              <tr>
                                <th>Task #</th>
                                <th>Creation Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Status</th>
                              </tr>
                              <?php 
                              foreach($record as $list){
                                $supplier = $db_helper->SingleDataWhere('stm_supplier','id = "'.$list['taskSupplierID'].'"');
                                $sat = $db_helper->SingleDataWhere('stm_statuses','id = "'.$list['taskStatusID'].'"');
                                $taskTypeID = $db_helper->SingleDataWhere('stm_tasktypes','id = "'.$list['taskTypeID'].'"');

                                $taskName = strip_tags($list['taskName']);
                                
                                if (strlen($taskName) > 45) {
                                    // truncate string
                                    $stringCut = substr($taskName, 0, 45);
                                    $endPoint = strrpos($stringCut, ' ');

                                    $taskName = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $taskName .= '...';
                                }

                              ?>
                              <tr>
                                <td><a href="stmtaskdetail.php?id=<?php echo $list['id'] ?>&view#assignees" target="_blank" class="anchor"><?php echo $list['id']; ?></a></td>
                                <td>
                                  <?php echo date('d-m-Y',strtotime($list['taskCreationDate'])); ?>
                                </td>
                                <td><?php 
                                 if($taskName){
                                  echo $taskName;
                                 }
                                 ?> 
                                 </td>
                                
                                <td><?php echo $taskTypeID['tasktypeName']; ?></td>
                                <td><?php echo $supplier['supplierName']; ?></td>
                                <td><?php echo $sat['statusName']; ?></td>
                              </tr>
                              <?php  
                              }
                              ?>
                            </table>
                            
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