<div class="messages-container">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" class="userID" value="<?php echo $session_id; ?>">
            <input type="hidden" class="taskID" value="<?php echo $_GET['id'] ?>">
            <div class="form-group">
                <label>Recipient</label>
                <?php
                $task_users = $db_helper->SingleDataWhere('stm_tasks', 'id = "' . $_GET['id'] . '"');

                $createdUser = $db_helper->SingleDataWhere('stm_users', 'id = "' . $task_users['taskAssignedBy'] . '"');
                $dataSuper = $db_helper->singleRecordwithDistict('stm_taskassigned', 'taskID = "' . $_GET['id'] . '"');
                $dataSuperUser = $db_helper->SingleDataWhere('stm_users', 'id = "' . $dataSuper['taskSupervisorID'] . '"');
                ?>
                <select class="form-control assignedTo" style="width:100%;">
                    <option value="">Select User</option>
                    <option value="<?php echo $createdUser['id']; ?>"><?php echo $createdUser['userName']; ?></option>
                    <option>---</option>
                    <?php
                    $task_assignees = $db_helper->onlyDISTINCTRecords('stm_taskassigned', 'taskID = "' . $_GET['id'] . '"');
                    foreach ($task_assignees as $task_assignees_list) {
                        $list_of_assignees_users = $db_helper->allRecordsRepeatedWhere('stm_users', 'id = "' . $task_assignees_list['taskuserID'] . '"');
                        foreach ($list_of_assignees_users as $assigness) {
                    ?>
                            <option value="<?php echo $assigness['id']; ?>"><?php echo $assigness['userName']; ?></option>
                    <?php
                        }
                    }
                    ?>
                    <?php
                    $tasks = $db_helper->DISTINCTRecordSupervisor('stm_taskassigned', 'taskID = "' . $_GET['id'] . '"');
                    foreach ($tasks as $task_assignees_supers) {
                        $list_of_supervsr = $db_helper->allRecordsRepeatedWhere('stm_users', 'id = "' . $task_assignees_supers['taskSupervisorID'] . '"');
                        foreach ($list_of_supervsr as $reviewers) {
                    ?>
                            <option value="<?php echo $reviewers['id']; ?>"><?php echo $reviewers['userName']; ?></option>
                    <?php
                        }
                    }
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
        <div class="col-md-8">
            <button type="button" class="btn btn-success postMessage" style="float:left;">POST</button>
        </div>
    </div>
    <br>
    <div class="message-body">
        <?php
        $messages = $db_helper->allRecordsRepeatedWhere("stm_messages", "taskID = '" . $_GET['id'] . "' ORDER BY id DESC");
        foreach ($messages as $message_row) {
            $message_detail = $db_helper->allRecordsRepeatedWhere("stm_message_details", "messageID = '" . $message_row['id'] . "' ORDER BY id DESC");
            foreach ($message_detail as $message_details) {

                $createdBy = $db_helper->SingleDataWhere("stm_users", "id = '" . $message_details['msgFrom'] . "'");

                $assignedTo = $db_helper->SingleDataWhere("stm_users", "id = '" . $message_details['msgTo'] . "'");
        ?>
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                <p><b><?php echo date('d/m/Y H:i:s', strtotime($message_details['createdOn'])) . "&nbsp&nbsp" ?>
                                        From: <?php echo $createdBy['userName'] . "<br>"; ?>
                                        To: <?php echo $assignedTo['userName']; ?></b><span><?php
                                                                                            if ($message_details['isRejection'] != "") {
                                                                                                echo "<span style='color:red;'>&nbsp(Rejection)</span>";
                                                                                            }

                                                                                            ?></span>
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
            }
        }
        ?>

    </div>
</div>