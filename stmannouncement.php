<?php ob_start();
session_start();
      include_once ('partials/header.php');
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
      include_once ('common/announceClass.php');

  if(isset($_SESSION['id']) OR isset($_SESSION['user']))
  {
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db);
      $db_helper = new db_helper($db);
      $objAnnouce = new announceClass($db);

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
        <div class="search-overlay"></div>
         <?php
          include_once "partials/sidebar.php";
         ?>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-md-12">
                        <div class="statbox widget box box-shadow">
                          <div class="widget-content widget-content-area">
                            <div class="row">
                               <div class="col-md-12">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <input type="text" class="form-control searchAnnounce" placeholder="Search title"> 
                                    </div>
                                    <?php 
                                    $user = $db_helper->SingleDataWhere('stm_users','userEmail = "shamsgulzar@gmail.com"');

                                    $user2 = $db_helper->SingleDataWhere('stm_users','userEmail = "quratulain@swiftitsol.net"');

                                    if($user['id'] == $session_id OR $user2['id'] == $session_id){
                                    ?>
                                        <div class="col-md-2 offset-md-6">
                                        <button class="btn btn-block btn-info" data-toggle="modal" data-target="#addAnnoucement">ADD NEW</button>
                                        </div>
                                    <?php  
                                    } 
                                    ?>
                                 </div>
                                 <br>
                                 <div class="row">
                                    <div class="col-md-12">
                                        <div class="announceContainer"></div>
                                    </div>
                                 </div>
                               </div>     
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addAnnoucement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                         <div class="modal-dialog" role="document">
                            <div class="modal-content">
                               <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Announcement</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                   <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control form-control-sm title">    
                                        </div>
                                        <div class="form-group">
                                            <label>Detail</label>
                                            <textarea class="form-control detail" rows="10"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control form-control-sm status">
                                            <?php 
                                               $data = $db_helper->allRecordsOrderBy('stm_annoucement_statuses','statusName ASC');
                                               foreach ($data as $list) {
                                            ?>
                                            <option value="<?php echo $list['id'] ?>"><?php echo $list['statusName'] ?></option>
                                            <?php
                                               }
                                               ?> 
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="announce" class="btn btn-success saveAnnounce" value="Save">
                                            <button class="btn btn-danger closeAnnouce" data-dismiss="modal" aria-label="Close">Close</button>
                                        </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="updateAnnoucement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                         <div class="modal-dialog" role="document">
                            <div class="modal-content">
                               <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Update Announcement</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                   <div class="form-group">
                                            <label>Title</label>
                                            <input type="hidden" class="id">
                                            <input type="text" class="form-control form-control-sm editTitle">    
                                        </div>
                                        <div class="form-group">
                                            <label>Detail</label>
                                            <textarea class="form-control editDetail" rows="10"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="submit" name="announce" class="btn btn-success updateAnnounce" value="Update">
                                            <button class="btn btn-danger closeAnnouce" data-dismiss="modal" aria-label="Close">Close</button>
                                        </div> 
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