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
   $supplier_id = "";
   if(isset($_GET['supplierid'])){
    $supplier_id = $_GET['supplierid'];
   }
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
                         <div class="col-md-4">
                            <h5 style="float:left; margin-top: 5px;">Channel Listing</h5>
                            <input type="text" class="form-control" id="search_listing" placeholder="Search" style="float:left; width: 50%; height:37px; margin-left: 10px;">
                         </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control filterByList" style="width:100%; height:37px;">
                                   <option value="">Filter By</option>
                                   <option value="supplier">Supplier</option>
                                   <option value="stock">Stock</option>
                                   <option value="supervisor">Supervisor</option>
                                </select>
                                 
                            </div>
                         </div>
                         <div class="col-md-3" id="filterRes">
                            <select class="form-control supp_listing" style="height:37px; width: 60%; display: none; float: left;">

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
                            <select class="form-control stockRes" style="height:37px; display: none; width: 60%; float: left;">
                                <option value="">Select Stock Type</option>
                                <option value="1">In Stock</option>
                                <option value="0">Out of Stock</option>
                            </select>
                            <select class="form-control super_list" style="height:37px; width: 60%; display: none; float: left;">

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
                            <a href="channelListing.php" class="btn btn-success" style="margin-left:5px; float:left;">Clear</a>
                         </div>
                         <div class="col-md-1 offset-md-2" id="exportDiv">
                            <a href="listingexport.php" class="btn btn-warning btn-sm" id="exportall">Export</a>
                         </div> 
                      </div>
                      <br>
                      <div class="row">
                          <div class="col-md-12">
                              <ul id="legends_nav">
                                  <li><h6>LEGENDS:</h6></li>
                                  <li>
                                    <div style="background-color:#b3e6cc;height: 16px; width: 20px; float: left;"></div>&nbsp = LISTED</li>

                                  <li><div style="background-color:#ffcccc; height: 16px; width: 20px; float: left;"></div>&nbsp= NOT LISTED</li>
                                  <li><img src="images/tickmark.png" height="20" width="20">&nbsp= LIST IT</li>
                                  <li><img src="images/x.png" height="15" width="15"> = DON'T LIST</li>
                                  <li> &nbsp&nbsp<img src="images/tickcross.png" height="20" width="20"> = DELETE LIST</li>
                                  <li><div style="background-color:#ffcccc; height: 16px; width: 50px; float: left;"></div>&nbsp= 
                                  DISCONTINUED ITEM</li>
                                  
                              </ul>
                          </div>
                      </div>
                      <div id="#fetchCSV"></div>
                      <div class="channellisting"></div>  
                            
                        
                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="channellistingModal" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myLargeModalLabel">Listing Detail</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="listingBody">
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="DNlistModal" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myLargeModalLabel">Listing Detail</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="channelBody">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success channelSubmit">Update</button>
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