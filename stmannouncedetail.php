<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
      include_once ('common/announceClass.php');

   if(isset($_SESSION['id']) OR isset($_SESSION['user']))
   {
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db);
      $db_helper = new db_helper($db);
      $objAnnouce = new announceClass($db);

       $session_id = "";
       if(isset($_SESSION['user'])){
        $session_id = $_SESSION['user'];
       }else if(isset($_SESSION['id'])){
        $session_id = $_SESSION['id'];
       }

      $tb = "stm_users";
      $wh = "id = '$session_id'";
      $session_data = $db_helper->SingleDataWhere($tb, $wh);

      $dataNews = $db_helper->SingleDataWhere('stm_announcements','id = "'.$_GET['id'].'"');
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
                          <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h5><?php echo $dataNews['title']; ?></h5>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <p class="detail">
                                        <?php echo $dataNews['detail']; ?>
                                    </p>
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