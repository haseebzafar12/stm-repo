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
                                    <div class="col-md-2 offset-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control supplierName" name="supplierName" placeholder="Add Supplier">
                                        </div>     
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <select name="supplierType" class="form-control supplier_Type">
                                             <option value="">Select Supplier Type</option>
                                                <?php
                                                  $userTypes = $db_helper->allRecordsOrderBy('stm_suppliers_type','supplierType ASC');
                                                  foreach($userTypes as $list){
                                                ?>
                                                <option value="<?php echo $list['id']; ?>"><?php echo $list['supplierType']; ?></option>
                                                <?php    
                                                  }
                                                ?>
                                        </select>
                                       </div>      
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="superName" class="form-control superName">
                                                 <option value="">Select Supervisor</option>
                                                    <?php
                                                      $userTypes = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                                      foreach($userTypes as $list){
                                                    ?>
                                                    <option value="<?php echo $list['id']; ?>"><?php echo $list['userName']; ?></option>
                                                    <?php    
                                                      }
                                                    ?>
                                            </select>
                                        </div>
                                        </div>
                                    
                                    <div class="col-md-1">
                                       <div class="form-group">
                                          <input type="submit" name="typesBTN" class="btn btn-primary" value="Save">    
                                        </div>     
                                    </div>  
                               </div>    
                            </form>
                            <?php
                                if(isset($_POST['typesBTN'])){
                                    $brand = $db_helper->SingleDataWhere('stm_supplier', 'supplierName = "'.$_POST['supplierName'].'"');
                                    if($brand['supplierName'] == $_POST['supplierName'])
                                    {
                                        echo "Already Exist";
                                    }else{
                                        $data = $objUser->stm_insert_supplier($_POST['supplierName'], $_POST['supplierType'],$_POST['superName']);
                                        if($data){
                                           
                                            echo "<script>window.location = 'stmsuppliers.php'</script>";
                                        }
                                    }
                                    
                                }
                                
                            ?>
                            <div class="row">
                                <table class="table table-striped table-bordered table-sm">
                                  <tr>
                                    <th>SUPPLIER</th>
                                    <th>SUPPLIER TYPE</th>
                                    <th>SUPERVISOR</th>
                                    <th width="5%">LISTINGS</th>
                                    <th width="5%">ACTION</th>  
                                  </tr>
                                  <!--  <form method="post"> -->
                                  <?php 
                                  $recs = $db_helper->allRecordsOrderBy('stm_supplier','supplierName ASC');
                                  foreach($recs as $records){
                                    $supervisor = $db_helper->SingleDataWhere('stm_users','id = "'.$records['userID'].'"');
                                  ?>
                                   <tr>
                                        <td>
                                        <?php echo $records['supplierName']; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $data = $db_helper->SingleDataWhere('stm_suppliers_type','id = "'.$records['supplierTypeID'].'"');
                                            echo $data['supplierType'];
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $supervisor['displayName']; ?>
                                        </td>
    <?php 
     $master = $db->prepare("select count(*) as total from stm_itemmaster where SupplierID In ('".$records['id']."') ");
     $master->execute();
     $res = $master->fetch(PDO::FETCH_ASSOC);
     echo "<td align='right'><a target='_blank' class='anchor' href='channelListing.php?supplierid=".$records['id']."'>".$res['total']."</a></td>";
    ?>

                                        <td align="right">
                                            <a href="#" class="updateSupplier" style="color:blue;" data-id="<?php echo $records['id']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            
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
            <div class="modal fade" id="editSupplier">
             <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Supplier</h5>
                        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;">
                          Record has been updated.
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="supplierID">
                        <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" class="form-control supplierName">
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="supplierType_ID">
                             
                             <label>Supplier Type - <b><span class="supplierTypeSP"></span></b></label>

                            <select name="supplierType" class="form-control supplier_Type">
                                 <option value="">Select Supplier Type</option>
                                    <?php
                                      $userTypes = $db_helper->allRecordsOrderBy('stm_suppliers_type','supplierType ASC');
                                      foreach($userTypes as $list){
                                    ?>
                                    <option value="<?php echo $list['id']; ?>"><?php echo $list['supplierType']; ?></option>
                                    <?php    
                                      }
                                    ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="supplierType_ID">
                             
                             <label>Supervisor - <b><span class="supervisorTypeSP"></span></b></label>

                            <select name="supervisor" class="form-control supervisor">
                                 <option value="">Select Supervisor</option>
                                    <?php
                                      $userTypes = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                      foreach($userTypes as $list){
                                    ?>
                                    <option value="<?php echo $list['id']; ?>"><?php echo $list['userName']; ?></option>
                                    <?php    
                                      }
                                    ?>
                            </select>
                        </div>
                        <div class="error"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-success save_supplier">Update</button>
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