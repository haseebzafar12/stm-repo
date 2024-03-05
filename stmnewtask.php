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

      $task_get_data = "";
      if(isset($_GET['session'])){
        $tb = "stm_tasks";
        $wh = "id = '".$_GET['session']."'";
        $task_get_data = $db_helper->SingleDataWhere('stm_tasks', "id = '".$_GET['session']."'");  
      } 
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
            <!-- <div class="layout-px-spacing"> -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="statbox widget box box-shadow">
                      <div class="widget-header">
                          <div class="row">
                              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                  <h4>BASIC INFORMATION</h4>
                              </div>
                          </div>
                      </div>
                      <div class="widget-content widget-content-area">
                        <div class="row-fluid">
                          <div class="col-md-12">
                            <form method="post">
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Category</label>
                                <div class="col-md-10">
                                    <select name="type" id="select_field" class="form-control form-control-sm">
                                        <option value="none">Select</option>
                                        <?php
                                          $tbl = "stm_tasktypes";
                                          $userTypes = $db_helper->allRecordsOrderBy($tbl,'tasktypeName ASC');
                                          foreach($userTypes as $list){
                                        ?>
                                            <option value="<?php echo $list['id']; ?>"<?php
                                            if(isset($_GET['session'])){
                                            if($task_get_data['taskTypeID'] == $list['id']){
                                            echo "selected = 'selected'";
                                            }
                                            }else if(isset($_POST['type'])){
                                            if($_POST['type'] == $list['id']){
                                            echo "selected = 'selected'";
                                            }
                                            }?>><?php echo $list['tasktypeName']; ?></option>
                                        <?php    
                                          }
                                        ?>
                                    </select>
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Task Name<span style="color:red;">&nbsp*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="taskName" class="form-control" id="input_field" value="<?php
                                    if(isset($_GET['session'])){
                                    echo $task_get_data['taskName'];
                                    }else if(isset($_POST['taskName'])){
                                    echo $_POST['taskName'];
                                    }?>" required>
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Description</label>
                                <div class="col-md-10">
                                    <textarea rows="7" class="form-control" name="description" id="input_field"><?php if(isset($_GET['session'])){
                                        echo $task_get_data['taskDescription'];
                                    }else if(isset($_POST['description'])){
                                        echo $_POST['description'];
                                    } ?></textarea>
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Supplier</label>
                                <div class="col-md-10">
                                    <select name="supplier" id="select_field" class="form-control form-control-sm supplier">
                                      <option value="0">Select</option>
                                      <?php
                                      $userTypes = $db_helper->allRecordsOrderBy("stm_supplier","supplierName ASC");
                                      foreach($userTypes as $list){
                                      ?>
                                      <option value="<?php echo $list['id']; ?>" <?php
                                       if(isset($_GET['session'])){
                                           if($task_get_data['taskSupplierID'] == $list['id']){
                                              echo "selected = 'selected'";
                                           }
                                       }else if(isset($_POST['supplier'])){
                                          if($_POST['supplier'] == $list['id']){
                                              echo "selected = 'selected'";
                                           }
                                       }?>>
                                       <?php echo $list['supplierName']; ?>
                                      </option>
                                      <?php    
                                        }
                                      ?>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Suppliers Brand</label>
                                <div class="col-md-10">
                                    <select name="brand" class="brands form-control form-control-sm" id="select_field">
                                      <?php 
                                      if(!isset($_POST['brand']) AND !isset($_GET['session'])){
                                        echo "<option value='0'>Select Brand</option>";
                                      }else{

                                      ?>
                                        <option
                                        <?php
                                         if(isset($_GET['session'])){
                                              echo "value = '".$task_get_data['taskBrandID']."'";
                                          
                                         }if(isset($_POST['brand'])){
                                            echo "value = '".$_POST['brand']."'";
                                          }?> >
                                            <?php 
                                            if(isset($_POST['brand'])){
                                             $brand = $db_helper->SingleDataWhere('stm_brands','id = "'.$_POST['brand'].'"');
                                             echo $brand['brandName'];
                                            }else if(isset($_GET['session'])){
                                             $brand_DB = $db_helper->SingleDataWhere('stm_brands','id = "'.$task_get_data['taskBrandID'].'"');
                                             echo $brand_DB['brandName'];
                                            }
                                            ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Priority</label>
                                <div class="col-md-10">
                                    <select name="priority" id="select_field" class="priority form-control form-control-sm">
                                        <option value="0">Select</option>
                                        <?php
                                        $prtrs = $db_helper->allRecordsOrderBy("stm_priorities","taskpriorityName ASC");
                                        foreach($prtrs as $list){
                                        ?>
                                        <option value="<?php echo $list['id']; ?>" <?php
                                         if(isset($_GET['session'])){
                                             if($task_get_data['taskPriorityID'] == $list['id']){
                                                echo "selected = 'selected'";
                                             }
                                         }else if(isset($_POST['priority'])){
                                            if($_POST['priority'] == $list['id']){
                                                echo "selected = 'selected'";
                                             }
                                         }?>>
                                         <?php echo $list['taskpriorityName']; ?>
                                        </option>
                                        <?php    
                                          }
                                        ?>
                                    </select>
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Our Brand</label>
                                <div class="col-md-10">
                                 
                                  <?php
                                    $ourbrands = $db_helper->allRecords('stm_ourbrands');
                                    foreach ($ourbrands as $ourbrandsList) {
                                  ?>
                                    <label class="new-control new-radio new-radio-text radio-success">
                                    <input type="radio" name="ourBrand" class="new-control-input" value="<?php echo $ourbrandsList['id']; ?>"
                                    <?php 
                                    if(isset($_GET['session'])){
                                      if($task_get_data['taskOurBrandID'] == $ourbrandsList['id']){
                                        echo "checked";
                                      }
                                    }else if(isset($_POST['ourBrand'])){
                                      if($_POST['ourBrand'] == $ourbrandsList['id']){
                                        echo "checked";
                                      }
                                    }
                                    ?>
                                    >
                                    <span class="new-control-indicator"></span><span class="new-radio-content"><?php echo $ourbrandsList['brandName']; ?></span>
                                    
                                    </label> 
                                  <?php
                                    }
                                  ?>
                                  
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Listing Type</label>
                                <div class="col-md-10">
                                 
                                  <?php
                                    $listingtype = $db_helper->allRecordsRepeatedWhere('stm_listingtype','listingTypeName = "Single" OR listingTypeName = "Variation" ');
                                    foreach($listingtype as $listingtypeList){
                                  ?>
                                    <label class="new-control new-radio new-radio-text radio-success">
                                    <input type="radio" name="TasklistingType" class="new-control-input" value="<?php echo $ourbrandsList['id']; ?>"
                                    <?php 
                                      if(isset($_GET['session'])){
                                        if($task_get_data['taskListingTypeID'] == $listingtypeList['id']){
                                          echo "checked";
                                        }
                                      }else if(isset($_POST['TasklistingType'])){
                                        if($_POST['TasklistingType'] == $listingtypeList['id']){
                                          echo "checked";
                                        }
                                      }
                                      ?>
                                    >
                                    <span class="new-control-indicator"></span><span class="new-radio-content"><?php echo $listingtypeList['listingTypeName']; ?></span>
                                    
                                    </label> 
                                  <?php
                                    }
                                  ?>
                                  
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <label for="hEmail" class="col-xl-2 col-form-label">Required On</label>
                                <div class="col-md-10">
                                  <?php
                                    $dataChannel = $db_helper->allRecordsOrderBy('stm_channels','channelName ASC');
                     
                                    $db_task_store_data = [];
                                    
                                    foreach ($dataChannel as $channelList) {
                                        $dataStore = $db_helper->allRecordsRepeatedWhere('stm_stores','storeChannelID = "'.$channelList['id'].'" ');
                                      foreach ($dataStore as $storeList) {
                                    ?>
                                    <label class="new-control new-checkbox checkbox-outline-success new-checkbox-text">

                                    <input type="checkbox" name="storeID[]" class="new-control-input" value="<?php echo $storeList['id']; ?>"
                                    <?php 
                                    if(isset($_GET['session'])){

                                     $data_tasks = $db_helper->allRecordsRepeatedWhere('stm_task_channels','taskID = "'.$_GET['session'].'"');

                                     foreach ($data_tasks as $tasksData) {
                                        $db_task_store_data = $tasksData['StoreID']; 
                                        if($storeList['id'] == $db_task_store_data){echo "checked";}    
                                     }
                                     
                                    }
                                    ?>
                                    >
                                    <span class="new-control-indicator"></span><span class="new-chk-content">
                                      <?php echo $channelList['channelName'].'-'.$storeList['storeName']."&nbsp&nbsp"; ?>  
                                    </span>
                                    
                                    </label> 
                                  <?php
                                    }}
                                  ?>
                                  
                                </div>
                              </div>
                              <div class="form-group row" id="form-group">
                                <div class="col-md-2"></div>
                                <div class="col-md-8"></div>
                                <div class="col-md-2">
                                  <?php 
                                    if(!isset($_GET['session'])){
                                    ?>
                                    <input type="submit" name="addPrimaryTask" class="btn btn-success" value="Next" style="float:right;">
                                    <?php  
                                    }
                                  ?>
                                </div>
                              </div>
                              <?php
                                  $tb = "stm_statuses";
                                  $wher = "statusName = '1-New Task'";
                                 $dataStatus =  $db_helper->SingleDataWhere($tb, $wher);
                              ?>
                              <input type="hidden" name="status" value="<?php echo $dataStatus['id'] ?>">
                            </form>
                            <?php 
                              if(isset($_POST['addPrimaryTask'])){
                                if($_POST['taskName'] == ""){
                                ?>
                                <div class="alert alert-danger">
                                  <button class="close" data-dismiss="alert">&times;</button>
                                  <strong>Required!</strong> TaskName is required.
                                </div>
                              <?php
                                }else if($_POST['type'] == "none"){
                                ?>
                                <div class="alert alert-danger">
                                  <button class="close" data-dismiss="alert">&times;</button>
                                  <strong>Required!</strong> Category is required.
                                </div>
                              <?php
                                }else{
                                    
                                    $task_name = addslashes($_POST['taskName']);
                                    $task_desc = addslashes($_POST['description']);
                                    $taskCreationDate = date("Y-m-d");

                                    $priority = "";
                                    if($_POST['priority'] == '0'){
                                      $priod = $db_helper->SingleDataWhere('stm_priorities','taskpriorityName = "4-Normal"');

                                      $priority = $priod['id'];
                                    }else{
                                      $priority = $_POST['priority'];
                                    }
                                    $ourbrand = "";
                                    if(isset($_POST['ourBrand'])){
                                      $ourbrand = $_POST['ourBrand'];
                                    }else{
                                      $ourbrand = "0";
                                    }

                                    $TasklistingType = "";
                                    
                                    if(isset($_POST['TasklistingType'])){
                                      $TasklistingType = $_POST['TasklistingType'];
                                    }else{
                                      $TasklistingType = "0";
                                    }

                                    $query = $objUser->stm_addTask($task_name,$task_desc,$_POST['type'],$TasklistingType,$session_id,$priority,$_POST['brand'],$ourbrand,$_POST['supplier'],$_POST['status'],$taskCreationDate);

                                    $lastTaskID = $db_helper->lastID();
                                    if($lastTaskID){
                                      if(isset($_POST['storeID'])){
                                      for($i=0; $i<count($_POST['storeID']); $i++){
                                            $dataStore = $db_helper->SingleDataWhere('stm_stores','id = "'.$_POST['storeID'][$i].'" ');
                                            $objUser->stm_tasks_channel_data($lastTaskID,$dataStore['storeChannelID'],$_POST['storeID'][$i]);
                                              
                                        }
                                      }
                                      echo "<script>window.location='stmnewtask.php?session=".$lastTaskID."'</script>";  
                                    }else{
                                      ?>
                                      <div class="alert alert-danger">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong>Data not entered!</strong> Something went wrong.
                                      </div>
                                    <?php
                                    }
                                }
                              }
                              ?>
                          </div>  
                        </div><!---/row--->
                            <?php 
                            if(isset($_GET['session'])){
                            ?>
                            <div class="row">
                              <span style="font-weight: 550; font-size: 14px;">
                               REF URL / PRICES
                              </span>
                              
                              <table class="table table-striped table-bordered table-sm" id="ref_table">
                                <tr class="table_row">
                                    <td>REF.URL</td>
                                    <td>PROD CODE</td>
                                    <td>PURCHASE</td>
                                    <td>QUANTITY</td>
                                    <td>AMZ PRICE</td>
                                    <td>EBAY PRICE</td>
                                    <td>WEB PRICE</td>
                                    <td>STORE SKU</td>
                                    <td>LINK SKU</td>
                                    <td>EAN</td>
                                    <td>ASIN</td>
                                    <td id="attach">ATTACH </td>
                                    <td id="action">ACTION</td>
                                  </tr>  
                                  
                                <?php 
                                $folder = "images/";
                                $dData = $db_helper->allRecordsRepeatedWhere("stm_task_details","taskID = '".$_GET['session']."'");
                                foreach($dData as $detailsList){
                                ?>
                                <tr id="row_table_<?php echo $detailsList['id'] ?>"> 
                                    <td style="width:10%;">
                                        <input type="hidden" class="form-control detail_id_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['id'] ?>" style="display:none;"> 
                                        <input type="hidden" class="form-control task_ID" value="<?php echo $_GET['session']; ?>" style="display:none;"> 
                                        <input type="text" value="<?php echo $detailsList['refURL'] ?>" class="form-control form-control-sm ref_url_<?php echo $detailsList['id'] ?>"disabled>
                                    </td>
                                    <td style="width:7%;">
                                        <input type="text" class="form-control form-control-sm productCode_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['productCode'] ?>" style=" font-size: 12px;" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm purchasePrice_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['purchasePrice'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['quantity'] ?>" class="form-control form-control-sm quantity_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['amzPrice'] ?>" class="form-control form-control-sm amzPrice_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['ebayPrice'] ?>" class="form-control form-control-sm ebayPrice_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm webPrice_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['webPrice'] ?>" disabled>
                                    </td>
                                    
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['storeSKU'] ?>" class="form-control form-control-sm storeSKU_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['linkedSKU'] ?>" class="form-control form-control-sm  linkedSKU_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['EAN'] ?>" class="form-control form-control-sm EAN_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $detailsList['ASIN'] ?>" class="form-control form-control-sm ASIN_<?php echo $detailsList['id'] ?>" disabled>
                                    </td>
                                    <!-- <td>
                                        <input type="text" value="<?php echo $detailsList['ASIN'] ?>" class="form-control form-control-sm ASIN_<?php echo $detailsList['id'] ?>" onkeyup="myFunction3(<?php echo $detailsList['id'] ?>)">
                                    </td> -->
                                    
                                    <td class="file_row">
                                       
                                       <input type="hidden" class="old_file_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['attachement'] ?>">
                                        <input type="file" id="update_file_<?php echo $detailsList['id'] ?>" class="update_file" style="float:left;" disabled>
                                        <div class='image_<?php echo $detailsList['id']; ?>'>
                                        <?php 
                                        if($detailsList['attachement']){
                                          $extension = pathinfo($detailsList['attachement'], PATHINFO_EXTENSION);
                                          $imgExtArr = ['jpg', 'jpeg', 'png','svg','webp'];
                                          if(in_array($extension, $imgExtArr)){
                                          ?>
                                          
                                            <img class='file_show_<?php echo $detailsList['id']; ?>' src="images/<?php echo $detailsList['attachement']; ?>">
                                          
                                          <?php
                                          }else{
                                            echo "<span class='file_show_".$detailsList['id']."'>".$detailsList['attachement']."</span>";
                                          }
                                          
                                          
                                        }
                                        ?>
                                          </div>      
                                    </td>
                                    
                                    <td id="Db_td">
                                      <svg class="edit_detail_ref" style="color:blue;" id="clone_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                      
                                      <button class="btn btn-success update_detail" data-id="<?php echo $detailsList['id'] ?>" type="button" style="display:none;" id="update_detail_<?php echo $detailsList['id'] ?>">Save</button>
                                    
                                      
                                      <svg class="rem_detail" style="color:red;" id="clone_rem_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                      
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr> 
                                    <td style="width:10%;">
                                        <input type="text" class="detail_id" style="display:none;">  
                                        <input type="text" class="form-control form-control-sm ref_url" id="ref_url" onkeyup="myFunction()" style="font-size: 12px;">
                                    </td>
                                    <td style="width:7%;">
                                        <input type="text" class="form-control form-control-sm productCode" id="productCode" onkeyup="myFunction()" style="font-size: 12px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm purchasePrice" id="purchasePrice" onkeyup="myFunction()">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm quantity" id="quantity" onkeyup="myFunction()">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm amzPrice" id="amzPrice" onkeyup="myFunction()">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm ebayPrice" id="ebayPrice">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm webPrice" id="webPrice">
                                    </td>
                                    
                                    <td>
                                        <input type="text" class="form-control form-control-sm storeSKU" id="storeSKU">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm linkedSKU" id="linkedSKU">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm EAN" id="EAN" onkeyup="myFunction()">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm ASIN" id="ASIN" onkeyup="myFunction()">
                                    </td>
                                    
                                    <td class="file_row">
                                        <input type="hidden" class="old_file">
                                        <input type="file" id="file"><span style="color:red; font-size: 10px;">(Max 5MB)</span>
                                    </td>
                                    <td class="first_td" id="Db_td" >
                                      <button class="btn btn-success add_task_detail" data-id="<?php echo $_GET['session'] ?>" type="button" id="clone_row" style="display:none;">Save</button>
                                    </td>
                                </tr> 
                                 
                              </table>
                            </div><!-- /row---> 
                          
                            <div class="row-fluid">
                              <button type="button" class="btn btn-primary btn-sm" id="saveANDclose" style="float:right;" data-id="<?php echo $_GET['session']; ?>">
                                Save and Close
                              </button>
                              
                            </div>
                            <?php  
                            }//session isset
                            ?>
                      </div><!---/widget-content--->
                    </div><!---/statbox widget--->
                  </div>
                </div><!---layout-px-spacing-->

        <?php
          include_once "partials/footer.php";
        }else{  
          echo "<script>window.location='signin.php'</script>";
        }
        ?>