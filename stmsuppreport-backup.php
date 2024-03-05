<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
  if(isset($_SESSION['id']))
  {
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db);
      $db_helper = new db_helper($db);

      $session_id = "";
      
      if(isset($_SESSION['user'])){
        $session_id = $_SESSION['user'];  
      }
      if(isset($_SESSION['id'])){
        $session_id = $_SESSION['id']; 
      }
     
      
      $tb = "stm_users";
      $wh = "id = '$session_id'";
      $session_data = $db_helper->SingleDataWhere($tb, $wh);
?>
    <body>
        <div class="navbar navbar-fixed-top">
            <?php
              include_once "partials/navbar.php";
            ?>
        </div>
        <div class="container-fluid">
            
            <div class="row-fluid">
                <!--/span-->
<div class="span12" id="content">  
    <div class="row-fluid">
        <!-- block -->
        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="pull-right">
                    <button class="btn btn-info get_report" data-toggle="modal" data-target="#reports" style="margin-bottom:5px;">Get Report</button>
                </div>
            </div>
            <div class="block-content collapse in">
               <div class="span12" id="content_report"></div>
            </div>
        </div>
        <!-- /block -->
    </div>
    <div id="reports" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h4>Tasks Report by Supplier</h4>
        </div>
        <div class="modal-body" style="margin-left:25%;">
            <div class="controls">
                <div class="control-group">
                    <label>Supplier Type</label>
                    <select name="supplier_type" class="supplier_type">
                        <option>Supplier Type</option>
                        <?php
                        $suppliers = $db_helper->allRecords('stm_suppliers_type');
                        foreach($suppliers as $supplier_data){
                        ?>
                        <option value="<?php echo $supplier_data['id'] ?>">
                            <?php echo $supplier_data['supplierType'] ?>
                        </option> 
                        <?php   
                        }
                        ?>
                    </select>
                </div>
                <div class="control-group" id="supplier" style="display:none;">
                    <label>Supplier</label>
                    <select name="supplier" class="supplier">
                        <option>Select Supplier</option>
                    </select>
                </div>
                <div class="control-group" id="supplier">
                    <label>Status</label>
                    <select name="listing_type" class="listing_status">
                        <option>Select Status</option>
                        <option value="pending">Pending Tasks</option>
                        <option value="completed">Completed Tasks</option>
                    </select>
                </div>
                <div class="control-group" id="supplier">
                    <label>From Date</label>
                    <input type="date" class="fromdate" value="From Date" required>
                </div>
                <div class="control-group" id="supplier">
                    <label>To Date</label>
                    <input type="date" class="todate" required>
                </div>
                <div class="error"></div>
                <div class="control-group">
                    <button class="btn btn-success" id="get_report">Get report</button>
                    <a data-dismiss="modal" class="btn btn-danger" href="#">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div><!---content--->
            </div>
           
            <hr>
            <footer>
                <p>&copy; Swift Task Managment 2022</p>
            </footer>
        </div>
        <!--/.fluid-container-->
        <?php 
          include_once ('partials/footer.php');
      }else{
          echo "<script>alert('Please Login')</script>";  
          echo "<script>window.location='signin.php'</script>";
      }
        ?>