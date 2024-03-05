<?php ob_start();
session_start();
  
  header("Content-Type: application/xlsx");   
  header("Content-Disposition: attachment; filename=listing.xls");  
  header("Pragma: no-cache"); 
  header("Expires: 0");

      
      include_once ('common/config.php');
      include_once ('common/user.php');
      include_once ('common/db_helper.php');
      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $objUser = new user($db);
      $db_helper = new db_helper($db);

    $query = "SELECT * FROM stm_itemmaster";  

    $statement = $db->prepare($query);
    $statement->execute();

    $result = $statement->fetchAll();
    ?>
    <table class="table table-striped table-sm" id="userTable">
      <thead style="background-color:#d9e6f2;">
      <tr>
        <td style="color:#333; font-weight:700;">Lw.Sku</td>
        <td style="color:#333; font-weight:700;">Item Name</td>
        <td style="color:#333; font-weight:700;">Supplier</td>
        <td style="color:#333; font-weight:700;">Barcode</td>
        
      </tr>
      </thead>
    <?php   
    foreach($result as $row)
    {
            
            $sku = $row['LWSKU'];
            $supplier = $db_helper->SingleDataWhere('stm_supplier','id = "'.$row['SupplierID'].'"');
            
           
            $dataList = $db_helper->allRecordsRepeatedWhere('stm_listing',"LwSku = '$sku' ");
            ?>
            <tr style="background-color:#ccf2ff">
              <td><?php echo $row['LWSKU']; ?></td>
              <td><?php echo $row['ItemName']; ?></td>
              <td><?php echo $supplier['supplierName']; ?></td>
              <td><?php echo $row['ItemBarCode']; ?></td>
              
            </tr>
            <!-- <tr>
              <th>LwSku</th>
              <th>ChannelName</th>
              <th>StoreName</th>
              <th>ItemID/Asim</th>
            </tr> -->
            <?php 
              foreach($dataList as $list){
                $channelid = $list['ChannelID'];
                $storeid = $list['StoreID'];
                $channels = $db_helper->SingleDataWhere('stm_channels',"id = '$channelid' ");
                $store = $db_helper->SingleDataWhere('stm_stores',"id = '$storeid' ");

                
                 $itemID = "";
                 if($channelid == '1'){
                    $itemID = $list['StoreItemID'];
                 }else if($channelid == '2'){
                    $itemID = $list['ItemID'];
                 }

                ?>

                <tr>
                  <td><?php echo $list['LwSku']; ?></td>
                  <td><?php echo $channels['channelName']; ?></td>
                  <td><?php echo $store['storeName']; ?></td>
                  <td><?php echo $itemID; ?></td>
                  <td><?php echo $list['StoreSKU']; ?></td>
                  <td><?php echo $list['StockType']; ?></td>
                </tr>
                <?php
              }
              ?>
            <?php
            }
            ?>
            </table>