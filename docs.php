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
                          		<div class="col-md-12">
                          			<div class="row">
		                               <div class="col-md-12">
		                                 <div class="row">
		                                    <div class="col-md-4">
		                                       <input type="text" class="form-control searchFile" placeholder="Search title"> 
		                                    </div>
		                                 </div>
		                               </div>     
		                            </div>
                          		</div>
                          	</div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                	<div class="directory"></div>
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