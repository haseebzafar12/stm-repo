<br>
<div class="row">
<span style="font-weight: 550; font-size: 14px;">
    REF URL / PRICES
</span>
<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped">
    <tr class="table_row">
        <td>REF.URL</td>
        <td>PROD CODE</td>
        <td>PURCHASE</td>
        <td>QUANTITY</td>
        <td>AMZ PRICE</td>
        <td>EBAY PRICE</td>
        <td>WEB PRICE</td>
        <td>STORE SKU</td>
        <td>LINK SKU</td>
        <td>EAN</td>
        <td>ASIN</td>
        <td>TYPE</td>
        <td>ATTACH</td>
    </tr>

    <?php
    $dData = $db_helper->allRecordsRepeatedWhere("stm_task_details", "taskID = '" . $_GET['id'] . "'");

    foreach ($dData as $detailsList) {


        $ref_url = strip_tags($detailsList['refURL']);
        if (strlen($ref_url) > 35) {
        // truncate string
        $stringCut = substr($ref_url, 0, 35);
        $endPoint = strrpos($stringCut, ' ');

        $ref_url = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        }

    ?>
        <tr class="row_table_<?php echo $detailsList['id']; ?>">
        <td id="Db_td">
            <a class="anchor" href="<?php echo $detailsList['refURL'] ?>" target="_blank">
            <?php echo $ref_url; ?>
            </a>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['productCode'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['purchasePrice'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['quantity'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['amzPrice'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['ebayPrice'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['webPrice'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['storeSKU'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['linkedSKU'] ?>
        </td>
        <td id="Db_td">
            <?php echo $detailsList['EAN'] ?>
        </td>

        <td id="Db_td">
            <?php echo $detailsList['ASIN'] ?>
        </td>
        <td id="Db_td">
            <?php
            $datatask = $db_helper->SingleDataWhere("stm_listingtype", "id = '" . $dataTask['taskListingTypeID'] . "'");
            echo $datatask['listingTypeName'];
            ?>
        </td>
        <td class="file_row" id="Db_td">
            <?php
            if ($detailsList['attachement']) {
            $extension = pathinfo($detailsList['attachement'], PATHINFO_EXTENSION);
            $imgExtArr = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
            if (in_array($extension, $imgExtArr)) {
                $file = $detailsList['attachement'];
            ?>
                <a href="download.php?file=<?php echo $file; ?>">
                <img src="images/<?php echo $detailsList['attachement']; ?>">
                </a>
            <?php
            } else {
                echo "<span class='file_show'>" . $detailsList['attachement'] . "</span>";
            }
            }
            ?>

        </td>

        </tr>
    <?php
    }
    ?>
    </table>
</div>
</div><!-- /row--->
<br>
<div class="row">
<span style="font-weight: 550; font-size: 14px;">
    PRE-LISTING
</span>
<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped" id="ref_table">

    <tr class="table_row" id="table_row_head">
        <td>REF.URL</td>
        <td>TITLE</td>
        <td>P CODE</td>
        <td>P PRICE</td>
        <td>QTY</td>
        <td>CHANNEL</td>
        <td>STORE</td>
        <td>SALE PRICE</td>
        <td>STORE SKU</td>
        <td>LINK SKU</td>
        <td>EAN</td>
        <td>ASIN</td>
        <td>TYPE</td>
        <?php if (isset($_GET['sub'])) { ?>
        <td id="action">Action</td>
        <?php } ?>
    </tr>


    <?php
    $folder = "images/";
    $dData = $db_helper->allRecordsRepeatedWhere("stm_prelistings", "taskID = '" . $_GET['id'] . "' ORDER by channelID ASC,storeID ASC,listingTypeID DESC");
    foreach ($dData as $detailsList) {
        $ref_url_listing = strip_tags($detailsList['refURL']);
        if (strlen($ref_url_listing) > 35) {
        // truncate string
        $stringCut = substr($ref_url_listing, 0, 35);
        $endPoint = strrpos($stringCut, ' ');

        $ref_url_listing = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        }
    ?>
        <tr id="row_table_<?php echo $detailsList['id'] ?>">
        <?php
        if (isset($_GET['view']) or isset($_GET['review']) or isset($_GET['message'])) {
        ?>
            <td><a class="anchor" href="<?php echo $detailsList['refURL'] ?>" target="_blank"><?php echo $ref_url_listing; ?></a></td>
            <td><?php echo $detailsList['refTitle'] ?>
            </td>
            <td><?php echo $detailsList['productCode'] ?></td>
            <td align="right">
            <?php
            if ($detailsList['purchasePrice']) {
                echo number_format((float)$detailsList['purchasePrice'], 2, '.', '');
            }

            ?>
            </td>
            <td align="right">
            <?php
            echo $detailsList['quantity'];
            ?>
            </td>
            <td>
            <?php
            $channelID = $detailsList['channelID'];
            $channelList = $db_helper->SingleDataWhere("stm_channels", "id = '$channelID'");
            echo $channelList['channelName'];
            ?>
            </td>
            <td>
            <?php
            $storeID = $detailsList['storeID'];
            $storeList = $db_helper->SingleDataWhere("stm_stores", "id = '$storeID'");
            echo $storeList['storeName'];
            ?>
            </td>
            <td align="right"><?php
                            $salPr = $detailsList['salePrice'];
                            if ($salPr) {
                                echo number_format((float)$salPr, 2, '.', '');
                            }
                            ?></td>
            <td><?php echo $detailsList['storeSKU'] ?></td>
            <td><?php echo $detailsList['linkedSKU'] ?></td>
            <td align="right"><?php echo $detailsList['EAN'] ?></td>
            <td align="right"><?php echo $detailsList['ASIN'] ?></td>
            <td>
            <?php
            $listingTypeID = $detailsList['listingTypeID'];
            $listType = $db_helper->SingleDataWhere("stm_listingtype", "id = '$listingTypeID'");
            ?>
            <span <?php
                    if ($detailsList['listingTypeID'] == "1") {
                    echo "style='background-color:#4361ee; color:white; padding:5px;'";
                    } else if ($detailsList['listingTypeID'] == "3") {
                    echo "style='background-color:#79d2a6; padding:5px;'";
                    } else if ($detailsList['listingTypeID'] == "2") {
                    echo "style='background-color: #8cb3d9; color:white;padding:5px;'";
                    }
                    ?>>
                <?php echo $listType['listingTypeName']; ?>
            </span>
            </td>
        <?php
        } else if (isset($_GET['sub'])) {
        ?>
            <td id="ref_url_db">
            <input type="hidden" class="form-control detail_id_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['id'] ?>" style="display:none;">
            <input type="hidden" class="task_ID" value="<?php echo $_GET['id']; ?>" style="display:none;">
            <input type="text" value="<?php echo $detailsList['refURL'] ?>" class="form-control form-control-sm ref_url_<?php echo $detailsList['id'] ?>" id="ref_urls" disabled>
            </td>

            <td id="ref_title_db">
            <input type="text" class="form-control form-control-sm refTitle_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['refTitle'] ?>" id="refTitle" disabled>
            </td>
            <td id="Db_td_productCode">
            <input type="text" class="form-control form-control-sm productCode_<?php echo $detailsList['id'] ?>" value="<?php echo $detailsList['productCode'] ?>" id="productCode3" disabled>
            </td>
            <td align="right">
            <?php
            $purPRic = "";
            if ($detailsList['purchasePrice']) {
                $purPRic = number_format((float)$detailsList['purchasePrice'], 2, '.', '');
            }
            ?>
            <input style="text-align:right;" type="text" class="form-control form-control-sm purchasePrice_<?php echo $detailsList['id'] ?>" value="<?php echo $purPRic; ?>" id="purchasePrice3" disabled>
            </td>
            <td align="right">
            <input style="text-align:right;" type="text" value="<?php echo $detailsList['quantity'] ?>" class="form-control form-control-sm quantity_<?php echo $detailsList['id'] ?>" id="quantity3" disabled>
            </td>
            <td>
            <select class="form-control form-control-sm channels_row" id="channel_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id'] ?>" disabled>
                <option value="0">Select</option>
                <?php
                $dataC = $db_helper->allRecordsOrderBy("stm_channels", "channelName ASC");
                foreach ($dataC as $channelsData) {
                ?>
                <option value="<?php echo $channelsData['id'] ?>" <?php
                                                                    if ($channelsData['id'] == $detailsList['channelID']) {
                                                                    echo "selected = 'selected'";
                                                                    }
                                                                    ?>>
                    <?php echo $channelsData['channelName'] ?>
                </option>
                <?php
                }
                ?>
            </select>
            </td>
            <td>
            <select class="form-control form-control-sm stores_<?php echo $detailsList['id'] ?>" id="store_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id'] ?>" disabled>
                <?php
                $storeData = $db_helper->SingleDataWhere("stm_stores", "id = '" . $detailsList['storeID'] . "'");
                if ($storeData['storeName']) {
                ?>
                <option value="<?php echo $storeData['id']; ?>">
                    <?php echo $storeData['storeName']; ?>
                </option>
                <?php
                } else {
                ?>
                <option value="0">Select</option>
                <?php
                }
                ?>
            </select>
            </td>
            <td>
            <?php
            $salePRic = "";
            if ($detailsList['salePrice']) {
                $salePRic = number_format((float)$detailsList['salePrice'], 2, '.', '');
            }
            ?>
            <input style="text-align: right;" type="text" class="form-control form-control-sm salePrice_<?php echo $detailsList['id'] ?>" value="<?php echo $salePRic; ?>" id="salePrice3" disabled>
            </td>
            <td>
            <input type="text" value="<?php echo $detailsList['storeSKU'] ?>" class="form-control form-control-sm storeSKU_<?php echo $detailsList['id'] ?>" id="storeSKU3" disabled>
            </td>
            <td>
            <input type="text" value="<?php echo $detailsList['linkedSKU'] ?>" class="form-control form-control-sm linkedSKU_<?php echo $detailsList['id'] ?>" id="linkedSKU3" disabled>
            </td>
            <td>
            <input type="text" value="<?php echo $detailsList['EAN'] ?>" class="form-control form-control-sm EAN_<?php echo $detailsList['id'] ?>" disabled>
            </td>
            <td>
            <input type="text" value="<?php echo $detailsList['ASIN'] ?>" class="form-control form-control-sm ASIN_<?php echo $detailsList['id'] ?>" disabled>
            </td>
            <td>
            <select class="form-control form-control-sm listingType_<?php echo $detailsList['id'] ?>" id="listingType" <?php
                                                                                                                        if ($detailsList['listingTypeID'] == "1") {
                                                                                                                            echo "style='background-color:#9999ff; color:white;'";
                                                                                                                        } else if ($detailsList['listingTypeID'] == "3") {
                                                                                                                            echo "style='background-color:#70dbdb'";
                                                                                                                        } else if ($detailsList['listingTypeID'] == "2") {
                                                                                                                            echo "style='background-color:#4d79ff; color:white;'";
                                                                                                                        }
                                                                                                                        ?> disabled>

                <?php
                $lst = $db_helper->allRecordsRepeatedWhere('stm_listingtype', 'listingTypeName !="Variation" ORDER By id DESC');
                foreach ($lst as $lstType) {
                ?>
                <option id="" value="<?php echo $lstType['id'] ?>" <?php
                                                                    if ($detailsList['listingTypeID'] == $lstType['id']) {
                                                                        echo "selected = 'selected'";
                                                                    }
                                                                    ?>>
                    <?php echo $lstType['listingTypeName'] ?>
                </option>
                <?php
                }
                ?>
            </select>
            <?php
        } else {
            $listT = $db_helper->SingleDataWhere("stm_listingtype", "id = '" . $detailsList['listingTypeID'] . "'");
            ?>
            <span <?php
                    if ($detailsList['listingTypeID'] == "1") {
                    echo "style='background-color:#9999ff; color:white; padding:5px;'";
                    } else if ($detailsList['listingTypeID'] == "3") {
                    echo "style='background-color:#79d2a6; padding:5px;'";
                    } else if ($detailsList['listingTypeID'] == "2") {
                    echo "style='background-color: #8cb3d9; color:white;padding:5px;'";
                    }
                    ?>>
                <?php echo $listT['listingTypeName']; ?>
            </span>

            </td>
        <?php
        }
        ?>
        <?php
        if (isset($_GET['sub'])) {
        ?>
            <td id="Db_td">
            <svg class="edit_detail_prelst" id="clone_<?php echo $detailsList['id'] ?>" style="color:#04AA6D; display: inline-block;" data-id="<?php echo $detailsList['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>

            <button class="btn btn-success btn-sm update_detail_prelst" id="update_detail_prelst_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id'] ?>" type="button" style="display: none;">Save</button>

            <svg class="prelst_rem_detail" style="color:red; display: inline-block;" id="clone_rem_<?php echo $detailsList['id'] ?>" data-id="<?php echo $detailsList['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>


            </td>
        <?php
        }
        ?>
        </tr>
    <?php
    }
    if (isset($_GET['sub'])) {
    ?>
        <tr>
        <td>
            <input type="hidden" value="<?php echo $_GET['sub']; ?>" class="subID">
            <input type="text" class="detail_id" style="display:none;">
            <input type="text" class="form-control form-control-sm ref_url" id="ref_url" onkeyup="myFunction1()">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm ref_title" id="ref_title">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm productCode" id="productCode" onkeyup="myFunction1()">
        </td>
        <td align="right">
            <input type="text" class="form-control form-control-sm purchasePrice" id="purchasePrice" onkeyup="myFunction1()">
        </td>
        <td align="right">
            <input type="text" class="form-control form-control-sm quantity" id="quantity" onkeyup="myFunction1()">
        </td>
        <td>
            <select name="channels_new" class="form-control form-control-sm channels_new" id="channel_new" style="width:100%">
            <option value="0">Select</option>
            <?php
            $userTypes = $db_helper->allRecordsOrderBy('stm_channels', 'channelName ASC');
            foreach ($userTypes as $list) {
            ?>
                <option value="<?php echo $list['id']; ?>">
                <?php echo $list['channelName']; ?>
                </option>
            <?php
            }
            ?>
            </select>
        </td>
        <td>
            <select name="stores" class="form-control form-control-sm stores_new" id="store_new" style="width:100%">
            <option value="0">Select</option>
            </select>
        </td>
        <td align="right">
            <input type="text" class="form-control form-control-sm salePrice">
        </td>

        <td>
            <input type="text" class="form-control form-control-sm storeSKU" id="storeSKU" onkeyup="myFunction1()">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm linkedSKU" id="linkedSKU" onkeyup="myFunction1()">
        </td>
        <td align="right">
            <input type="text" class="form-control form-control-sm EAN" id="EAN" onkeyup="myFunction1()">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm ASIN" id="ASIN" onkeyup="myFunction1()">
        </td>
        <td>
            <select class="form-control form-control-sm listingType">
            <?php
            $lst = $db_helper->allRecordsRepeatedWhere('stm_listingtype', 'listingTypeName !="Variation" ORDER By id DESC');
            foreach ($lst as $lstType) {
            ?>
                <option value="<?php echo $lstType['id'] ?>">
                <?php echo $lstType['listingTypeName'] ?>
                </option>
            <?php
            }
            ?>
            </select>
        </td>

        <td class="first_td" id="Db_td">
            <button class="btn btn-success btn-sm add_prelisting" data-id="<?php echo $_GET['id'] ?>" type="button" id="clone_row" style="display: none;">Save</button>
        </td>

        </tr>
        <tr id="error_message" style="display:none;">
        <td class="error_message"></td>
        </tr>
    <?php } ?>

    </table>
</div>
</div><!-- /row--->