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

   $supplier_id = "";
   if(isset($_GET['supplierid'])){
    $supplier_id = $_GET['supplierid'];
   }
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
                                <div class="col-md-5">
                                    <h5 style="float:left; margin-top: 5px;">Sales & Stock Analysis</h5>
                                    <input type="text" class="form-control" id="inv" placeholder="Search" style="float:left; width: 40%; height:37px; margin-left: 10px;">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control filterByStock" style="width:100%; height:37px;">
                                           <option value="">Filter By</option>
                                           <option value="supplier">Supplier</option>
                                           <option value="stock">Stock</option>
                                           <option value="supervisor">Supervisor</option>
                                        </select>
                                         
                                    </div>
                                </div>
                                <div class="col-md-3" id="filterRes">
                                    <select class="form-control suppliers" style="height:37px; width: 60%; display: none; float: left;">

                                        <option value="">Select Supplier</option>

                                         <?php 

                                         $suppliers = $db_helper->allRecordsOrderBy('stm_supplier','supplierName ASC');

                                         foreach($suppliers as $list){

                                         ?>

                                         <option value="<?php echo $list['id'] ?>"
                                            <?php 
                                            if($supplier_id == $list['id']){
                                                echo "selected = 'selected'";
                                            }
                                            ?>
                                            >
                                             <?php echo $list['supplierName']; ?>

                                         </option>

                                         <?php   

                                         }

                                         ?>           

                                    </select>
                                    <select class="form-control stock_res" style="height:37px; display: none; width: 60%; float: left;">
                                        <option value="">Select Stock Type</option>
                                        <option value="1">In Stock</option>
                                        <option value="0">Out of Stock</option>
                                    </select>
                                    <select class="form-control supervisors" style="height:37px; width: 60%; display: none; float: left;">

                                            <option value="">Select Supervisor</option>

                                         <?php 

                                         $users = $db_helper->allRecordsOrderBy('stm_users','userName ASC');

                                         foreach($users as $user){

                                         ?>

                                         <option value="<?php echo $user['id'] ?>">
                                             <?php echo $user['userName']; ?>

                                         </option>

                                         <?php   

                                         }

                                         ?>           

                                    </select>
                                    <a href="inventory.php" class="btn btn-success" style="margin-left:5px; float:left;">Clear</a>
                                </div>
                                <div class="col-md-1 offset-md-1" id="exportDiv">
                                    <a style="float:right;" href="saletrendexport.php" class="btn btn-warning btn-sm" id="exportall">Export</a>
                                </div>
                                 
                              </div><br>
                              <div id="#fetchCSV1"></div>
                              <div class="inventory"></div>  
                            </div>
                        </div>
                    </div>
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="salesDetail" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myLargeModalLabel">Sales Detail</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="listingBody">
                                        
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