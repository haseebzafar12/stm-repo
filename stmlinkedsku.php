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
                          <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-2">
                                    <select class="form-control mapping_area_filter">
                                        <option>Filter By</option>
                                        <?php 
                                        $channels = $db_helper->allRecordsOrderBy("stm_channels","channelName ASC");
                                        foreach($channels as $channelsList){
                                        ?>
                                        <option value="<?php echo $channelsList['id'] ?>">
                                            <?php echo $channelsList['channelName'] ?>
                                        </option>
                                        <?php    
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-8" id="mapping_area_stores"></div>
                                <div class="col-md-2">
                                    <input type="text" placeholder="Type Search..." class="form-control mapping_area_search">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mapping_area"></div>
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