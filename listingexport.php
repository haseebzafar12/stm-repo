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

    if(isset($_GET['supplierid'])){
      $query = "SELECT * FROM stm_itemmaster WHERE SupplierID = '".$_GET['supplierid']."' ";
    }else{
      $query = "SELECT * FROM stm_itemmaster";  
    }  
    
    $statement = $db->prepare($query);
    $statement->execute();

    $result = $statement->fetchAll();
    $output ="";  
    $output = '<div class="table-responsive">
    <table class="table table-striped table-sm" id="userTable">
      <thead style="background-color:#d9e6f2;">
      <tr>
        <td style="color:#333; font-weight:700;">LW.SKU</td>
        <td style="color:#333; font-weight:700;">ITEM NAME</td>
        <td style="color:#333; font-weight:700;">SUPPLIER</td>
        <td style="color:#333; font-weight:700;">BARCODE</td>
        <td style="color:#333; font-weight:700;">AMZ.OS (Is Listing?)</td>
        <td style="color:#333; font-weight:700;">AMZ.QC (Is Listing?)</td>
        <td style="color:#333; font-weight:700;">EBAY OS (Is Listing?)</td>
        <td style="color:#333; font-weight:700;">EBAY AO (Is Listing?)</td>
      </tr>
      </thead>
    '; 
        
    foreach($result as $row)
    {
            
            $sku = $row['LWSKU'];
            $supplier = $db_helper->SingleDataWhere('stm_supplier','id = "'.$row['SupplierID'].'"');
            $string = strip_tags($row['ItemBarCode']);
            if (strlen($string) > 15) {
                // truncate string
                $stringCut = substr($string, 0, 15);
                $endPoint = strrpos($stringCut, ' ');

                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $string .= '...';
            }
            $yesAmzOS = "";
            $yesAmzQC = "";
            $yesEbayOs = "";
            $yesEbayAo = "";

            $AmzOS = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '1' AND StoreID = '5'");
            if($AmzOS){
              if($AmzOS['LwSku'] == $row['LWSKU']){
                $yesAmzOS = "<div class='greenBox' style='background-color:green; height:50px; width:50px;'>Yes</div>";
              }  
            }

            $AmzQC = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '1' AND StoreID = '6' AND ItemMasterID = '".$row['id']."'");
            if($AmzQC){
              if($AmzQC['LwSku'] == $row['LWSKU']){
                $yesAmzQC = "<div class='greenBox' style='background-color:green; height:50px; width:50px;'>Yes</div>";
              }  
            }
            $ebayOs = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '2' AND StoreID = '1' AND ItemMasterID = '".$row['id']."'");
            if($ebayOs){
              if($ebayOs['LwSku'] == $row['LWSKU']){
                $yesEbayOs = "<div class='greenBox' style='background-color:green; height:50px; width:50px;'>Yes</div>";
              }  
            }
            $ebayAo = $db_helper->SingleDataWhere('stm_listing',"LwSku = '$sku' AND ChannelID = '2' AND StoreID = '2' AND ItemMasterID = '".$row['id']."'");
            if($ebayAo){
              if($ebayAo['LwSku'] == $row['LWSKU']){
                $yesEbayAo = "<div class='greenBox' style='background-color:green; height:50px; width:50px;'>Yes</div>";
              }  
            }
    
            $output .= '<tr>
              <td>'.$row['LWSKU'].'</td>
              <td>'.$row['ItemName'].'</td>
              <td>'.$supplier['supplierName'].'</td>
              <td><a title="'.$row['ItemBarCode'].'">'.$string.'</a></td>
              <td>'.$yesAmzOS.'</td>
              <td>'.$yesAmzQC.'</td>
              <td>'.$yesEbayOs.'</td>
              <td>'.$yesEbayAo.'</td>
            </tr>
            ';
            }
            $output .= '</table>';

            echo $output;
  ?>