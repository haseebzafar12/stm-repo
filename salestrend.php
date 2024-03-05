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
                                <h5 style="float:left;">Step: 1&nbsp&nbsp</h5><input id="trendDate" name="FBMFlatpickr" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date" readonly="readonly" style="float:left; width:63.9%; height: 33px; ">
                            </div><br><br><br>
                            <div class="col-md-12">

                                <h5 style="float:left;">Step: 2&nbsp&nbsp</h5><button class="btn btn-danger btn-sm" id="importBtn" style="float:left;">Upload Linnworks (FBM) Inventory</button>&nbsp
                                <div class="tickmark_fbm" style="float:left; margin-left:20px;"></div>
                                <div class="form-group" style="display:none; float: left;" id="upload_excel">    
                                    

                                    <label for="UploadCSV"></label>
                                    <input type="file" name="file" id="UploadCSV" style="float:left; margin-left: 5px;">

                                    <input type="submit" name="fileUpload" value="Upload CSV" class="btn btn-info btn-sm uploadCSV" style="float:left;">
                                    
                                </div>
                            </div><br><br><br>    
                            <div class="col-md-12">
                                <h5 style="float:left;">Step: 3&nbsp&nbsp</h5><input type="button" class="btn btn-danger btn-sm" id="importFbaOs" value="Upload FBA.OS.Inventory" style="float:left;">   
                                <div class="tickmark_fbaos" style="float:left; margin-left:20px;"></div>
                                <div class="form-group" style="float:left; display:none;" 
                                id="importFBAOs">

                                    <label for="importFbaInvOs"></label>
                                    <input type="file" name="file1" id="importFbaInvOs" style="float:left; margin-left:5px;">
                                    <input type="button" name="fileUpload" value="Upload CSV" class="btn btn-info btn-sm importFbaInvOs" style="float:left;">
                                    
                                </div>

                            </div>
                            <br><br><br>
                            <div class="col-md-12">
                                <h5 style="float:left;">Step: 4&nbsp&nbsp</h5><input type="button" style="float:left;" class="btn btn-danger btn-sm" id="importFbaQc" value="Upload FBA.QC Inventory">

                                <div class="tickmark_fbaqc" style="float:left; margin-left:20px;"></div>

                                <div class="form-group" style="display:none; float: left;" 
                                    id="importFBAQc">

                                    <label for="importFbaInvQc"></label>
                                    <input type="file" name="file1" id="importFbaInvQc" style="float:left; margin-left:5px;">
                                    <input type="button" name="fileUpload" value="Upload CSV" class="btn btn-info btn-sm importFbaInvQc" style="float:left;">
                                    
                                </div>
                            </div><br><br><br>
                            <div class="col-md-12">
                                <h5 style="float:left;">Step: 5&nbsp&nbsp</h5><input type="button" style="float:left;" class="btn btn-warning btn-sm" id="importAmzOS" value="Upload AMZ.OS Sale">
                                <div class="tickmark_amzos" style="float:left; margin-left:20px;"></div>
                                <div class="form-group" style="float:left; display:none;" id="importamzos_sale">

                                    <label for="AmzOSFile"></label>
                                    <input type="file" name="file1" id="AmzOSFile" style="float:left; margin-left: 5px;">
                                    <input type="button" name="fileUpload" value="Upload CSV" class="btn btn-info btn-sm import_amz_os" style="float:left;">
                                    <div class="tickmark" style="float:left; margin-left:20px;"></div>
                                </div>
                            </div><br><br><br>    
                            <div class="col-md-12">
                                <h5 style="float:left;">Step: 6&nbsp&nbsp</h5><input type="button" style="float:left;" class="btn btn-warning btn-sm" id="importAmzQC" value="Upload AMZ.QC Sale">
                                <div class="tickmark_amzqc" style="float:left; margin-left:20px;"></div>
                                <div class="form-group" style="float:left; display:none;" id="importamzqc_sale">
                                    
                                    <label for="AmzQCFile"></label>
                                    <input type="file" name="file1" id="AmzQCFile" style="float:left; margin-left: 5px;">
                                    <input type="button" name="fileUpload" value="Uplaod CSV" class="btn btn-info btn-sm import_amz_qc" style="float:left;">
                                    
                                </div>
                            </div><br><br><br>
                            <div class="col-md-12">
                                <h5 style="float:left;">Step: 7&nbsp&nbsp</h5><input type="button" class="btn btn-warning btn-sm" id="importEbayOS" style="float:left;" value="Upload eBay.OS Sale">
                                <div class="tickmark_ebayos" style="float:left; margin-left:20px;"></div>
                                <div class="form-group" style="float:left;display:none;" id="importebayos_sale">

                                    <label for="EbayOSFile"></label>
                                    <input type="file" name="file1" id="EbayOSFile" style="float:left; margin-left:5px;">
                                    <input type="button" name="fileUpload" value="Upload CSV" class="btn btn-info btn-sm import_ebay_os" style="float:left;">
                                   
                                </div> 
                            </div><br><br><br>    
                            <div class="col-md-12">
                              <h5 style="float:left;">Step: 8&nbsp&nbsp</h5><input type="button" class="btn btn-warning btn-sm" id="importEbayAC" style="float:left;" value="Upload ebay.AO Sale">
                              <div class="tickmark_ebayao" style="float:left; margin-left:20px;"></div>
                              <div class="form-group" style="float:left;display:none;" id="importebayac_sale">

                                    <label for="EbayACFile"></label>
                                    <input type="file" name="file1" id="EbayACFile" style="float:left; margin-left:5px;">
                                    <input type="button" name="fileUpload" value="Upload CSV" class="btn btn-info btn-sm import_ebay_ac" style="float:left; margin-left:5px;">
                                    
                                </div>      
                            </div><br><br><br>

                            <div class="successMsg">
                                <div class="alert custom-alert-1 mb-4" id="alertBox" role="alert" style="display:none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> .... </svg></button>
                                    <div class="media">
                                        <div class="alert-icon">
                                            <svg> .... </svg>
                                        </div>
                                        <div class="media-body">
                                            <div class="alert-text">
                                                <h5><strong>Success! </strong><span> You are completed all the steps,Click button to view the report.</span></h5> 
                                            </div>
                                            <div class="alert-btn">
                                                <a href="inventory.php" class="btn btn-default btn-dismiss">Go</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12" id="fetchCSV">
                                
                                <div id="imageloading" style="display: none; margin-left:35%; margin-top:5%;"><img src="images/loading-icon.gif" height="250" width="250"><br><h3 style="margin-left:13%;">Loading Data...</h3></div>
                                        
                            </div>
                        </div>
                        <br>
                         
                    </div>
                </div>
            </div><!---layout-px-spacing-->

        <?php
          include_once "partials/footer.php";
        }else{  
          echo "<script>window.location='signin.php'</script>";
        }
        ?>