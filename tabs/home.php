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
            <?php
            $list = $db_helper->SingleDataWhere("stm_tasktypes", "id = '" . $dataTask['taskTypeID'] . "'");

            ?>
            <input type="text" class="form-control form-control-sm" value="<?php echo $list['tasktypeName']; ?>" id="input_field">
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Task Name</label>
        <div class="col-md-10">
            <?php
            $list = $db_helper->SingleDataWhere("stm_priorities", "id = '" . $dataTask['taskPriorityID'] . "'");
            ?>
            <input type="text" class="form-control form-control-sm" value="<?php echo $dataTask['taskName']; ?>" id="input_field">
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Description</label>
        <div class="col-md-10">
            <textarea rows="6" class="form-control form-control-sm" name="description" id="input_field"><?php if (isset($_GET['id'])) {
                                                                                                            echo $dataTask['taskDescription'];
                                                                                                        } ?></textarea>
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Supplier</label>
        <div class="col-md-10">
            <?php
            $list = $db_helper->SingleDataWhere("stm_supplier", "id = '" . $dataTask['taskSupplierID'] . "'");
            ?>
            <input type="text" class="form-control form-control-sm" value="<?php echo $list['supplierName']; ?>" id="input_field">
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Suppliers Brand</label>
        <div class="col-md-10">
            <?php
            $list = $db_helper->SingleDataWhere("stm_brands", "id = '" . $dataTask['taskBrandID'] . "'");
            ?>
            <input type="text" class="form-control form-control-sm" value="<?php echo $list['brandName']; ?>" id="input_field">
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Priority</label>
        <div class="col-md-10">
            <?php
            $list = $db_helper->SingleDataWhere("stm_priorities", "id = '" . $dataTask['taskPriorityID'] . "'");

            ?>
            <input type="text" class="form-control form-control-sm" value="<?php echo $list['taskpriorityName']; ?>" id="input_field">
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
                    <input type="radio" name="ourBrand" class="new-control-input" value="<?php echo $ourbrandsList['id']; ?>" <?php
                                                                                                                                if (isset($_GET['id'])) {
                                                                                                                                    if ($dataTask['taskOurBrandID'] == $ourbrandsList['id']) {
                                                                                                                                        echo "checked";
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                ?>>
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
            <?php
            $list = $db_helper->SingleDataWhere("stm_users", "id = '" . $dataTask['taskAssignedBy'] . "'");
            ?>
            <input type="text" class="form-control form-control-sm" value="<?php echo $list['userName']; ?>" id="input_field">
        </div>
    </div>

    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Listing Type</label>
        <div class="col-md-10">
            <?php
            $listingtype = $db_helper->allRecordsRepeatedWhere('stm_listingtype', 'listingTypeName = "Single" OR listingTypeName = "Variation" ');
            foreach ($listingtype as $listingtypeList) {
            ?>
                <label class="new-control new-radio new-radio-text radio-success">

                    <input type="radio" name="TasklistingType" class="new-control-input" value="<?php echo $listingtypeList['id']; ?>" <?php
                                                                                                                                        if (isset($_GET['id'])) {
                                                                                                                                            if ($dataTask['taskListingTypeID'] == $listingtypeList['id']) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>>
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
            <input type="text" class="form-control" name="taskSkypeGroup" id="input_field" value="<?php echo $dataTask['taskSkypeGroup']; ?>">
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Status</label>
        <div class="col-md-10">
            <input type="text" id="input_field" class="form-control form-control-sm" value="<?php echo $dataStatus['statusName']; ?>" readonly>
        </div>
    </div>
    <div class="form-group row mb-4">
        <label for="hEmail" class="col-xl-2 col-form-label">Required On</label>
        <div class="col-md-10">
            <?php
            $dataChannel = $db_helper->allRecordsOrderBy('stm_channels', 'channelName ASC');

            $db_task_store_data = [];

            foreach ($dataChannel as $channelList) {
                $dataStore = $db_helper->allRecordsRepeatedWhere('stm_stores', 'storeChannelID = "' . $channelList['id'] . '" ');
                foreach ($dataStore as $storeList) {
            ?>
                    <label class="new-control new-checkbox checkbox-outline-success new-checkbox-text">

                        <input type="checkbox" name="storeID[]" class="new-control-input" value="<?php echo $storeList['id']; ?>" <?php
                                                                                                                                    if (isset($_GET['id'])) {

                                                                                                                                        $data_tasks = $db_helper->allRecordsRepeatedWhere('stm_task_channels', 'taskID = "' . $_GET['id'] . '"');

                                                                                                                                        foreach ($data_tasks as $tasksData) {
                                                                                                                                            $db_task_store_data = $tasksData['StoreID'];
                                                                                                                                            if ($storeList['id'] == $db_task_store_data) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    ?>>
                        <span class="new-control-indicator"></span><span class="new-chk-content">
                            <?php echo $channelList['channelName'] . '-' . $storeList['storeName'] . "&nbsp&nbsp"; ?>
                        </span>

                    </label>
            <?php
                }
            }
            ?>

        </div>
    </div>


</form>