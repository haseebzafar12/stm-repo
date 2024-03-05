<?php ob_start();
session_start();
include_once('partials/header.php');
include_once('common/config.php');
include_once('common/user.php');
include_once('common/db_helper.php');
if (isset($_SESSION['id']) or isset($_SESSION['user'])) {
  $dbcon = new Database();
  $db = $dbcon->getConnection();
  $objUser = new user($db);
  $db_helper = new db_helper($db);

  $session_id = "";
  if (isset($_SESSION['user'])) {
    $session_id = $_SESSION['user'];
  } else if (isset($_SESSION['id'])) {
    $session_id = $_SESSION['id'];
  }

  $tb = "stm_users";
  $wh = "id = '$session_id'";
  $session_data = $db_helper->SingleDataWhere($tb, $wh);

  // if(isset($_GET['message'])){
  //   $objUser->updateUserMessage($_GET['message'],"1");
  // }

  if (isset($_GET['message'])) {
    date_default_timezone_set('ASIA/Karachi');
    $ToSeenDate = date('Y-m-d H:i:s');

    if (isset($_GET['to'])) {
      if ($_GET['to'] == $session_id) {
        $objUser->updateToSeenDate($_GET['message'], $ToSeenDate);
      }
    }
  }

  if (time() - $_SESSION["login_time_stamp"] > 10800) {
    session_unset();
    session_destroy();
    echo "<script>window.location='signin.php'</script>";
  }

  $id = $_GET['id'];
  $tb = "stm_users";
  $wh = "id = '$session_id'";
  $session_data = $db_helper->SingleDataWhere($tb, $wh);

  $tbl = "stm_tasks";
  $whe = "id = '$id'";
  $dataTask = $db_helper->SingleDataWhere($tbl, $whe);

  $tblType = "stm_tasktypes";
  $wheType = "id = '" . $dataTask['taskTypeID'] . "'";
  $type = $db_helper->SingleDataWhere($tblType, $wheType);

  $use = "stm_users";
  $wheuse = "id = '" . $dataTask['taskAssignedBy'] . "'";
  $dataUse = $db_helper->SingleDataWhere($use, $wheuse);

  // $useS = "stm_users";
  // $wheuseS = "id = '".$dataTask['taskSupervisorID']."'";
  // $dataUseS = $db_helper->SingleDataWhere($useS, $wheuseS);

  $proio = "stm_priorities";
  $wheproio = "id = '" . $dataTask['taskPriorityID'] . "'";
  $datawheproio = $db_helper->SingleDataWhere($proio, $wheproio);

  $status = "stm_statuses";
  $statuspio = "id = '" . $dataTask['taskStatusID'] . "'";
  $datastatuspio = $db_helper->SingleDataWhere($status, $statuspio);

  $br = "stm_brands";
  $brw = "id = '" . $dataTask['taskBrandID'] . "'";
  $brands = $db_helper->SingleDataWhere($br, $brw);
  $sp = "stm_supplier";
  $spw = "id = '" . $dataTask['taskSupplierID'] . "'";
  $suppliers = $db_helper->SingleDataWhere($sp, $spw);

  $creationDate = date("d M Y", strtotime($dataTask["taskCreationDate"]));
  //$deadlineDate = date("d M Y", strtotime($dataTask["taskDeadline"]));

  $tb = "stm_statuses";
  $wher = "id = '" . $dataTask['taskStatusID'] . "'";;
  $dataStatus =  $db_helper->SingleDataWhere($tb, $wher);
  $creationDate = date("d M Y", strtotime($dataTask["taskCreationDate"]));
  //$deadlineDate = date("d M Y", strtotime($dataTask["taskDeadline"]));
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
          <div class="row">
            <div class="col-md-12">
              <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area simple-tab">
                  <ul class="nav nav-tabs mb-3 mt-3" id="simpletab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">BASIC INFO</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="listing-tab" data-toggle="tab" href="#preListing" role="tab" aria-controls="listing" aria-selected="false">PRE-LISTING INFO</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="assignees-tab" data-toggle="tab" href="#assignees" role="tab" aria-controls="assignees" aria-selected="false">ASSIGNEES</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="content-tab" data-toggle="tab" href="#listingContent" role="tab" aria-controls="content" aria-selected="false">CONTENT</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="message" aria-selected="false">MESSAGES</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="simpletabContent">

                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <?php include_once "tabs/home.php"; ?>
                    </div><!----/home---->

                    <div class="tab-pane fade" id="preListing" role="tabpanel" aria-labelledby="listing-tab">
                      <?php include_once "tabs/prelisting.php"; ?>
                    </div><!----/preListing--->

                    <div class="tab-pane fade" id="assignees" role="tabpanel" aria-labelledby="assignees-tab">
                      <?php include_once "tabs/assignees.php"; ?>
                    </div><!----/assignees---->

                    <div class="tab-pane fade" id="listingContent" role="tabpanel" aria-labelledby="content-tab">
                      
                      <?php include_once "tabs/content.php"; ?>
                      
                    </div>

                    <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                        
                        <?php include_once "tabs/messages.php"; ?>

                    </div>

                  </div>
                </div>
              </div>
            </div><!---/row---->
          </div><!---/row---->
          <div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Task Related Information</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </button>

                </div>
                <div class="modal-body">

                  <input type="hidden" class="subID" value="<?php echo $_GET['sub']; ?>">
                  <input type="hidden" class="taskID" value="<?php echo $_GET['id']; ?>">
                  <div class="form-group">

                    <label>URL</label>
                    <input type="text" class="form-control url_view">

                  </div>
                  <div class="form-group">
                    <label>Comments</label>
                    <textarea class="comments_view form-control"></textarea>
                  </div>
                  <div class="error"></div>
                </div>
                <div class="modal-footer">
                  <a data-dismiss="modal" class="btn" href="#">Cancel</a>

                  <button type="button" class="btn btn-success saveInfo">Update</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="viewinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Task Related Information</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </button>

                </div>
                <div class="modal-body">

                  <div class="form-group">
                    <label>Desscription</label>
                    <textarea class="comments form-control"></textarea>
                  </div>

                </div>
                <div class="modal-footer">
                  <a data-dismiss="modal" class="btn" href="#">Cancel</a>

                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="add-reason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Reason</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" class="subID">
                  <input type="hidden" class="taskID" value="<?php echo $_GET['id']; ?>">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="reason form-control"></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <a data-dismiss="modal" class="btn" href="#">Cancel</a>
                  <button type="button" class="btn btn-danger saveReason">Reject</button>
                </div>
              </div>
            </div>
          </div>
        </div><!---layout-px-spacing-->

      <?php
      include_once "partials/footer.php";
    } else {
      echo "<script>window.location='signin.php'</script>";
    }
      ?>