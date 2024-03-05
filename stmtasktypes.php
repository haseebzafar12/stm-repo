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
                            <form method="post">
                               <div class="row">
                                  <div class="col-md-5 offset-md-6">
                                    <div class="form-group">
                                        <input type="text" name="types" class="form-control" placeholder="Add Task Type" required>
                                    </div>     
                                    </div>
                                    
                                    <div class="col-md-1">
                                       <div class="form-group">
                                            <input type="submit" name="typesBTN" class="btn btn-primary" value="Save">     
                                        </div>     
                                    </div>  
                               </div>    
                            </form>
                            <?php
                                if(isset($_POST['typesBTN'])){
                                    $types = $db_helper->SingleDataWhere('stm_tasktypes', 'tasktypeName = "'.$_POST['types'].'"');
                                    if($types['tasktypeName'] == $_POST['types'])
                                    {
                                        echo "Already Exist";
                                    }else{
                                        $data = $objUser->stm_tasktype($_POST['types']);
                                        if($data){
                                            echo "<script>window.location = 'stmtasktypes.php'</script>";
                                        }
                                    }
                                }
                            ?>
                            <div class="row">
                                <table class="table table-striped table-sm">
                                    <tr>
                                        <th>Types</th>  
                                        <th>Action</th>  
                                    </tr>
                                      <!--  <form method="post"> -->
                                      <?php 
                                      $recs = $db_helper->allRecords('stm_tasktypes');
                                      foreach($recs as $records){
                                      ?>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control task_type_<?php echo $records['id']; ?>" value="<?php echo $records['tasktypeName']; ?>">
                                        </td>
                                        <td>
                                            <svg id="buttn" style="color:blue;" data-id="<?php echo $records['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </td>
                                    </tr>
                                      <?php
                                      }
                                      ?>
                                    <!--   </form> -->
                                    
                                </table>
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