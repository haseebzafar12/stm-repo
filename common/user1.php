<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "stm_users";
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    function userSession($email, $password){
        //select all data
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                Where
                    userEmail=:email and userPassword=:password";  
        
        $stmt = $this->conn->prepare( $query );
        $stmt->bindparam(":email",$email);
        $stmt->bindparam(":password",$password);
        $stmt->execute();
        //$stm->fetch(PDO::FETCH_ASSOC)
        if($record = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            return $record;
        }

    }

    function stm_adduser($usertypeID,$departmentID,$userName,$displayname,$userEmail,$userPassword,$creationDate, $createdBy){
    
        $query = $this->conn->prepare("INSERT INTO stm_users (usertypeID,departmentID,userName,displayName,userEmail,userPassword,creationDate,createdBy) 
                VALUES ('$usertypeID','$departmentID','$userName','$displayname','$userEmail','$userPassword','$creationDate','$createdBy')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_tasktype($tasktypeName){
    
        $query = $this->conn->prepare("INSERT INTO stm_tasktypes (tasktypeName) 
                VALUES ('$tasktypeName')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_employee_of_month($userID,$certificate,$monthID,$year){
    
        $query = $this->conn->prepare("INSERT INTO stm_employees_of_month (userID,certificate,monthID,year) 
                VALUES ('$userID','$certificate','$monthID','$year')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_insert_tokken($userID,$token,$createdOn){
    
        $query = $this->conn->prepare("INSERT INTO stm_token (userID,token,createdOn) 
                VALUES ('$userID','$token','$createdOn')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_tasks_channel_data($taskID, $channelID, $storeID){
    
        $query = $this->conn->prepare("INSERT INTO stm_task_channels (taskID,channelID,StoreID) 
                VALUES ('$taskID','$channelID','$storeID')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_task_channels_update($taskID,$channelID,$StoreID){

        $query=$this->conn->prepare("UPDATE stm_task_channels SET channelID='$channelID',StoreID = '$StoreID' WHERE taskID='$taskID'");

        $ex = $query->execute();
        
        return $query;
    }
    function stm_update_chat_status($fromID,$toID){

        $query=$this->conn->prepare("UPDATE stm_chat SET isOpen = '1' WHERE fromID='$toID' AND toID = '$fromID' AND isOpen = '0' ");

        $ex = $query->execute();
        
        return $query;
    }
    function stm_insert_supplier($supplier, $supplierType,$userID){
    
        $query = $this->conn->prepare("INSERT INTO stm_supplier (supplierName, supplierTypeID,userID) 
                VALUES ('$supplier','$supplierType','$userID')");
        
        $ex = $query->execute();
        return $query;
    }
     function stm_insert_brand($brand, $supplierID){
    
        $query = $this->conn->prepare("INSERT INTO stm_brands (brandName, supplierID) 
                VALUES ('$brand','$supplierID')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_subtask($subtask){
    
        $query = $this->conn->prepare("INSERT INTO stm_subtask (subTask) 
                VALUES ('$subtask')");
        
        $ex = $query->execute();
        return $query;
    }

    function stm_message($taskID,$createdOn){
    
        $query = $this->conn->prepare("INSERT INTO stm_messages (taskID,createdOn) 
                VALUES ('$taskID','$createdOn')");
        
        $ex = $query->execute();
        return $query;
    }

    function stm_login_details($userID){
    
        $query = $this->conn->prepare("INSERT INTO stm_login_details (user_id) 
                VALUES ('$userID')");
        
        $ex = $query->execute();
        return $query;
    }
    function stm_send_mesage($fromID,$toID,$message,$isOpen,$seenData,$createdOn,$file){
    
        $query = $this->conn->prepare("INSERT INTO stm_chat (fromID,toID,message,isOpen,seenDate,createdOn,attachement) 
                VALUES ('$fromID','$toID','$message','$isOpen','$seenData','$createdOn','$file')");
        
        $ex = $query->execute();
        return $query;
    }

    function stm_reply_message($messageID,$message,$msgFrom,$msgTo,$IsSeen,$createdOn){
    
        $query = $this->conn->prepare("INSERT INTO stm_message_details (messageID,message,msgFrom,msgTo,IsSeen,createdOn) 
                VALUES ('$messageID','$message','$msgFrom','$msgTo','$IsSeen','$createdOn')");
        
        $ex = $query->execute();
        return $query;
    }

    function stm_preListing($taskID,$refURL,$refTitle,$productCode,$channels,$stores,$salePrice,$storeSKU,$linkedSKU,$EAN,$listingTypeID,$LinkedStatusID,$ASIN,$purchasePrice,$quantity){
    
        $query = $this->conn->prepare("INSERT INTO stm_prelistings (taskID,refURL,refTitle,productCode,channelID,storeID,salePrice,storeSKU,linkedSKU,EAN,listingTypeID,LinkedStatusID,ASIN,purchasePrice,quantity) 
                VALUES ('$taskID','$refURL','$refTitle','$productCode','$channels','$stores','$salePrice','$storeSKU','$linkedSKU','$EAN','$listingTypeID','$LinkedStatusID','$ASIN','$purchasePrice','$quantity')");
        $ex = $query->execute();
        return $query;
    }
    function stm_insert_task_details($taskID,$refURL,$productCode,$amzPrice,$ebayPrice,$webPrice,$storeSKU,$linkedSKU,$EAN,$ASIN,$purchasePrice,$quantity,$attachment){
    
        $query = $this->conn->prepare("INSERT INTO stm_task_details (taskID,refURL,productCode,amzPrice,ebayPrice,webPrice,storeSKU,linkedSKU,EAN,ASIN,purchasePrice,quantity,attachement) 
                VALUES ('$taskID','$refURL','$productCode','$amzPrice','$ebayPrice','$webPrice','$storeSKU','$linkedSKU','$EAN','$ASIN','$purchasePrice','$quantity','$attachment')");
        $ex = $query->execute();
        return $query;
    }

    function stm_addTask($taskName,$taskDescription,$taskTypeID,$taskListingTypeID,$taskAssignedBy,$taskPriorityID,$taskBrandID,$taskOurBrand,$taskSupplierID,$taskStatusID,$taskCreationDate){
    
        $query = $this->conn->prepare("INSERT INTO stm_tasks (taskName,taskDescription,taskTypeID,taskListingTypeID,taskAssignedBy,taskPriorityID,taskBrandID,taskOurBrandID,taskSupplierID,taskStatusID,taskCreationDate) 
                VALUES ('$taskName','$taskDescription','$taskTypeID','$taskListingTypeID','$taskAssignedBy','$taskPriorityID','$taskBrandID','$taskOurBrand','$taskSupplierID','$taskStatusID','$taskCreationDate')");
            
        $ex = $query->execute();
        
        return $query;
    }

    function stm_addSubTask($subTaskName,$subTaskDescription,$taskID,$taskchannelID,$taskstoreID,$taskuserID,$statusID,$taskSupervisorID,$taskCreationDate,$taskEndDate){
    
        $query = $this->conn->prepare("INSERT INTO stm_taskassigned (subTaskID,
            subTaskDescription,
            taskID,
            taskchannelID,
            taskstoreID,
            taskuserID,
            taskStatusID,
            taskSupervisorID,
            taskCreationDate,
            taskDeadline) 
                VALUES ('$subTaskName','$subTaskDescription','$taskID','$taskchannelID','$taskstoreID','$taskuserID','$statusID','$taskSupervisorID','$taskCreationDate','$taskEndDate') ");
        
        $ex = $query->execute();
        return $query;
    }
    function generatePIN($digits){
         $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
        }
        return $pin;
    }

    function update_user_change_password($password, $UserID){

        $query=$this->conn->prepare("UPDATE stm_users SET UserPassword='$password' WHERE id='$UserID'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function tasktypeUpdae($task, $id){

        $query=$this->conn->prepare("UPDATE stm_tasktypes SET tasktypeName='$task' WHERE id='$id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function subtaskUPdate($task, $id){

        $query=$this->conn->prepare("UPDATE stm_subtask SET subTask='$task' WHERE id='$id'");

        $ex = $query->execute();
        
        return $query;
        
    }

    function user_edit($displayname,$type,$userID){

        $query=$this->conn->prepare("UPDATE stm_users SET usertypeID='$type', displayName='$displayname' WHERE id='$userID'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function update_last_activity($login_details_id){

        $query=$this->conn->prepare("UPDATE stm_login_details SET last_activity= now() WHERE login_details_id='$login_details_id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function update_isType($enum,$login_details_id){

        $query=$this->conn->prepare("UPDATE stm_login_details SET is_type= '$enum' WHERE login_details_id='$login_details_id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function update_password($password, $userID){

        $query=$this->conn->prepare("UPDATE stm_users SET userPassword='$password' WHERE id='$userID'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function backup_approvedOn($id, $taskEndDate){

        $query = $this->conn->prepare("UPDATE stm_taskassigned SET taskApprovedOn = '$taskEndDate' WHERE id = '$id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function user_profile_update($UserID,$userName,$displayname,$userDP,$userEmail,$userPhone,$userWhatsapp,$userCity,$userAddress){

        $query=$this->conn->prepare("UPDATE stm_users SET userName='$userName',
        displayName = '$displayname',
        userDP = '$userDP',
        userEmail = '$userEmail',
        userPhone = '$userPhone',
        userWhatsapp = '$userWhatsapp',
        userCity = '$userCity',
        userAddress = '$userAddress'
         WHERE id='$UserID'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function task_edit($taskName,$taskDescription,$taskTypeID,$taskListingTypeID,$taskAssignedBy,$taskPriorityID,$taskBrandID,$taskBrand,$taskSupplierID,$taskSkypeGroup,$id){

        $query=$this->conn->prepare("UPDATE stm_tasks SET 
        taskName='$taskName',
        taskDescription = '$taskDescription',
        taskTypeID = '$taskTypeID',
        taskListingTypeID = '$taskListingTypeID',
        taskAssignedBy = '$taskAssignedBy',
        taskPriorityID = '$taskPriorityID',
        taskBrandID = '$taskBrandID',
        taskOurBrandID = '$taskBrand',
        taskSupplierID = '$taskSupplierID',
        taskSkypeGroup = '$taskSkypeGroup'
         WHERE id='$id'");
        $ex = $query->execute();
        
        return $query;
        
    }
    function updateStatus($subID,$statusID,$startDate,$taskEndDate,$taskURL,$taskComments){

        $query = $this->conn->prepare("UPDATE stm_taskassigned SET 
            taskStatusID ='$statusID', 
            taskStartDate = '$startDate',
            taskEndDate = '$taskEndDate',
            taskURL = '$taskURL',
            taskComments = '$taskComments' 
            WHERE id='$subID'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function update_suppliers($supervisor,$supplierID,$supplierName,$supplierType){

        $query = $this->conn->prepare("UPDATE stm_supplier SET 
            supplierName ='$supplierName', 
            supplierTypeID = '$supplierType',
            userID = '$supervisor' 
            WHERE id='$supplierID'");

        $ex = $query->execute();
        return $query; 
    }
    function update_brands($brandID,$brandName,$supplier_id){

        $query = $this->conn->prepare("UPDATE stm_brands SET 
            brandName ='$brandName', 
            supplierID = '$supplier_id' 
            WHERE id='$brandID'");

        $ex = $query->execute();
        return $query; 
    }
    function updateTaskAssigned($subTaskName,$subTaskDescription,$taskID,$taskchannelID,$taskstoreID,$taskuserID,$taskSupervisorID,$taskDeadline,$subid){

        $query = $this->conn->prepare("UPDATE stm_taskassigned SET 
            subTaskID ='$subTaskName',
            subTaskDescription ='$subTaskDescription',
            taskchannelID = '$taskchannelID',
            taskstoreID = '$taskstoreID',
            taskuserID = '$taskuserID',
            taskSupervisorID = '$taskSupervisorID',
            taskDeadline = '$taskDeadline'
            WHERE taskID='$taskID' AND id = '$subid'");

        $ex = $query->execute();
        
        return $query;
        
    }
    
    function updateURL($subID,$url,$comments){

        $query = $this->conn->prepare("UPDATE stm_taskassigned SET 
            taskURL ='$url', 
            taskComments = '$comments' 
            WHERE id='$subID'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function isLOGIN($id){

        $query = $this->conn->prepare("UPDATE stm_users SET 
            isLogin ='1' WHERE id='$id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function sync_sku_update($row_id,$statusID){

        $query = $this->conn->prepare("UPDATE stm_prelistings SET 
            LinkedStatusID ='$statusID' WHERE id='$row_id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function isLOGOUT($id){

        $query = $this->conn->prepare("UPDATE stm_users SET 
            isLogin ='0' WHERE id='$id'");

        $ex = $query->execute();
        
        return $query;
        
    }
    function updateTaskStatus($id, $status){
        $query = $this->conn->prepare("UPDATE stm_tasks SET 
            taskStatusID ='$status' 
            WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function updateMessage($taskID,$createdOn){
        $query = $this->conn->prepare("INSERT INTO stm_messages(taskID,createdOn)VALUES ('$taskID','$createdOn')");
        
        $ex = $query->execute();
        return $query;
    }
    function updateMessageDetail($messageID,$message,$msgFrom,$msgTo,$IsSeen,$isRejection,$createdOn){
        $query = $this->conn->prepare("INSERT INTO stm_message_details (messageID,message,msgFrom,msgTo,IsSeen,isRejection,createdOn) 
                VALUES ('$messageID','$message','$msgFrom','$msgTo','$IsSeen','$isRejection','$createdOn')");
        
        $ex = $query->execute();
        return $query;
    }
    function updateUserMessage($id,$userMsg){
        $query = $this->conn->prepare("UPDATE stm_message_details SET 
            IsSeen ='$userMsg'
            WHERE id = '$id'");
        $ex = $query->execute();
        return $query;
    }
    function updateToSeenDate($id,$ToSeenDate){
        $query = $this->conn->prepare("UPDATE stm_message_details SET IsSeen='1', ToSeenDate ='$ToSeenDate'
            WHERE id = '$id'");
        $ex = $query->execute();
        return $query;
    }
    function updateReview($id,$reviewStartedAt,$reviewEndAt){
        $query = $this->conn->prepare("UPDATE stm_tasks SET 
            reviewStartedAt ='$reviewStartedAt',
            reviewEndAt ='$reviewEndAt'
            WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function updateContent($id,$content){
        $query = $this->conn->prepare("UPDATE stm_tasks SET 
            taskContent ='$content'
            WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function updatePreListingNote($id,$content){
        $query = $this->conn->prepare("UPDATE stm_prelistings SET 
            note ='$content'
            WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function stm_update_skuprices($id,$storeSKU,$linkedSKU,$EAN,$typeSKU,$salePrice,$purchasePrice,$quantity){
    
        $query = $this->conn->prepare("UPDATE stm_skuprices SET storeSKU ='$storeSKU',linkedSKU='$linkedSKU',EAN='$EAN',typeSKU='$typeSKU',salePrice = '$salePrice',purchasePrice='$purchasePrice',quantity = '$quantity' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function stm_update_prelisting($id,$refURL,$refTitle,$productCode,$channels,$stores,$salePrice,$storeSKU,$linkedSKU,$EAN,$listingType,$ASIN,$purchasePrice,$quantity){
    
        $query = $this->conn->prepare("UPDATE stm_prelistings SET refURL ='$refURL',refTitle ='$refTitle',productCode='$productCode',channelID='$channels',storeID='$stores',salePrice = '$salePrice',
            storeSKU = '$storeSKU',
            linkedSKU = '$linkedSKU',
            EAN = '$EAN',
            listingTypeID = '$listingType',
            ASIN = '$ASIN',
            purchasePrice='$purchasePrice',quantity = '$quantity' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function stm_update_taskDetails($id,$refURL,$productCode,$amzPrice,$ebayPrice,$webPrice,$storeSKU,$linkedSKU,$EAN,$ASIN,$purchasePrice,$quantity,$attachement){
    
        $query = $this->conn->prepare("UPDATE stm_task_details SET refURL ='$refURL',productCode='$productCode',amzPrice='$amzPrice',ebayPrice='$ebayPrice',webPrice = '$webPrice',
            storeSKU = '$storeSKU',
            linkedSKU = '$linkedSKU',
            EAN = '$EAN',
            ASIN = '$ASIN',
            purchasePrice='$purchasePrice',quantity = '$quantity',attachement = '$attachement' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function deleteSubTask($id){
        $query = $this->conn->prepare("DELETE from stm_taskassigned WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function stm_rem_detail($id){
        $query = $this->conn->prepare("DELETE from stm_task_details WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function stm_rem_prelst($id){
        $query = $this->conn->prepare("DELETE from stm_prelistings WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }
    function stm_task_inactive($id,$status){
        $query = $this->conn->prepare("UPDATE stm_tasks SET taskStatusID = '$status' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function stm_assignees_inactive($id){
        $query = $this->conn->prepare("UPDATE stm_taskassigned SET isActive = '0' WHERE taskID = '$id'");

        $ex = $query->execute();
        return $query;
    }
     function updateAssigneesStatus($id,$status,$rejectionMessageID){
        
        $query = $this->conn->prepare("UPDATE stm_taskassigned SET taskStatusID = '$status',rejectionMessageID = '$rejectionMessageID' WHERE id = '$id' ");

        $ex = $query->execute();
        return $query;
    }
    function stm_taskAssigned_inactive($id,$status){
        $query = $this->conn->prepare("UPDATE stm_taskassigned SET taskStatusID = '$status' WHERE taskID = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function updateApproved($id,$status,$date){
        $query = $this->conn->prepare("UPDATE stm_taskassigned SET taskStatusID = '$status', taskApprovedOn = '$date' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function update_read_status($status,$id){
        $query = $this->conn->prepare("UPDATE stm_users SET readStatus = '$status' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function update_online_status($status,$id){
        
        $query = $this->conn->prepare("UPDATE stm_users SET onlineStatus = '$status' WHERE id = '$id'");

        $ex = $query->execute();
        return $query;
    }
    function stm_rem_task_channels($taskID){
        $query = $this->conn->prepare("DELETE from stm_task_channels WHERE taskID='$taskID'");
        $ex = $query->execute();
        return $query;
    }
  
}
?>