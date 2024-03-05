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
                                <div class="col-md-8 offset-md-2">
<form method="post">
    <fieldset>
    
        <div class="form-group">
            <label>Channels</label>
            <select name="channels" class="form-control">
                 <option value="">Select Channel</option>
                    <?php
                      $channels = $db_helper->allRecordsOrderBy('stm_channels','channelName ASC');
                      foreach($channels as $channel){
                    ?>
                    <option value="<?php echo $channel['id']; ?>"><?php echo $channel['channelName']; ?></option>
                    <?php    
                      }
                    ?>
            </select>
        </div>
        <div class="form-group">
            <label>Our Brands</label>
            <select name="ourbrands" class="form-control ourbrands">
                 <option value="">Select Brand</option>
                    <?php
                      $brands = $db_helper->allRecordsOrderBy('stm_ourbrands','brandName ASC');
                      foreach($brands as $brand){
                    ?>
                    <option value="<?php echo $brand['id']; ?>"><?php echo $brand['brandName']; ?></option>
                    <?php    
                      }
                    ?>
            </select>
        </div>
        <div class="form-group">
            <label>Store</label>
            
                <input name="stores" type="text" class="form-control" required>
            
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit" name="addStore">
            <a href="stmstores.php" class="btn">Go Back</a>
        </div>
    </fieldset>
</form>
<?php
    if(isset($_POST['addStore'])){

        if(!empty($_POST['channels']) && !empty($_POST['ourbrands']) && !empty($_POST['stores'])){

            $stmt = $db->prepare("select * from stm_stores where storeChannelID = '".$_POST['channels']."' AND storeName = '".$_POST['stores']."' AND OurBrandID = '".$_POST['ourbrands']."'");

            $stmt->execute();
            $dbrecords = $stmt->fetch(PDO::FETCH_ASSOC);
            if($dbrecords['storeChannelID'] == $_POST['channels'] && $dbrecords['OurBrandID'] == $_POST['ourbrands'] && $dbrecords['storeName'] == $_POST['stores']){
                ?>
                <div class="alert alert-light-danger border-0 mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                    <strong>Error!</strong> Input records already exist</button>
                </div> 
                <?php
            }else{
                $query = $db->prepare("INSERT INTO stm_stores (storeChannelID,storeName,OurBrandID) values('".$_POST['channels']."','".$_POST['stores']."','".$_POST['ourbrands']."')");
                $query->execute();    
            }
            

        }else{
            ?>
            <div class="alert alert-light-danger border-0 mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                <strong>Error!</strong> All fields are required</button>
            </div> 
            <?php
        }

            
    }
?>    
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