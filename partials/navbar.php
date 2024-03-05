<!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm expand-header">
            <a href="index.php" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
                
            <div class="col-md-11">
                <div class="d-flex justify-content-between align-items-center breaking-news bg-white">
                    <?php 
                    $dataStatus = $db_helper->SingleDataWhere('stm_annoucement_statuses','statusName = "Active"');

                    $data = $db_helper->allRecordsRepeatedWhere('stm_announcements','status = "'.$dataStatus['id'].'"');
                    if($data){
                    ?>
                    <div class="d-flex flex-row flex-grow-1 flex-fill justify-content-center bg-danger text-white px-1 news"><span class="d-flex align-items-center">&nbsp;ANNOUNCEMENTS</span></div>
                    <marquee class="news-scroll" behavior="scroll" direction="left" scrollamount="4" onmouseover="this.stop();" onmouseout="this.start();">
                        <?php
                        foreach ($data as $list) {
                        ?>
                            <span class="dot"></span> <a href="stmannouncedetail.php?id=<?php echo $list['id']; ?>&view">
                                <?php echo $list['title']; ?>
                            </a>
                        <?php
                        }
                        ?>
                    </marquee>
                    <?php 
                    }
                    ?>
                </div>
            </div>
                
            <ul class="navbar-item flex-row navbar-dropdown">
               
                <?php 
                $UnSeenMessages = $db_helper->allRecordsRepeatedWhere("stm_message_details","msgTo = '$session_id' AND IsSeen = '0'");
                $totalUnSeenMessages = count($UnSeenMessages);
                ?>
                <li class="nav-item dropdown message-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg style="height: 30px !important; width:30px !important;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        <?php 
                         if($totalUnSeenMessages > 0){
                        ?>
                        <span class="badge badge-danger">
                        <?php echo $totalUnSeenMessages; ?>   
                        </span>
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu p-0 position-absolute" aria-labelledby="messageDropdown">
                        <div class="">

                            <?php 
                              $message = $db_helper->allRecordsRepeatedWhere("stm_message_details","msgTo = '$session_id' AND isSeen = '0'");
                            if(count($message)){
                              foreach ($message as $messageData) {

                              $msgFrom = $db_helper->SingleDataWhere("stm_users",'id = "'.$messageData['msgFrom'].'"'); 

                              $string = strip_tags($messageData['message']);
                              if (strlen($string) > 15) {
                                  // truncate string
                                  $stringCut = substr($string, 0, 27);
                                  $endPoint = strrpos($stringCut, ' ');

                                  $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                  $string .= '...';
                              }
                            ?>
                            <?php
                            
                            $messageTaskID = $db_helper->SingleDataWhere('stm_messages','id = "'.$messageData['messageID'].'"'); 
                                if($messageTaskID['taskID'] == 0){
                                ?>
                                    <a class="dropdown-item" href="stmMessages.php">
                                        <div class="">
                                            <div class="media">
                                                
                                                <div class="media-body">
                                                    <div class="">
                                                        <h5 class="usr-name">
                                                            <?php echo $msgFrom['userName'] ?>
                                                        </h5>
                                                        <p class="msg-title">
                                                            <?php 
                                                            echo $string;
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php }else{
                                 ?>
                                    <a class="dropdown-item" href="stmMessages.php">
                                        <div class="">
                                            <div class="media">
                                                
                                                <div class="media-body">
                                                    <div class="">
                                                        <h5 class="usr-name">
                                                            <?php echo $msgFrom['userName'] ?>
                                                        </h5>
                                                        <p class="msg-title">
                                                            <?php 
                                                            echo $string;
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>     
                                <?php    
                                }
                            }}else{?>
                            <a class="dropdown-item">No any message</a>    
                            <?php }?>
                            <a class="dropdown-item" href="stmMessages.php" style="text-align:center;">
                                See all messages
                            </a>
                            
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <?php 
                        if($session_data['userDP']){
                        ?>
                        <img src="images/<?php echo $session_data['userDP']; ?>" width="90" height="90">
                        <?php    
                        }else{
                        ?>
                        <img src="assets/img/90x90.jpg" alt="avatar">     
                        <?php   
                        }
                        ?>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="dropdown-item">
                           <a href="#"><?php echo $session_data['userName']; ?> </a>     
                        </div>
                        <div class="dropdown-item">
                            <a href="stmProfile.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span> Profile</span>
                            </a>
                        </div>
                      
                        <div class="dropdown-item">
                            <a href="logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->