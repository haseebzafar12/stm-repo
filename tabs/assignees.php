<table class="table table-sm table-bordered table-striped">
    <tr class="table_row" id="tabl_row_head">
        <th>ASSIGNEE</th>
        <th>SUB TASK</th>
        <th>CHANNEL</th>
        <th>STORE</th>
        <th>URL</th>
        <th>STARTED ON</th>
        <th>ENDED ON</th>
        <th>DEADLINE</th>
        <th>DESCRIPTION</th>
        <th>REVIEWER</th>
        <th>STATUS</th>
        <th>APPROVED ON</th>
        <th>ACTION</th>
    </tr>
    <?php if (isset($_GET['sub'])) { ?>
        <tr>
            <?php
            $tabl = "stm_taskassigned";
            $where1 = "id = '" . $_GET['sub'] . "' ";
            $owndata =  $db_helper->SingleDataWhere($tabl, $where1);

            $deadline = date("d-m-Y", strtotime($owndata['taskDeadline']));

            $started_at = date("d-m-Y", strtotime($owndata['taskStartDate']));
            $ended_at = date("d-m-Y", strtotime($owndata['taskDeadline']));
            $tbc = "stm_channels";
            $wherc = "id = '" . $owndata['taskchannelID'] . "'";
            $cData = $db_helper->SingleDataWhere($tbc, $wherc);

            $tbS = "stm_stores";
            $wherS = "id = '" . $owndata['taskstoreID'] . "'";
            $SData = $db_helper->SingleDataWhere($tbS, $wherS);

            $tbSt = "stm_statuses";
            $wherSt = "id = '" . $owndata['taskStatusID'] . "'";
            $StData = $db_helper->SingleDataWhere($tbSt, $wherSt);

            $tbUser = "stm_users";
            $wher = "id = '" . $owndata['taskuserID'] . "'";
            $userData = $db_helper->SingleDataWhere($tbUser, $wher);

            $superVisorData = $db_helper->SingleDataWhere('stm_users', "id = '" . $owndata['taskSupervisorID'] . "'");

            ?>
            <td>
                <?php echo $userData['userName']; ?></td>

            <td>
                <?php
                $dataSubTask = $db_helper->SingleDataWhere('stm_subtask', 'id = "' . $owndata['subTaskID'] . '"');
                echo $dataSubTask['subTask'];
                ?>
            </td>
            <td><?php echo $cData['channelName']; ?></td>
            <td><?php echo $SData['storeName']; ?></td>
            <td><?php
                if ($owndata['taskURL'] == "") {
                    echo "";
                } else {
                    echo "<a class='anchor' href='" . $owndata['taskURL'] . "' target='_blank'>Click</a>";
                }
                ?></td>
            <td class="started_on_<?php echo $owndata['id'] ?>">
                <?php
                if ($owndata['taskStartDate'] == "") {
                    echo "";
                } else {
                    echo $started_at;
                }
                ?>
            </td>
            <td><?php
                if ($owndata['taskEndDate'] == "") {
                    echo "";
                } else {
                    $dateD = date('d-m-Y', strtotime($owndata['taskEndDate']));
                    echo $dateD;
                }
                ?></td>
            <td class="ended_on_<?php echo $owndata['id'] ?>"><?php
                                                                if ($owndata['taskDeadline'] == "") {
                                                                    echo "";
                                                                } else {
                                                                    echo $ended_at;
                                                                }
                                                                ?></td>

            <td>
                <?php
                if ($owndata['subTaskDescription'] != "") {
                ?>
                    <a target="_blank" class="anchor subtaskDesc" data-id="<?php echo $owndata['id']; ?>">Click</a>
                <?php
                }
                ?>
            </td>
            <td>
                <?php echo $superVisorData['userName']; ?>
            </td>
            <td class="status_assig_<?php echo $owndata['id']; ?>">
                <?php
                $taskAssignedTo = "stm_taskassigned";
                $taskAssignedToW = "id = '" . $_GET["sub"] . "' ";
                $get_data = $db_helper->SingleDataWhere($taskAssignedTo, $taskAssignedToW);
                $tbl1 = "stm_statuses";
                if ($get_data['taskStartDate'] == "") {
                    $wher1 = "statusName = '2-Started' ORDER by statusName ASC";
                    $userStatus = $db_helper->allRecordsRepeatedWhere($tbl1, $wher1);
                } else if ($get_data['taskStartDate'] != "") {
                    $wher1 = "statusName IN('5-Done','1-New Task') ORDER by statusName ASC";
                    $userStatus = $db_helper->allRecordsRepeatedWhere($tbl1, $wher1);
                }
                ?>
                <select class="form-control form-control-sm status" style="width:100%;">
                    <option value="none">Change</option>
                    <?php
                    foreach ($userStatus as $list) {
                    ?>
                        <option value="<?php echo $list['id']; ?>" <?php
                                                                    if ($get_data['taskStatusID'] == $list['id']) {
                                                                        echo "selected = 'selected'";
                                                                    }
                                                                    ?>>
                            <?php echo $list['statusName']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td>
                <?php
                if ($owndata['taskApprovedOn']) {
                    echo date('d-m-Y', strtotime($owndata['taskApprovedOn']));
                }
                ?>
            </td>
            <td>
                <input type="hidden" class="subID" value="<?php echo $_GET['sub']; ?>">
                <input type="hidden" class="id" value="<?php echo $_GET['id']; ?>">
                <?php

                if ($get_data['taskURL'] == "" and $get_data['taskComments'] == "") {
                ?>
                    <a class="anchor addInfo" data-id="<?php echo $_GET['sub'] ?>" title="Add Info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg></a>
                <?php
                } else {
                ?>

                    <a class="anchor viewInfo" data-id="<?php echo $_GET['sub'] ?>" title="View Info"><svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg></a>
                <?php
                }
                ?>
            </td>
        </tr>
    <?php
    }
    if (isset($_GET['sub'])) {
        $where = "taskID = '" . $id . "' AND id != '" . $_GET['sub'] . "'";
    } else {
        $where = "taskID = '" . $id . "'";
    }

    $data = $db_helper->allRecordsRepeatedWhere('stm_taskassigned', $where);

    foreach ($data as $subTasksList) {
        $tbUser = "stm_users";
        $wher = "id = '" . $subTasksList['taskuserID'] . "'";
        $userData = $db_helper->SingleDataWhere($tbUser, $wher);

        $supervData = $db_helper->SingleDataWhere("stm_users", "id = '" . $subTasksList['taskSupervisorID'] . "'");

        $tbc = "stm_channels";
        $wherc = "id = '" . $subTasksList['taskchannelID'] . "'";
        $cData = $db_helper->SingleDataWhere($tbc, $wherc);

        $tbS = "stm_stores";
        $wherS = "id = '" . $subTasksList['taskstoreID'] . "'";
        $SData = $db_helper->SingleDataWhere($tbS, $wherS);

        $tbSt = "stm_statuses";
        $wherSt = "id = '" . $subTasksList['taskStatusID'] . "'";
        $StData = $db_helper->SingleDataWhere($tbSt, $wherSt);
        $statusClass = "";
        if ($StData['statusName'] == "6-Reviewed") {
            $statusClass .= "primary";
        }
        if ($StData['statusName'] == "1-New Task") {
            $statusClass .= "danger";
        }
        if ($StData['statusName'] == "3-In Progress" or $StData['statusName'] == "2-Started") {
            $statusClass .= "warning";
        }
        if ($StData['statusName'] == "4-Ended" or $StData['statusName'] == "5-Done") {
            $statusClass .= "success";
        }
        if ($StData['statusName'] == "Rejected") {
            $statusClass .= "secondary";
        }
        if ($StData['statusName'] == "Approved") {
            $statusClass .= "dark";
        }

        $startDate = date("d-m-Y", strtotime($subTasksList['taskStartDate']));
        $deadline = date("d-m-Y", strtotime($subTasksList['taskDeadline']));
        $endDate = date("d-m-Y", strtotime($subTasksList['taskDeadline']));
    ?>
        <tr>
            <td><?php echo $userData['userName']; ?></td>
            <td>
                <?php
                $dataSubTask = $db_helper->SingleDataWhere('stm_subtask', 'id = "' . $subTasksList['subTaskID'] . '"');
                echo $dataSubTask['subTask'];
                ?>
            </td>
            <td><?php echo $cData['channelName']; ?></td>
            <td><?php echo $SData['storeName']; ?></td>
            <td><?php
                if ($subTasksList['taskURL'] == "") {
                    echo "";
                } else {
                    echo "<a target='_blank' class='anchor' href='" . $subTasksList['taskURL'] . "'>Click</a>";
                }
                ?>
            </td>
            <td>
                <?php
                if ($subTasksList['taskStartDate'] == "") {
                    echo "";
                } else {
                    echo $startDate;
                }
                ?>
            </td>
            <td class="ended_on_<?php echo $subTasksList['id'] ?>">
                <?php
                if ($subTasksList['taskEndDate'] == "") {
                    echo "";
                } else {
                    $date = date('d-m-Y', strtotime($subTasksList['taskEndDate']));
                    echo $date;
                }
                ?>
            </td>
            <td>
                <?php
                if ($subTasksList['taskDeadline'] == "") {
                    echo "";
                } else {
                    echo $endDate;
                }
                ?>
            </td>
            <td>
                <?php
                if ($subTasksList['subTaskDescription'] != "") {
                ?>
                    <a class="anchor subtaskDesc" data-id="<?php echo $subTasksList['id']; ?>">Click</a>
                <?php
                }
                ?>
            </td>
            <td><?php echo $supervData['userName']; ?></td>
            <td>
                <span class="badge badge-<?php echo $statusClass; ?>">
                    <?php echo $StData['statusName']; ?>
                </span>
            </td>
            <td>
                <?php
                if ($subTasksList['taskApprovedOn']) {
                    echo date('d-m-Y', strtotime($subTasksList['taskApprovedOn']));
                }
                ?>
            </td>
            <td>
                <?php
                if (isset($_GET['review'])) {
                    if ($subTasksList['taskSupervisorID'] == $session_id) {

                        if ($StData['statusName'] == "5-Done") {
                ?>
                            <input type="hidden" class="taskID" value="<?php echo $_GET['id'] ?>">

                            <a class="approved" data-id="<?php echo $subTasksList['id']; ?>" title="Approve">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </a>
                            <a class="rejected" data-id="<?php echo $subTasksList['id']; ?>" title="Reject"><svg style="color:#e7515a;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg></a>
                <?php
                        }
                    }
                }
                ?>

                <?php
                if ($subTasksList['taskComments'] != "") {
                    echo '<a class="viewDetail" data-id="' . $subTasksList['id'] . '" title="View"><svg style="color:#3399ff;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>';
                }
                ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>