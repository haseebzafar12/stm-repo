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
                        <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <select class="form-control suppliers" style="float:left; width:65%; height:37px;">
                                            <option value="">Filter By Supplier</option>
                                         <?php 
                                         $suppliers = $db_helper->allRecordsOrderBy('stm_supplier','supplierName ASC');
                                         foreach($suppliers as $list){
                                         ?>
                                         <option value="<?php echo $list['id'] ?>">
                                             <?php echo $list['supplierName']; ?>
                                         </option>
                                         <?php   
                                         }
                                         ?>           
                                         </select>
                                         <a href="inventory.php" class="btn btn-success" style="float:right;">Clear</a>
                                     </div>
                                 </div>
                                 <div class="col-md-7"></div>
                                 <div class="col-md-2">
                                     <a href="saletrendexport.php" class="btn btn-danger btn-sm">Export</a>
                                 </div> 
                              </div><br>
                              <div id="#fetchCSV"></div>
                              <div class="inventory1"></div>  
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