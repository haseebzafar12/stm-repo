<?php ob_start();
session_start();
include('smtp/PHPMailerAutoload.php');
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
      
    $id = $_GET['id'];
    $tbl = "stm_tasks";
    $whe = "id = '$id'";
    $dataTask = $db_helper->SingleDataWhere($tbl, $whe);

    $tblType = "stm_tasktypes";
    $wheType = "id = '".$dataTask['taskTypeID']."'";
    $type = $db_helper->SingleDataWhere($tblType, $wheType);

    $use = "stm_users";
    $wheuse = "id = '".$dataTask['taskAssignedBy']."'";
    $dataUse = $db_helper->SingleDataWhere($use, $wheuse);

    // $useS = "stm_users";
    // $wheuseS = "id = '".$dataTask['taskSupervisorID']."'";
    // $dataUseS = $db_helper->SingleDataWhere($useS, $wheuseS);

    $proio = "stm_priorities";
    $wheproio = "id = '".$dataTask['taskPriorityID']."'";
    $datawheproio = $db_helper->SingleDataWhere($proio, $wheproio);

    $status = "stm_statuses";
    $statuspio = "id = '".$dataTask['taskStatusID']."'";
    $datastatuspio = $db_helper->SingleDataWhere($status, $statuspio);

    $br = "stm_brands";
    $brw = "id = '".$dataTask['taskBrandID']."'";
    $brands = $db_helper->SingleDataWhere($br, $brw);

    $sp = "stm_supplier";
    $spw = "id = '".$dataTask['taskSupplierID']."'";
    $suppliers = $db_helper->SingleDataWhere($sp, $spw);

    $creationDate = date("d M Y", strtotime($dataTask["taskCreationDate"]));
    //$deadlineDate = date("d M Y", strtotime($dataTask["taskDeadline"]));

    $tb = "stm_statuses";
    $wher = "id = '".$dataTask['taskStatusID']."'";;
    $dataStatus =  $db_helper->SingleDataWhere($tb, $wher);
    $creationDate = date("d M Y", strtotime($dataTask["taskCreationDate"]));
    //$deadlineDate = date("d M Y", strtotime($dataTask["taskDeadline"]));
                                  
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
                <div class="row">
                   <div class="col-md-12">
                     <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area simple-tab">
                            <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">BASIC INFO</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="listing-tab" data-toggle="tab" href="#preListing" role="tab" aria-controls="contact" aria-selected="false">PRE-LISTING INFO</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="assignees-tab" data-toggle="tab" href="#assignees" role="tab" aria-controls="contact" aria-selected="false">ASSIGNEES</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="content-tab" data-toggle="tab" href="#listingContent" role="tab" aria-controls="contact" aria-selected="false">CONTENT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="contact" aria-selected="false">MESSAGES</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="simpletabContent">
                                
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">       
                                  <form method="post">
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Task ID</label>
                                      <div class="col-md-10">
                                        <input type="text" id="input_field" class="form-control form-control-sm" value="<?php echo $dataTask['id']; ?>" readonly>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Category</label>
                                      <div class="col-md-10">
                                        <select name="type" class="form-control" id="select_field">
                                            <option value="none">Select</option>
                                            <?php
                                              $tbl = "stm_tasktypes";
                                              $userTypes = $db_helper->allRecordsOrderBy($tbl,'tasktypeName ASC');
                                              foreach($userTypes as $list){
                                            ?>
                                            <option value="<?php echo $list['id']; ?>"<?php

                                            if($type['id'] == $list['id']){
                                            echo "selected = 'selected'";
                                            }?>><?php echo $list['tasktypeName']; ?></option>
                                            <?php    
                                              }
                                            ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Task Name</label>
                                      <div class="col-md-10">
                                        <input type="text" class="form-control" name="taskName" id="input_field" value="<?php
                                        if(isset($_GET['id'])){
                                        echo $dataTask['taskName'];
                                        }?>">
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Description</label>
                                      <div class="col-md-10">
                                        <textarea rows="7" class="form-control" name="description" id="input_field"><?php if(isset($_GET['id'])){
                                            echo $dataTask['taskDescription'];
                                        }?></textarea>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Supplier</label>
                                      <div class="col-md-10">
                                          <select name="supplier" class="form-control supplier" id="select_field">
                                            <option value="0">Select</option>
                                            <?php
                                              $tbl = "stm_supplier";
                                              $userTypes = $db_helper->allRecordsOrderBy("stm_supplier","supplierName ASC");
                                              foreach($userTypes as $list){
                                            ?>
                                              <option value="<?php echo $list['id']; ?>"<?php
                                                   if($suppliers['id'] == $list['id']){
                                                      echo "selected = 'selected'";
                                                   }
                                               ?>>
                                                  <?php echo $list['supplierName']; ?>
                                              </option>
                                              <?php    
                                                }
                                              ?>
                                          </select>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Suppliers Brand</label>
                                      <div class="col-md-10">
                                          <select name="brand" class="form-control brands" id="select_field">
                                            <?php 
                                            if($brands){
                                            ?>
                                            <option value="<?php echo $brands['id']; ?>"><?php echo $brands['brandName']; ?>
                                              </option>
                                            <?php  
                                            }else{
                                            ?>
                                            <option value="0">Select Brand</option>
                                            <?php
                                            }
                                            ?>
                                              
                                          </select> 
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Priority</label>
                                      <div class="col-md-10">
                                        <select name="priority" class="form-control" id="select_field">
                                          <option value="0">Select</option>
                                          <?php
                                          $userTypes = $db_helper->allRecordsOrderBy('stm_priorities','taskpriorityName ASC');
                                            foreach($userTypes as $list){
                                          ?>
                                           <option value="<?php echo $list['id']; ?>"<?php
                                           if($datawheproio['id'] == $list['id']){
                                              echo "selected = 'selected'";
                                           }
                                          ?>><?php echo $list['taskpriorityName']; ?>
                                            </option>
                                          <?php    
                                          }
                                          ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Our Brand</label>
                                      <div class="col-md-10">
                                        <?php
                                          $ourbrands = $db_helper->allRecords('stm_ourbrands');
                                          foreach ($ourbrands as $ourbrandsList) {
                                        ?>
                                          <label class="new-control new-radio new-radio-text radio-success">
                                          <input type="radio" name="ourBrand" class="new-control-input" value="<?php echo $ourbrandsList['id']; ?>"
                                          <?php 
                                          if(isset($_GET['id'])){
                                            if($dataTask['taskOurBrandID'] == $ourbrandsList['id']){
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
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Requested By</label>
                                      <div class="col-md-10">
                                        <select name="owner" class="form-control" id="select_field">
                                          <option value="none">Select</option>
                                          <?php
                                           
                                            $userTypes = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                            foreach($userTypes as $list){
                                          ?>
                                          <option value="<?php echo $list['id']; ?>" <?php
                                          if($dataUse['id'] == $list['id']){
                                          echo "selected = 'selected'";
                                          }?>><?php echo $list['userName']; ?></option>
                                          <?php    
                                            }
                                          ?>
                                        </select>
                                      </div>
                                    </div>
                                    
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Listing Type</label>
                                      <div class="col-md-10">
                                        <?php
                                          $listingtype = $db_helper->allRecordsRepeatedWhere('stm_listingtype','listingTypeName = "Single" OR listingTypeName = "Variation" ');
                                          foreach($listingtype as $listingtypeList){
                                        ?>
                                          <label class="new-control new-radio new-radio-text radio-success">

                                          <input type="radio" name="TasklistingType" class="new-control-input" value="<?php echo $listingtypeList['id']; ?>"
                                            <?php 
                                            if(isset($_GET['id'])){
                                              if($dataTask['taskListingTypeID'] == $listingtypeList['id']){
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
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Skype Group</label>
                                      <div class="col-md-10">
                                        <input type="text" class="form-control basic" maxlength="20" name="taskSkypeGroup" id="input_field" value = "<?php echo $dataTask['taskSkypeGroup']; ?>">
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label for="hEmail" class="col-xl-2 col-form-label">Status</label>
                                      <div class="col-md-10">
                                        <input type="text" id="input_field" class="form-control form-control-sm" value = "<?php echo $dataStatus['statusName']; ?>" readonly>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
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
                                          if(isset($_GET['id'])){

                                           $data_tasks = $db_helper->allRecordsRepeatedWhere('stm_task_channels','taskID = "'.$_GET['id'].'"');

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
                                    <div class="row float-right">
                                      <div class="col-md-12">
                                        <input type="submit" name="editTask" class="btn btn-primary" value="Save Changes">  
                                      </div>
                                      
                                    </div> 
                                  </form>
                                  <?php 
                                    if(isset($_POST['editTask'])){

                                       // $taskDeadline = date("Y-m-d", strtotime($_POST["deadline"]));
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
                                         $query = $objUser->task_edit(
                                          addslashes($_POST['taskName']),
                                          addslashes($_POST['description']),
                                          $_POST['type'],
                                          $TasklistingType,
                                          $_POST['owner'],
                                          $_POST['priority'],
                                          $_POST['brand'],
                                          $ourbrand,
                                          $_POST['supplier'],
                                          addslashes($_POST['taskSkypeGroup']),$_GET['id']);
                                        
                                          $objUser->stm_rem_task_channels($_GET['id']);
                                          if(isset($_POST['storeID'])){
                                           for($i=0; $i<count($_POST['storeID']); $i++){

                                                  $dataStore = $db_helper->SingleDataWhere('stm_stores','id = "'.$_POST['storeID'][$i].'" ');

                                                  $objUser->stm_tasks_channel_data($_GET['id'],$dataStore['storeChannelID'],$_POST['storeID'][$i]);
                                            }
                                          }
                                          echo "<script>window.location.href='stmtaskedit.php?id=".$_GET['id']."'</script>";
                                      }
                                  ?>
                                </div><!----/home---->

                                <div class="tab-pane fade" id="preListing" role="tabpanel" aria-labelledby="profile-tab">
                                  <br>
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
                                      $dData = $db_helper->allRecordsRepeatedWhere("stm_task_details","taskID = '".$_GET['id']."'");
                                      foreach($dData as $detailsList){
                                      ?>
                                      <tr id="row_table_<?php echo $detailsList['id'] ?>"> 
                                          <td style="width:10%;">
                                              <input type="hidden" class="form-control detail_id_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['id'] ?>" style="display:none;"> 
                                              <input type="hidden" class="form-control task_ID" value="<?php echo $_GET['id']; ?>" style="display:none;"> 
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
                                            <svg class="edit_detail_ref" style="color:#04AA6D;" id="clone_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            
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
                                            <button class="btn btn-success add_task_detail" data-id="<?php echo $_GET['id'] ?>" type="button" id="clone_row" style="display:none;">Save</button>
                                          </td>
                                      </tr> 
                                       
                                    </table>
                                  </div><!-- /row---> 
                                  <br> 
                                  <div class="row">
                                    <span style="font-weight: 550; font-size: 14px;">
                                     PRE-LISTING
                                    </span>
                                    <div class="table-responsive">
                                      <table class="table table-sm table-striped table-bordered" id="ref_table">
                                     
                                        <tr class="table_row">
                                          <td>REF.URL</td>
                                          <td>P CODE</td>
                                          <td>P PRICE</td>
                                          <td>QUANTITY</td>
                                          <td>CHANNEL</td>
                                          <td>STORE</td>
                                          <td>SALE PRICE</td>
                                          <td>STORE SKU</td>
                                          <td>LINK SKU</td>
                                          <td>EAN</td>
                                          <td>ASIN</td>
                                          <td>TYPE</td>
                                           <?php if(isset($_GET['sub'])){?>
                                          <td id="action">Action</td>
                                          <?php }?>
                                        </tr>  
                                      
                                        <?php 
                                        $folder = "images/";
                                        $dData = $db_helper->allRecordsRepeatedWhere("stm_prelistings","taskID = '".$_GET['id']."'");
                                        foreach($dData as $detailsList){
                                          $ref_url_listing = strip_tags($detailsList['refURL']);
                                        if (strlen($ref_url_listing) > 35) {
                                            // truncate string
                                            $stringCut = substr($ref_url_listing, 0, 35);
                                            $endPoint = strrpos($stringCut, ' ');

                                            $ref_url_listing = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            
                                        }
                                        ?>
                                        <tr id="row_table_<?php echo $detailsList['id'] ?>">
                                          <td><a class="anchor" href="<?php echo $detailsList['refURL'] ?>" target="_blank"><?php echo $ref_url_listing; ?></a></td>
                                            <td><?php echo $detailsList['productCode'] ?></td>
                                            <td><?php echo $detailsList['purchasePrice'] ?></td>
                                            <td><?php echo $detailsList['quantity'] ?></td>
                                            <td>
                                                <?php
                                                  $channelID = $detailsList['channelID'];
                                                  $channelList = $db_helper->SingleDataWhere("stm_channels","id = '$channelID'");
                                                  echo $channelList['channelName'];
                                                ?>
                                            </td>
                                            <td>
                                              <?php
                                                  $storeID = $detailsList['storeID'];
                                                  $storeList = $db_helper->SingleDataWhere("stm_stores","id = '$storeID'");
                                                  echo $storeList['storeName'];
                                                ?>
                                            </td>
                                            <td><?php echo $detailsList['salePrice'] ?></td>
                                            <td><?php echo $detailsList['storeSKU'] ?></td>
                                            <td><?php echo $detailsList['linkedSKU'] ?></td>
                                            <td><?php echo $detailsList['EAN'] ?></td>
                                            <td><?php echo $detailsList['ASIN'] ?></td>
                                            <td>
                                                <?php
                                                  $listingTypeID = $detailsList['listingTypeID'];
                                                  $listType = $db_helper->SingleDataWhere("stm_listingtype","id = '$listingTypeID'");
                                                ?>
                                                <span <?php 
                                                if($detailsList['listingTypeID'] == "1"){
                                                  echo "style='background-color:#9999ff; color:white; padding:5px;'";
                                                }else if($detailsList['listingTypeID'] == "3"){
                                                  echo "style='background-color:#79d2a6; padding:5px;'";
                                                }else if($detailsList['listingTypeID'] == "2"){
                                                  echo "style='background-color: #8cb3d9; color:white;padding:5px;'";
                                                }
                                                ?>>
                                                  <?php echo $listType['listingTypeName']; ?>
                                                </span>
                                            </td> 
                                        </tr>    
                                        <?php } ?> 
                                           
                                      </table>
                                    </div>
                                  </div><!-- /row---> 
                                </div><!----/preListing--->

                                <div class="tab-pane fade" id="assignees" role="tabpanel" aria-labelledby="profile-tab2">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-sm" id="ref_table">
                                      <tr class="assig_table_row table_row">
                                          <td>ASSIGNEE</td>
                                          <td>SUB TASK</td>
                                          <td>CHANNEL</td>
                                          <td>STORE</td>
                                          <td>DEADLINE</td>
                                          <td>DESCRIPTION</td>
                                          <th>REVIEWER</th>
                                          <td>ACTION</td>
                                      </tr>
                                      <div class="assig_tables">
                                      <?php 

                                      $assgnees = $db_helper->allRecordsRepeatedWhere('stm_taskassigned','taskID = "'.$_GET['id'].'"');

                                      foreach($assgnees as $assigneeData){
                                      ?>
                                      
                                      <tr class="assigne_data_<?php echo $assigneeData['id']; ?>"> 
                                          <td>
                                              <input type="hidden" class="form-control task_id" value="<?php echo $_GET['id']; ?>">
                                              <select name="owner" class="form-control owner_<?php echo $assigneeData['id']; ?>" id="owner" disabled>
                                                  <option value="none">Select</option>
                                                  <?php
  $userTypes = $db_helper->allRecordsWhereOrderBy('stm_users',"isActive = '1' Order by userName ASC");
                                                    foreach($userTypes as $list){
                                                  ?>
                                                  <option value="<?php echo $list['id']; ?>" <?php
                                                  if($assigneeData['taskuserID'] == $list['id']){echo "selected = 'selected'";}
                                                  ?>><?php echo $list['userName']; ?></option>
                                                  <?php    
                                                    }
                                                  ?>
                                              </select>
                                          </td>
                                          
                                          <td>
                                            <select name="subtask" class="form-control subtask_<?php echo $assigneeData['id']; ?>" disabled>
                                             <option value="">Select SubTask</option>
                                                <?php
                                                  $userTypes = $db_helper->allRecordsOrderBy('stm_subtask','subTask ASC');
                                                  foreach($userTypes as $list){
                                                ?>
                                                <option value="<?php echo $list['id']; ?>" <?php
                                                  if($assigneeData['subTaskID'] == $list['id']){echo "selected = 'selected'";}
                                                  ?>><?php echo $list['subTask']; ?></option>
                                                <?php    
                                                  }
                                                ?>
                                            </select>
                                          </td>
                                          <td>
                                            <select name="channels" class="form-control channels_<?php echo $assigneeData['id']; ?>" id="channel_new" data-id="<?php echo $assigneeData['id']; ?>" disabled>
                                               <option value="0">Select</option>
                                                <?php
                                                 
                                                  $userTypes = $db_helper->allRecordsOrderBy('stm_channels','channelName ASC');
                                                  foreach($userTypes as $list){
                                                ?>
                                                <option value="<?php echo $list['id']; ?>" <?php
                                                  if($assigneeData['taskchannelID'] == $list['id']){echo "selected = 'selected'";}
                                                  ?>><?php echo $list['channelName']; ?></option>
                                                <?php    
                                                  }
                                                ?>
                                            </select>
                                          </td>
                                          <td>
                                              <select name="stores" class="form-control stores_<?php echo $assigneeData['id']; ?>" id="store_new" style="width:100%" disabled>
                                                <?php 
                                                if($assigneeData['taskstoreID']){
                                                ?>
                                                <option value="<?php echo $assigneeData['taskstoreID']; ?>">
                                                  <?php 
                                                  $dataStore = $db_helper->SingleDataWhere('stm_stores','id = "'.$assigneeData['taskstoreID'].'"');
                                                  echo $dataStore['storeName'];
                                                  ?>
                                                </option>
                                                <?php
                                                }else{
                                                  ?><option value="0">Select Store</option>
                                                  <?php
                                                }
                                                ?>
                                              </select>
                                          </td>
                                          <td>
                                            <input class="form-control" type="date" id="subDeadline_<?php echo $assigneeData['id']; ?>" name="date" value="<?php echo strftime('%Y-%m-%d',strtotime($assigneeData['taskDeadline'])); ?>" style="width:92%" disabled>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control form-control-sm" id="des_new_aasignee_<?php echo $assigneeData['id']; ?>" value="<?php echo $assigneeData["subTaskDescription"] ?>" disabled>
                                          </td>
                                          <td>
                                              <select class="form-control supervisor_<?php echo $assigneeData['id']; ?>" id="supervisor" disabled>
                                                  <option value="none">Select</option>
                                                  <?php
                                                    $userTypes = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                                    foreach($userTypes as $list){
                                                  ?>
                                                  <option value="<?php echo $list['id']; ?>" <?php
                                                  if($assigneeData['taskSupervisorID'] == $list['id']){echo "selected = 'selected'";}
                                                  ?>><?php echo $list['userName']; ?></option>
                                                  <?php    
                                                    }
                                                  ?>
                                              </select>
                                          </td>
                                          <td class="first_td"  style="width:8%;">
                                            <svg class="save_task" style="color:#04AA6D;"data-id="<?php echo $assigneeData['id']; ?>" id="save_task_<?php echo $assigneeData['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            
                                            <button type="button" class="btn btn-success savesubTask" id="update_clone_<?php echo $assigneeData['id']; ?>" data-id="<?php echo $assigneeData['id']; ?>" style="display: none;">Save</button>
                                            
                                            <a title="Copy" id="copy_<?php echo $assigneeData['id']; ?>" class="copyAssignee" data-id="<?php echo $assigneeData['id']; ?>">
                                             <svg style="color:#805dca" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> 
                                            </a>
                                            
                                            <svg class="remsubTask" style="color:red;" id="rem_clone_<?php echo $assigneeData['id']; ?>" data-id="<?php echo $assigneeData['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            
                                          </td>
                                      </tr> 
                                      <?php
                                      }
                                      ?>
                                      </div>
                                      <tr> 
                                          <td>
                                            <input type="hidden" class="task_id" value="<?php echo $_GET['id']; ?>">
                                            
                                            <input type="hidden" id="current_date" value="<?php echo date("Y-m-d"); ?>">
                                              <select name="owner" class="form-control owner" id="owner" style="width:100%">
                                                  <option value="">Select</option>
                                                  <?php
  
  $userTypes = $db_helper->allRecordsRepeatedWhere('stm_users',"isActive = '1' order by userName ASC");
                                                    foreach($userTypes as $list){
                                                  ?>
                                                  <option value="<?php echo $list['id']; ?>"><?php echo $list['userName']; ?></option>
                                                  <?php    
                                                    }
                                                  ?>
                                              </select>
                                          </td>
                                          
                                          <td>
                                            <select name="subtask" class="form-control subtask" id="subtask" style="width:100%">
                                             <option value="">Select SubTask</option>
                                                <?php
                                                  $userTypes = $db_helper->allRecordsOrderBy('stm_subtask','subTask ASC');
                                                  foreach($userTypes as $list){
                                                ?>
                                                <option value="<?php echo $list['id']; ?>"><?php echo $list['subTask']; ?></option>
                                                <?php    
                                                  }
                                                ?>
                                            </select>
                                          </td>
                                          <td>
                                            <select name="channels" class="form-control channels" id="channel_assign" style="width:100%">
                                               <option value="0">Select</option>
                                                <?php
                                                 
                                                  $userTypes = $db_helper->allRecordsOrderBy('stm_channels','channelName ASC');
                                                  foreach($userTypes as $list){
                                                ?>
                                                <option value="<?php echo $list['id']; ?>"><?php echo $list['channelName']; ?></option>
                                                <?php    
                                                  }
                                                ?>
                                            </select>
                                          </td>
                                          <td>
                                              <select name="stores" class="form-control stores" id="store_assign" style="width:100%"><option value="0">Select</option></select>
                                          </td>
                                          <td>
                                            <input class="form-control" type="date" id="subDeadline" name="date" style="width:92.2%">
                                          </td>
                                          <td>
                                            <input type="text" class="form-control" id="des_new_aasignee">
                                          </td>
                                          <td>
                                            <select name="supervisor" class="form-control supervisor" id="supervisor" style="width:100%">
                                                <option value="">Select</option>
                                                <?php
                                                  $userTypes = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                                  foreach($userTypes as $list){
                                                ?>
                                                <option value="<?php echo $list['id']; ?>"><?php echo $list['userName']; ?></option>
                                                <?php    
                                                  }
                                                ?>
                                            </select>
                                          </td>
                                          <td class="first_td">
                                              <button type="button" class="btn btn-success newsubTask" id="newsubTask" style="display:none;">Save</button>
                                          </td>
                                      </tr>    
                                    </table>
                                    <div class="error"></div>
                                  </div><!---/table responsive---->    
                                </div><!----/assignees---->

                                <div class="tab-pane fade" id="listingContent" role="tabpanel" aria-labelledby="contact-tab">
                                  <!-- <div id="editor-container"> -->
                                    <?php 
                                    if(isset($_GET['sub'])){
                                    ?>
                                      <div id="editor" style="height:400px;"><?php echo $dataTask['taskContent']; ?></div>
                                      <input type="hidden" class="taskID" value="<?php echo $_GET['id']; ?>">
                                    <?php  
                                    }else{
                                    ?>
                                      <div id="editor-readonly" style="height:400px;"><?php echo $dataTask['taskContent']; ?></div>
                                    <?php  
                                    }
                                    ?>
                                      
                                </div>    
                                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="contact-tab">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <input type="hidden" class="userID" value="<?php echo $session_id; ?>">
                                      <input type="hidden" class="taskID" value="<?php echo $_GET['id'] ?>">
                                      <div class="form-group">
                                        <label>Recipient</label>
                                        <?php
                                        $task_users = $db_helper->SingleDataWhere('stm_tasks','id = "'.$_GET['id'].'"');

                                        $createdUser = $db_helper->SingleDataWhere('stm_users','id = "'.$task_users['taskAssignedBy'].'"');
                                        $dataSuper = $db_helper->singleRecordwithDistict('stm_taskassigned','taskID = "'.$_GET['id'].'"');
                                        $dataSuperUser = $db_helper->SingleDataWhere('stm_users','id = "'.$dataSuper['taskSupervisorID'].'"');
                                        ?>
                                        <select class="form-control assignedTo" style="width:100%;">
                                        <option>Select User</option>
                                        
                                        <option value="<?php echo $dataSuperUser['id']; ?>"><?php echo $dataSuperUser['userName']; ?></option>
                                        <option value="<?php echo $createdUser['id']; ?>"><?php echo $createdUser['userName']; ?></option>
                                        <option>---</option>
                                        <?php 
                                        $task_assignees = $db_helper->onlyDISTINCTRecords('stm_taskassigned','taskID = "'.$_GET['id'].'"');
                                        foreach ($task_assignees as $task_assignees_list) {
                                          $list_of_assignees_users = $db_helper->allRecordsRepeatedWhere('stm_users','id = "'.$task_assignees_list['taskuserID'].'"');
                                          foreach($list_of_assignees_users as $assigness){
                                        ?>
                                        <option value="<?php echo $assigness['id']; ?>"><?php echo $assigness['userName']; ?></option>
                                        <?php
                                        }}
                                        ?> 
                                        </select>
                                      </div>
                                      <div class="form-group">
                                        <label>Type your message</label>
                                        <textarea class="form-control chat_message"></textarea>  
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-10">
                                      <button type="button" class="btn btn-success postMessage" style="float:left;">POST</button>
                                    </div>
                                  </div>
                                  <br>
                                  <div class="message-body">
                                    <?php 
                                    
                                    $messages = $db_helper->allRecordsRepeatedWhere("stm_messages","taskID = '".$_GET['id']."' ORDER BY id DESC");

                                    foreach ($messages as $message_row) {

                                      $message_detail = $db_helper->allRecordsRepeatedWhere("stm_message_details","messageID = '".$message_row['id']."' ORDER BY id DESC");

                                      foreach ($message_detail as $message_details) {
                                        
                                      $createdBy = $db_helper->SingleDataWhere("stm_users","id = '".$message_details['msgFrom']."'");

                                      $assignedTo = $db_helper->SingleDataWhere("stm_users","id = '".$message_details['msgTo']."'");
                                    ?>
                                    <div class="row">
                                      <div class="col-md-10">
                                        <div class="row">
                                          <div class="col-md-6">
                                            <p><b><?php echo date('d/m/Y H:i:s', strtotime($message_details['createdOn']))."&nbsp&nbsp"?>
                                              From: <?php echo $createdBy['userName']."<br>"; ?>
                                              To: <?php echo $assignedTo['userName']; ?></b>
                                            </p>           
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-10">
                                              <?php 
                                                echo $message_details['message'];
                                              ?>
                                          </div>
                                        </div>
                                        <hr>
                                      </div>
                                    </div>
                                    <?php
                                    }}
                                    ?>
                                    
                                  </div>
                                  
                                </div>

                            </div>
                        </div>
                      </div>
                   </div><!---/row---->
                   
                   <div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                     <div class="modal-dialog" role="document">
                       <div class="modal-content">
                           <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Task Related Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>

                            </div>
                            <div class="modal-body">
                                
                                <input type="hidden" class="subID" value="<?php echo $_GET['sub']; ?>">
                                <input type="hidden" class="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    
                                    <label>URL</label>
                                    <input type="text" class="form-control URL">
                                    
                                </div>
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea class="comments form-control"></textarea>
                                </div>
                                <div class="error"></div>
                            </div>
                            <div class="modal-footer">
                              <a data-dismiss="modal" class="btn" href="#">Cancel</a>
                                
                                <button type="button" class="btn btn-success saveInfo">Update</button>
                            </div>
                       </div> 
                     </div>
                   </div>

                   <div class="modal fade" id="viewinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                     <div class="modal-dialog" role="document">
                       <div class="modal-content">
                           <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Task Related Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>

                            </div>
                            <div class="modal-body">
                                
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea class="comments form-control"></textarea>
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                              <a data-dismiss="modal" class="btn" href="#">Cancel</a>
                               
                            </div>
                       </div> 
                     </div>
                   </div>

                </div><!---/row---->
            </div><!---layout-px-spacing-->

        <?php
          include_once "partials/footer.php";
        }else{  
          echo "<script>window.location='signin.php'</script>";
        }
        ?>