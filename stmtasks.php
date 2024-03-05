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
     <div class="main-container sidebar-closed sbar-open" id="container">

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
                
            <div class="col-lg-12 col-12">
                <div class="statbox widget box box-shadow">
                    <!-- <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Simple Pills</h4>
                            </div>
                        </div>
                    </div> -->
                    <div class="widget-content widget-content-area simple-pills">
                        
                        <?php 
                        if(isset($_GET['opentask'])){
                        ?>
                        <ul class="nav nav-tabs mb-3" id="simpletab" role="tablist">
                            <?php 
                            if(isset($_SESSION['id'])){
                            ?>
                            <li class="nav-item active">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#alltask" role="tab" aria-controls="pills-home" aria-selected="true">OPEN TASKS</a>
                            </li>
                            <?php } ?>
                            
                            <li class="nav-item" <?php  if(isset($_SESSION['user'])){echo "class='active'";}?>>
                                <a class="nav-link <?php  if(isset($_SESSION['user'])){echo 'active';}?>" id="pills-contact-tab" data-toggle="pill" href="#assignedTo" role="tab" aria-controls="pills-contact" aria-selected="false">ASSIGNED TO ME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#assignedBy" role="tab" aria-controls="pills-contact" aria-selected="false">ASSIGNED BY ME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#formyReview" role="tab" aria-controls="pills-contact" aria-selected="false">FOR MY REVIEW</a>
                            </li>
                            <div class="buttons">
                             <a href="stmnewtask.php" class="btn btn-success btn-sm">ADD NEW TASK
                             </a>
                             <a class="btn btn-warning btn-sm" data-target="#exportModal" data-toggle="modal">EXPORT</a>&nbsp
                             
                            </div>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show <?php if(isset($_SESSION['id'])){echo 'active'; } ?>" id="alltask" role="tabpanel" aria-labelledby="pills-home-tab">
                               <div class="row mb-2">
                                    
                                    <div class="col-md-10" >
                                        <div class="row" >
                                            <div class="col-md-12" >
                                               <select class="form-control select_filter" style="height:30px; padding: 2px;">
                                                    <option value="All">Filter by</option>
                                                    <option value="Category">Category</option>
                                                    <option value="Created By">Created By</option>
                                                    <option value="Assignees">Assignees</option>
                                                    <option value="Skype">Skype</option>
                                                    <option value="Priority">Priority</option>
                                                </select> 
                                            
                                                <select class="form-control filter_task" style="display:none;" id="category">
                                                    <option value="">Select Category</option>
                                                    <?php
                                                        $dataCate = $db_helper->allRecordsOrderBy("stm_tasktypes",'tasktypeName ASC');
                                            
                                                        foreach($dataCate as $categoryData){
                                                     ?>
                                                    <option value="<?php echo $categoryData['id']; ?>">
                                                        <?php echo $categoryData['tasktypeName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control filter_task" style="display:none;" id="priority">
                                                    <option value="">Priority</option>
                                                    <?php
                                                        $priority = $db_helper->allRecordsOrderBy("stm_priorities",'taskpriorityName ASC');
                                            
                                                        foreach($priority as $priorities){
                                                     ?>
                                                    <option value="<?php echo $priorities['id']; ?>">
                                                        <?php echo $priorities['taskpriorityName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>

                                                <select class="form-control filter_task" id="created_by" style="display:none;">
                                                    <option value="">Select user</option>
                                                    <?php
                                                        $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                            
                                                        foreach($user as $allusers){
                                                     ?>
                                                    <option value="<?php echo $allusers['id']; ?>">
                                                        <?php echo $allusers['userName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control filter_task" id="assignees_filter" style="display:none;">
                                                    <option value="">Select user</option>
                                                    <?php
                                                        $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                            
                                                        foreach($user as $allusers){
                                                     ?>
                                                    <option value="<?php echo $allusers['id']; ?>">
                                                        <?php echo $allusers['userName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="text" class="filter_task form-control" id="skype" style="display:none;">
                                            
                                                <select class="form-control filter_task" style="display:none;" id="status">
                                                    <option value="">Select Status</option>
                                                    <option value="open">Open Tasks</option>
                                                    <option value="closed">Closed Tasks</option>
                                                </select>
                                                <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From Date ..." style="display:none; height: 35px;">
                                                <input id="toFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To Date ..." style="display:none; height: 35px;"> 
                                            
                                                <button style="margin-bottom:10px; display: none; float: left; margin-left: 3px;" type="button" class="btn btn-info btn-sm" id="filterBy">Filter</button>
                                                <a href="stmtasks.php?opentask" style="margin-bottom:10px; float: left; display: none;" class="btn btn-warning btn-sm" id="reset">Reset</a>
                                            </div>
                                        </div>                                    
                                    </div><!---col-md-10--->   
                                    
                                    <div class="col-md-2">
                                    <input type="text" name="search_box" id="tasks_search_box" class="form-control" placeholder="Type Search" style="height:35px !important">
                                    </div><!----col-md-2--->
                                </div>
                               <div class="tasksTable"></div>
                            </div><!----all task close--->
                            
                            <div class="tab-pane fade <?php
                                 if(isset($_SESSION['user'])){
                                    echo "show active";
                                 }
                                  ?>" id="assignedTo" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <input type="text" class="form-control" id="all_assignedtome_opensearch" placeholder="Type search" style="width:15%; height: 35px; float: right; margin-bottom:5px;">
                                <div class="all_assignedtome_open"></div>
                            </div>
                            <div class="tab-pane fade" id="assignedBy" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <input type="text" name="all_assignedtome_opensearch" class="form-control all_assignedbyme_opensearch" placeholder="Type Search" style="width:15%; height: 35px; float: right; margin-bottom:5px;">
                                    <div class="all_assignedbyme_open"></div>
                            </div>
                            <div id="formyReview" class="tab-pane fade">
                                <input type="text" name="all_forreview_opensearch" class="form-control all_forreview_opensearch" placeholder="Type Search" style="width:15%; height: 35px; float: right; margin-bottom:5px;">
                                <div class="all_forreview_open"></div>
                             </div>
                        </div>                        
                        <?php }else if(isset($_GET['closetask'])){
                        ?>
                        <ul class="nav nav-tabs mb-3" id="simpletab" role="tablist">
                            <?php 
                            if(isset($_SESSION['id'])){
                            ?>
                            <li class="nav-item active">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#compTab" role="tab" aria-controls="pills-home" aria-selected="true">CLOSED TASKS</a>
                            </li>
                            <?php } ?>
                            <li class="nav-item" <?php  if(isset($_SESSION['user'])){echo "class='active'";}?>>
                                <a class="nav-link <?php  if(isset($_SESSION['user'])){echo 'active';}?>" id="pills-contact-tab" data-toggle="pill" href="#assignedTo" role="tab" aria-controls="pills-contact" aria-selected="false">ASSIGNED TO ME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#assignedBy" role="tab" aria-controls="pills-contact" aria-selected="false">ASSIGNED BY ME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#formyReview" role="tab" aria-controls="pills-contact" aria-selected="false">FOR MY REVIEW</a>
                            </li>
                            <div class="buttons">
                             <a href="stmnewtask.php" class="btn btn-success btn-sm">ADD NEW TASK
                             </a>
                             <a class="btn btn-warning btn-sm" data-target="#exportModal" data-toggle="modal">EXPORT</a>&nbsp
                             
                            </div>
                        </ul> 
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade <?php if(isset($_SESSION['id'])){echo 'show active'; } ?>" id="compTab" role="tabpanel" aria-labelledby="pills-home-tab">
                               <div class="row mb-2">
                                    <div class="col-md-10" >
                                        <div class="row" >
                                            <div class="col-md-12" >
                                               <select class="form-control select_filter" id="select_filter_task" style="height:30px; padding: 2px;">
                                                    <option value="All">Filter by</option>
                                                    <option value="Category">Category</option>
                                                    <option value="Created By">Created By</option>
                                                    <option value="Assignees">Assignees</option>
                                                    <option value="Skype">Skype</option>
                                                    <option value="Priority">Priority</option>
                                                </select> 
                                            
                                                <select class="form-control filter_task" style="display:none;" id="category_task">
                                                    <option value="">Select Category</option>
                                                    <?php
                                                        $dataCate = $db_helper->allRecordsOrderBy("stm_tasktypes",'tasktypeName ASC');
                                            
                                                        foreach($dataCate as $categoryData){
                                                     ?>
                                                    <option value="<?php echo $categoryData['id']; ?>">
                                                        <?php echo $categoryData['tasktypeName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control filter_task" style="display:none;" id="priority_task">
                                                    <option value="">Priority</option>
                                                    <?php
                                                        $priority = $db_helper->allRecordsOrderBy("stm_priorities",'taskpriorityName ASC');
                                            
                                                        foreach($priority as $priorities){
                                                     ?>
                                                    <option value="<?php echo $priorities['id']; ?>">
                                                        <?php echo $priorities['taskpriorityName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>

                                                <select class="form-control filter_task" id="created_by_task" style="display:none;">
                                                    <option value="">Select user</option>
                                                    <?php
                                                        $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                            
                                                        foreach($user as $allusers){
                                                     ?>
                                                    <option value="<?php echo $allusers['id']; ?>">
                                                        <?php echo $allusers['userName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control filter_task" id="assignees_filter" style="display:none;">
                                                    <option value="">Select user</option>
                                                    <?php
                                                        $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                            
                                                        foreach($user as $allusers){
                                                     ?>
                                                    <option value="<?php echo $allusers['id']; ?>">
                                                        <?php echo $allusers['userName']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="text" class="filter_task form-control" id="skype" style="display:none;">
                                            
                                                <select class="form-control filter_task" style="display:none;" id="status">
                                                    <option value="">Select Status</option>
                                                    <option value="open">Open Tasks</option>
                                                    <option value="closed">Closed Tasks</option>
                                                </select>
                                                <input id="fromFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="From Date ..." style="display:none; height: 35px;">
                                                <input id="toFlatpickr" name="fromdate" class="form-control flatpickr flatpickr-input active" type="text" placeholder="To Date ..." style="display:none; height: 35px;"> 
                                            
                                                <button style="margin-bottom:10px; display: none; float: left; margin-left: 3px;" type="button" class="btn btn-info btn-sm" id="filterBy_task">Filter</button>
                                                <a href="stmtasks.php?closetask" style="margin-bottom:10px; float: left; display: none;" class="btn btn-warning btn-sm" id="reset">Reset</a>
                                            </div>
                                        </div>                                    
                                    </div><!---col-md-10--->  
                                    
                                    <div class="col-md-2">
                                        <input type="text" name="task_comp_search" id="task_comp_search" class="form-control" placeholder="Type Search" style="height:35px;">
                                    </div><!----col-md-2--->
                                </div>
                               <div class="compTab"></div>
                            </div><!----compTab--->
                            
                            <div class="tab-pane fade <?php
                                 if(isset($_SESSION['user'])){
                                    echo "show active";
                                 }
                                  ?>" id="assignedTo" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <input type="text" class="form-control all_assignedtome_closesearch" id="all_assignedtome_closesearch" placeholder="Type search" style="width:15%; height: 35px; float: right; margin-bottom:5px;">
                                <div class="all_assignedtome_close"></div>
                            </div>
                            <div class="tab-pane fade" id="assignedBy" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <input type="text" name="all_assignedbyme_closesearch" class="form-control all_assignedbyme_closesearch" placeholder="Type Search" style="width:15%; height: 35px; float: right; margin-bottom:5px;">
                                    <div class="all_assignedbyme_close"></div>
                            </div>
                            <div id="formyReview" class="tab-pane fade">
                                <input type="text" name="all_forreview_closesearch" class="form-control all_forreview_closesearch" placeholder="Type Search" style="width:15%; height: 35px; float: right; margin-bottom:5px;">
                                <div class="all_forreview_close"></div>
                             </div>
                        </div>  
                        <?php }else{
                        ?>
                        <ul class="nav nav-tabs mb-3" id="simpletab" role="tablist">
                            <?php 
                            if(isset($_SESSION['id'])){
                            ?>
                            <li class="nav-item active">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#alltask" role="tab" aria-controls="pills-home" aria-selected="true">OPEN TASKS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#compTab" role="tab" aria-controls="pills-home" aria-selected="true">CLOSED TASKS</a>
                            </li>
                            <?php } ?>
                            
                            <li class="nav-item" <?php  if(isset($_SESSION['user'])){echo "class='active'";}?>>
                                <a class="nav-link <?php  if(isset($_SESSION['user'])){echo 'active';}?>" id="pills-contact-tab" data-toggle="pill" href="#menu1" role="tab" aria-controls="pills-contact" aria-selected="false">ASSIGNED TO ME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#home" role="tab" aria-controls="pills-contact" aria-selected="false">ASSIGNED BY ME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#allreviews" role="tab" aria-controls="pills-contact" aria-selected="false">FOR MY REVIEW</a>
                            </li>
                            <div class="buttons">
                             <a href="stmnewtask.php" class="btn btn-success btn-sm">ADD NEW TASK
                             </a>
                             <a class="btn btn-warning btn-sm" data-target="#exportModal" data-toggle="modal">EXPORT</a>&nbsp
                             
                            </div>
                        </ul> 
                        <div class="tab-content" id="pills-tabContent">
                            
                            <div class="tab-pane fade <?php if(isset($_SESSION['id'])){echo 'show active'; } ?>" id="alltask" role="tabpanel" aria-labelledby="pills-home-tab">
                               <div class="row mb-2">
                                    
                                    <div class="col-md-10">
                                        <select class="form-control select_filter" style="height:30px; padding: 2px;">
                                            <option value="All">Filter by</option>
                                            <option value="Category">Category</option>
                                            <option value="Created By">Created By</option>
                                            <option value="creation_date">Creation Date</option>
                                            
                                            <option value="Status">Status</option>
                                            <option value="Assignees">Assignees</option>
                                             <option value="Skype">Skype</option>
                                            <option value="Priority">Priority</option>
                                        </select>

                                        <select class="form-control filter_task" style="display:none;" id="category">
                                            <option value="">Select Category</option>
                                            <?php
                                                $dataCate = $db_helper->allRecordsOrderBy("stm_tasktypes",'tasktypeName ASC');
                                    
                                                foreach($dataCate as $categoryData){
                                             ?>
                                            <option value="<?php echo $categoryData['id']; ?>">
                                                <?php echo $categoryData['tasktypeName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        
                                        <select class="form-control filter_task" style="display:none;" id="status">
                                            <option value="">Select Status</option>
                                            <?php
                                                $satte = $db_helper->allRecordsRepeatedWhere('stm_statuses','id !="13" AND id !="15" AND id !="17"');
                                    
                                                foreach($satte as $statuses){
                                             ?>
                                            <option value="<?php echo $statuses['id']; ?>">
                                                <?php echo $statuses['statusName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <select class="form-control filter_task" id="assignees_filter" style="display:none;">
                                            <option value="">Select user</option>
                                            <?php
                                                $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                    
                                                foreach($user as $allusers){
                                             ?>
                                            <option value="<?php echo $allusers['id']; ?>">
                                                <?php echo $allusers['userName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <select class="filter_task" style="display:none;" id="priority">
                                            <option value="">Priority</option>
                                            <?php
                                                $priority = $db_helper->allRecordsOrderBy("stm_priorities",'taskpriorityName ASC');
                                    
                                                foreach($priority as $priorities){
                                             ?>
                                            <option value="<?php echo $priorities['id']; ?>">
                                                <?php echo $priorities['taskpriorityName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                        <select class="form-control filter_task" id="created_by" style="display:none;">
                                            <option value="">Select user</option>
                                            <?php
                                                $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                    
                                                foreach($user as $allusers){
                                             ?>
                                            <option value="<?php echo $allusers['id']; ?>">
                                                <?php echo $allusers['userName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                        <input type="text" class="filter_task form-control" id="skype" style="display:none;">
                                         

                                        <input type="date" class="form-control filter_task" id="from" style="display:none;">

                                        <input type="date" class="form-control filter_task" id="to" style="display:none;">

                                        <input type="date" class="filter_task" id="deadline" style="display:none;">
                                        &nbsp&nbsp
                                        <button style="margin-bottom:10px; display: none;" type="button" class="btn btn-info btn-sm" id="filterBy">Filter</button>
                                        <a href="stmtasks.php" style="margin-bottom:10px; display: none;" class="btn btn-warning btn-sm" id="reset">Reset</a>
                                    </div><!---col-md-8--->   
                                    
                                    <div class="col-md-2" style="float:right; ">
                                    <input type="text" name="search_box" id="tasks_search_box" class="form-control" placeholder="Type Search" style="height:35px !important">
                                    </div><!----col-md-2--->
                                </div>
                               <div class="tasksTable"></div>
                            </div><!----all task close--->

                            <div class="tab-pane fade show" id="compTab" role="tabpanel" aria-labelledby="pills-home-tab">
                               <div class="row" style="margin-bottom:2px;">
                                    
                                    <div class="col-md-10">
                                        <select id="select_filter_task" class="form-control select_filter_task">
                                            <option value="All">Filter by</option>
                                            <option value="Category">Category</option>
                                            <option value="Created By">Created By</option>
                                            <option value="creation_date">Creation Date</option>
                                            
                                            <option value="Status">Status</option>
                                            <option value="Skype">Skype</option>
                                            <option value="Priority">Priority</option>
                                        </select>
                                        <select class="form-control filter_task" style="display:none;" id="category_task">
                                            <option value="">Select Category</option>
                                            <?php
                                                $dataCate = $db_helper->allRecordsOrderBy("stm_tasktypes",'tasktypeName ASC');
                                    
                                                foreach($dataCate as $categoryData){
                                             ?>
                                            <option value="<?php echo $categoryData['id']; ?>">
                                                <?php echo $categoryData['tasktypeName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <select class="form-control filter_task" style="display:none;" id="status_task">
                                            <option value="">Select Status</option>
                                            <?php
                                                $satte = $db_helper->allRecordsRepeatedWhere('stm_statuses','id !="13" AND id !="15" AND id !="17"');
                                    
                                                foreach($satte as $statuses){
                                             ?>
                                            <option value="<?php echo $statuses['id']; ?>">
                                                <?php echo $statuses['statusName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <select class="form-control filter_task" style="display:none;" id="priority_task">
                                            <option value="">Priority</option>
                                            <?php
                                                $priority = $db_helper->allRecordsOrderBy("stm_priorities",'taskpriorityName ASC');
                                    
                                                foreach($priority as $priorities){
                                             ?>
                                            <option value="<?php echo $priorities['id']; ?>">
                                                <?php echo $priorities['taskpriorityName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <select class="form-control filter_task" id="created_by_task" style="display:none;">
                                            <option value="">Select user</option>
                                            <?php
                                                $user = $db_helper->allRecordsOrderBy('stm_users','userName ASC');
                                    
                                                foreach($user as $allusers){
                                             ?>
                                            <option value="<?php echo $allusers['id']; ?>">
                                                <?php echo $allusers['userName']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                         <input type="text" class="filter_task form-control" id="skype" style="display:none;">
                                        <input type="date" class="form-control filter_task" id="creationdate_task" style="display:none;">
                                        <input type="date" class="form-control filter_task" id="deadline_task" style="display:none;">
                                        <button style="margin-bottom:10px; display: none;" type="button" class="btn btn-info btn-sm" id="filterBy_task">Filter</button>
                                        <a href="stmtasks.php" style="margin-bottom:10px; display: none;" class="btn btn-warning btn-sm" id="reset_task">Reset</a>  
                                    </div><!---col-md-8--->   
                                    
                                    <div class="col-md-2">
                                        <input type="text" name="task_comp_search" id="task_comp_search" class="form-control" placeholder="Type Search" style="height:35px;">
                                    </div><!----col-md-2--->
                                </div>
                               <div class="compTab"></div>
                            </div><!----compTab--->
                            
                            <div class="tab-pane fade  <?php
                                 if(isset($_SESSION['user'])){
                                    echo "show active";
                                 }
                                  ?>" id="menu1" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <div class="userTasksTable"></div>
                            </div>

                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <!-- <input type="text" name="all_assignedbyme_closesearch" class="form-control all_assignedbyme_closesearch" placeholder="Type Search" style="width:15%; height: 35px; float: right; margin-bottom:5px;"> -->
                                    <div class="taskMain"></div>
                            </div>

                            <div id="allreviews" class="tab-pane fade" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <div class="col-md-2 offset-md-10 mb-2">
                                    <input type="text" name="supervisr_search" id="supervisr_search" class="form-control" placeholder="Type Search" style="height:35px;">    
                                </div>
                                
                               <div class="super_Table"></div>
                            </div>
                        </div>    
                        <?php    
                        } ?>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
             <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Export</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" class="form-control fromdate">
                        </div>
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" class="form-control todate">
                        </div>
                        <div class="error"></div>
                        <a data-dismiss="modal" class="btn" href="#">Cancel</a>
                        <input type="button" class="btn btn-info" id="exportClick" value="Export">
                        <a href="javascript:void(0)" id="dlbtn" style="display: none;">
                            <button type="button" id="mine">Export</button>
                        </a>
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