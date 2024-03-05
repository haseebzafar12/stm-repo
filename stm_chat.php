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
    $departms = $db_helper->SingleDataWhere('stm_departments','departmentName = "Digital Marketing and SEO"');

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
                <div class="chat-section layout-top-spacing">
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        
                        <div class="chat-system">
                            <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                            <div class="user-list-box" style="overflow:auto;width:25%;">
                                <div class="search">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    <input type="text" class="form-control" placeholder="Search" / style="padding-left: 50px !important;">
                                </div>
                                <div class="people"> 
                                </div>
                            </div>
                            <div id="user-modal" style="width:75%">
                                <div class="chat-not">
                                    <span><img src="images/stm.png"></span>
                                </div>
                            </div>
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