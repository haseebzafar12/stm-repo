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
                            <form method="post">
                               <div class="row">
                                  <div class="col-md-2 offset-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="brand" placeholder="Add Brand" required>    
                                    </div>     
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                            <select name="suppliers" class="form-control suppliers">
                                                 <option value="none">Select Suppliers</option>
                                                    <?php
                                                      $userTypes = $db_helper->allRecordsOrderBy('stm_supplier','supplierName ASC');
                                                      foreach($userTypes as $list){
                                                    ?>
                                                    <option value="<?php echo $list['id']; ?>"><?php echo $list['supplierName']; ?></option>
                                                    <?php    
                                                      }
                                                    ?>
                                            </select>
                                        </div>      
                                    </div>
                                    <div class="col-md-1">
                                       <div class="form-group">
                                            <input type="submit" name="typesBTN" class="btn btn-primary" style="float:right;" value="Save">    
                                        </div>     
                                    </div>  
                               </div>    
                            </form>
                            <?php
                                if(isset($_POST['typesBTN'])){
                                    $brand = $db_helper->SingleDataWhere('stm_brands', 'brandName = "'.$_POST['brand'].'"');
                                    if($brand['brandName'] == $_POST['brand'])
                                    {
                                        echo "Already Exist";
                                    }else{
                                        $data = $objUser->stm_insert_brand($_POST['brand'], $_POST['suppliers']);
                                        if($data){
                                           
                                            // echo "<script>window.location = 'stmbrands.php'</script>";
                                        }
                                    }
                                    
                                }
                                
                            ?>
                            <div class="row">
                                <table class="table table-striped table-sm">
                                  <tr>
                                    <th>Brands</th>
                                    <th>Suppliers</th>
                                    <th>Action</th>  
                                  </tr>
                                  <!--  <form method="post"> -->
                                  <?php 
                                  $recs = $db_helper->allRecords('stm_brands');
                                  foreach($recs as $records){
                                  ?>
                                    <tr>
                                        <td>
                                        <?php echo $records['brandName']; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $data = $db_helper->SingleDataWhere('stm_supplier','id = "'.$records['supplierID'].'"');
                                            echo $data['supplierName'];
                                            ?>
                                        </td>
                                        <td>
                                            <svg class="updateBrand" style="color:blue;" data-id="<?php echo $records['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            
                                        </td>
                                    </tr>
                                  <?php
                                  }
                                  ?>
                                <!--   </form> -->
                                
                              </table>
                            </div>
                          </div>
                        </div>
                    
                   </div> 
                   <div class="modal fade" id="editBrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                     <div class="modal-dialog" role="document">
                       <div class="modal-content">
                           <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Update Brand</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>

                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="brandID">
                                <input type="hidden" class="supplier_ID">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <input type="text" class=" form-control brandName">
                                </div>
                                <div class="form-group">
                                    <label>Supplier - <b><span class="supplierSP"></span></b></label>

                                    <select name="supplier" class="form-control supplier">
                                        <option value="">Select Supplier</option>
                                        <?php
                                          $userTypes = $db_helper->allRecordsOrderBy('stm_supplier','supplierName ASC');
                                          foreach($userTypes as $list){
                                        ?>
                                        <option value="<?php echo $list['id']; ?>"><?php echo $list['supplierName']; ?></option>
                                        <?php    
                                          }
                                        ?>
                                    </select>
                                </div>
                                <div class="error"></div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-success saveBrand">Update</button>
                              <a data-dismiss="modal" class="btn" href="#">Cancel</a>
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