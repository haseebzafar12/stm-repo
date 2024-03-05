<?php
ob_start();
    session_start();

      include('../../smtp/PHPMailerAutoload.php');     
      include_once ('../config.php');
      include_once ('../db_helper.php');
      include_once ('../user.php');
      include_once ('../announceClass.php');

      $dbcon = new Database();
      $db = $dbcon->getConnection();
      $DB_HELPER_CLASS = new db_helper($db);
      $objUser = new User($db);
      $objAnnouce = new announceClass($db);

     $session_id = "";
     if(isset($_SESSION['user'])){
      $session_id = $_SESSION['user'];
     }else if(isset($_SESSION['id'])){
      $session_id = $_SESSION['id'];
     }
  $status = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "5-Done"');

  $stApprov = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "Approved"');

  $stNew = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "1-New Task"');

  $stProg = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "3-In Progress"');
  $stInactive = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "In-Active"');
  $rejected = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "Rejected"');
  $forRev = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "7-For Review"');
  $reviewed = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "6-Reviewed"');
if(isset($_POST['post_m']) && $_POST['post_m'] == "filterCats"){
  
  $supplierPost      = $_POST['supplierPost'];
  $fromPost     = $_POST['fromPost'];
  $toPost       = $_POST['toPost'];
  $catPost  = $_POST['catPost'];

  $output = "";

  if(!empty($catPost)){
    $cats = $DB_HELPER_CLASS->SingleDataWhere('stm_tasktypes','id = "'.$catPost.'"');
    $output .= '<tr class="content">';
      $output .= '<td>'.$cats['tasktypeName'].'</td>';
        if(!empty($supplierPost) AND !empty($fromPost) AND !empty($toPost)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(empty($supplierPost) AND !empty($fromPost) AND !empty($toPost)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(!empty($supplierPost) AND empty($fromPost) AND empty($toPost)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(empty($supplierPost) AND empty($fromdateP) AND empty($todateP)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
        }
        $totalnewRequest = count($newRequest);
        $output .= '<td align="right">';
        if($totalnewRequest){
              $supGet = '';
              $get = '';
              if($supplierPost != ''){
                  $supGet = "&supplier=".$supplierPost."";
              }
              $dates = '';
              if($fromPost != '' AND $toPost != '' AND $supplierPost != ''){
                  $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.'&categoryNew&allget';
              }
              if($fromPost != '' AND $toPost != '' AND $supplierPost == ''){
                $get = '&fromDate='.$fromPost.'&toDate='.$toPost.'&categoryNew&dates';  
              }
              if($fromPost == '' AND $toPost == '' AND $supplierPost != ''){
                $get = "&categoryNew&supp";  
              }
              if($fromPost == '' AND $toPost == '' AND $supplierPost == ''){
                $get = "&categoryNew&all";  
              }
              $output .='<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].''.$get.'">'.$totalnewRequest.'</a>';  
        }
        $output .= '</td>';
        if(!empty($supplierPost) AND !empty($fromPost) AND !empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(empty($supplierPost) AND !empty($fromPost) AND !empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(!empty($supplierPost) AND empty($fromPost) AND empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(empty($supplierPost) AND empty($fromdateP) AND empty($todateP)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
        }

        $totalcomplet = count($complet);
          $output .= '<td align="right">';
          if($totalcomplet){
              $supGet = '';
              $get = '';
              if($supplierPost != ''){
                  $supGet = "&supplier=".$supplierPost."";
              }
              $dates = '';
              if($fromPost != '' AND $toPost != '' AND $supplierPost != ''){
                  $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.'&categoryComp&allget';
              }
              if($fromPost != '' AND $toPost != '' AND $supplierPost == ''){
                $get = '&fromDate='.$fromPost.'&toDate='.$toPost.'&categoryComp&dates';  
              }
              if($fromPost == '' AND $toPost == '' AND $supplierPost != ''){
                $get = "&categoryComp&supp";  
              }
              if($fromPost == '' AND $toPost == '' AND $supplierPost == ''){
                $get = "&categoryComp&all";  
              }
              $output .= '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].''.$get.'">'.$totalcomplet.'</a>';  
          }
          $output .= '</td>';
         if(!empty($supplierPost) AND !empty($fromPost) AND !empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
        }else if(empty($supplierPost) AND !empty($fromPost) AND !empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
        }else if(!empty($supplierPost) AND empty($fromPost) AND empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
        }else if(empty($supplierPost) AND empty($fromdateP) AND empty($todateP)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
        }
         $totalpendin = count($pendin);
         $output .= '<td align="right">';
          if($totalpendin){
              $supGet = '';
              $get = '';
              if($supplierPost != ''){
                  $supGet = "&supplier=".$supplierPost."";
              }
              $dates = '';
              if($fromPost != '' AND $toPost != '' AND $supplierPost != ''){
                  $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.'&categoryPend&allget';
              }
              if($fromPost != '' AND $toPost != '' AND $supplierPost == ''){
                $get = '&fromDate='.$fromPost.'&toDate='.$toPost.'&categoryPend&dates';    
              }
              if($fromPost == '' AND $toPost == '' AND $supplierPost != ''){
                $get = "&categoryPend&supp";  
              }
              if($fromPost == '' AND $toPost == '' AND $supplierPost == ''){
                $get = "&categoryPend&all";  
              }
              $output .= '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].''.$get.'">'.$totalpendin.'</a>';  
          }
          $output .= '</td>';
    $output .= '</tr>';
  }else if(empty($catPost) AND empty($supplierPost) AND empty($fromPost) AND empty($toPost)){
    $datacats = $DB_HELPER_CLASS->allRecordsOrderBy('stm_tasktypes','tasktypeName ASC');
     foreach($datacats as $cats){
       $output .= "<tr class='content'>";
       $output .= "<td>".$cats['tasktypeName']."</td>";

       $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
       
       $output .= "<td align='right'>";
       $totalnewRequest = count($newRequest);
       if($totalnewRequest){
          
          $output .= '<a class="anchor" target="_blank" href="stm_cats_detail.php?type='.$cats['id'].'&newRequestDate">'.$totalnewRequest.'</a>';
        }
        $output .= "</td>";

       if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }

       $totalcomplet = count($complet);

       $output .= '<td align="right">';
        if($totalcomplet){
          
          $output .= '<a target="_blank" class="anchor" href="stm_cats_detail.php?type='.$cats['id'].'&completedDate">'.$totalcomplet.'</a>';
        }
       $output .= '</td>';

       if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
       }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
       }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
       }

       $totalpendin = count($pendin);
       $output .= '<td align="right">';
        if($totalpendin){
          $output .= '<a target="_blank" class="anchor" href="stm_cats_detail.php?type='.$cats['id'].'&pendingDate">'.$totalpendin.'</a>';
        }
       $output .= '</td>';
       $output .= '</tr>';

     }
  }else{
     $datacats = $DB_HELPER_CLASS->allRecordsOrderBy('stm_tasktypes','tasktypeName ASC');
     foreach($datacats as $cats){
       $output .= "<tr class='content'>";
       $output .= "<td>".$cats['tasktypeName']."</td>";
       if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }
       $output .= "<td align='right'>";
       $totalnewRequest = count($newRequest);
       if($totalnewRequest){
          $supGet = '';
          if($supplierPost != ''){
              $supGet ="&supplier=".$supplierPost."";
          }
          $dates = "";
          if($fromPost != '' AND $toPost != ''){
              $dates ='fromDate='.$fromPost.'&toDate='.$toPost.'';
          }
          $output .= '<a class="anchor" target="_blank" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].'&newRequestDate">'.$totalnewRequest.'</a>';
        }
        $output .= "</td>";

       if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
          $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"'); 
       }

       $totalcomplet = count($complet);

       $output .= '<td align="right">';
        if($totalcomplet){
          $supGet = '';
          if($supplierPost != ''){
              $supGet = "&supplier=".$supplierPost."";
          }
          $dates = "";
          if($fromPost != '' AND $toPost != ''){
              $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.' ';
          } 
          $output .= '<a target="_blank" class="anchor" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].'&completedDate">'.$totalcomplet.'</a>';
        }
       $output .= '</td>';

       if($supplierPost == "" AND !empty($fromPost) AND !empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
       }else if($supplierPost != "" AND !empty($fromPost) AND !empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskCreationDate BETWEEN "'.$fromPost.'" AND "'.$toPost.'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
       }else if($supplierPost != "" AND empty($fromPost) AND empty($toPost)){
          $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$cats['id'].'" AND taskSupplierID = "'.$supplierPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')'); 
       }

       $totalpendin = count($pendin);
       $output .= '<td align="right">';
        if($totalpendin){
          $supGet = '';
          if($supplierPost != ''){
              $supGet = "&supplier=".$supplierPost."";
          }
          $dates = "";
          if($fromPost != '' AND $toPost != ''){
              $dates = 'fromDate='.$fromPost.'&toDate='.$toPost.' ';
          }  
          $output .= '<a target="_blank" class="anchor" href="stm_cats_detail.php?'.$dates.''.$supGet.'&type='.$cats['id'].'&pendingDate">'.$totalpendin.'</a>';
        }
       $output .= '</td>';
       $output .= '</tr>';

     } 
  }
  echo $output;
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "filterSupp"){
  $suppPost      = $_POST['suppPost'];
  $fromdateP     = $_POST['fromdateP'];
  $todateP       = $_POST['todateP'];
  $categoryPost  = $_POST['categoryPost'];

  $rowperpage = 17;
  $statement = $db->prepare("SELECT * from stm_supplier");
  $statement->execute();
  $result = $statement->fetchAll();
  $total_supplier = $statement->rowCount();
  $output = "";

  if(!empty($suppPost)){
    $sups = $DB_HELPER_CLASS->SingleDataWhere('stm_supplier','id = "'.$suppPost.'"');
    $output .= '<tr class="supContent">';
      $output .= '<td>'.$sups['supplierName'].'</td>';
        if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
        }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
          $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
        }
        $totalnewRequest = count($newRequest);
        $output .= '<td align="right">';
        if($totalnewRequest){
            $catGet = '';
              $get = '';
              if($categoryPost != ''){
                  $catGet = "&category=".$categoryPost."";
              }
              $dates = '';
              if($fromdateP != '' AND $todateP != '' AND $categoryPost != ''){
                  $dates = 'fromDate='.$fromdateP.'&toDate='.$todateP.'&supplierNew&allget';
              }
              if($fromdateP != '' AND $todateP != '' AND $categoryPost == ''){
                $get = '&fromDate='.$fromdateP.'&toDate='.$todateP.'&supplierNew&dates';  
              }
              if($fromdateP == '' AND $todateP == '' AND $categoryPost != ''){
                $get = "&supplierNew&ctANDsp";  
              }
              if($fromdateP == '' AND $todateP == '' AND $categoryPost == ''){
                $get = "&supplierNew&allsupp";  
              }
              $output .='<a class="anchor" target="_blank" href="stm_supp_det.php?'.$dates.''.$catGet.'&supps='.$sups['id'].''.$get.'">'.$totalnewRequest.'</a>'; 
        }
        $output .= '</td>';
        if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }

        $totalcomplet = count($complet);
          $output .= '<td align="right">';
          if($totalcomplet){
              $catGet = '';
              $get = '';
              if($categoryPost != ''){
                  $catGet = "&category=".$categoryPost."";
              }
              $dates = '';
              if($fromdateP != '' AND $todateP != '' AND $categoryPost != ''){
                  $dates = 'fromDate='.$fromdateP.'&toDate='.$todateP.'&supplierComp&allget';
              }
              if($fromdateP != '' AND $todateP != '' AND $categoryPost == ''){
                $get = '&fromDate='.$fromdateP.'&toDate='.$todateP.'&supplierComp&dates';  
              }
              if($fromdateP == '' AND $todateP == '' AND $categoryPost != ''){
                $get = "&supplierComp&ctANDsp";  
              }
              if($fromdateP == '' AND $todateP == '' AND $categoryPost == ''){
                $get = "&supplierComp&allsupp";  
              }
              $output .='<a class="anchor" target="_blank" href="stm_supp_det.php?'.$dates.''.$catGet.'&supps='.$sups['id'].''.$get.'">'.$totalcomplet.'</a>';   
          }
          $output .= '</td>';
         if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }
         $totalpendin = count($pendin);
         $output .= '<td align="right">';
          if($totalpendin){
              $catGet = '';
              $get = '';
              if($categoryPost != ''){
                  $catGet = "&category=".$categoryPost."";
              }
              $dates = '';
              if($fromdateP != '' AND $todateP != '' AND $categoryPost != ''){
                  $dates = 'fromDate='.$fromdateP.'&toDate='.$todateP.'&supplierPend&allget';
              }
              if($fromdateP != '' AND $todateP != '' AND $categoryPost == ''){
                $get = '&fromDate='.$fromdateP.'&toDate='.$todateP.'&supplierPend&dates';  
              }
              if($fromdateP == '' AND $todateP == '' AND $categoryPost != ''){
                $get = "&supplierPend&ctANDsp";  
              }
              if($fromdateP == '' AND $todateP == '' AND $categoryPost == ''){
                $get = "&supplierPend&allsupp";  
              }
              $output .='<a class="anchor" target="_blank" href="stm_supp_det.php?'.$dates.''.$catGet.'&supps='.$sups['id'].''.$get.'">'.$totalpendin.'</a>';     
          }
          $output .= '</td>';
    $output .= '</tr>';
  }else if(empty($suppPost)){
    $datacats = $DB_HELPER_CLASS->allRecordsOrderBy('stm_supplier','supplierName ASC');
    foreach($datacats as $sups){
      $output .= '<tr class="post supContent" id="post_'.$sups['id'].'">';
        $output .= '<td>'.$sups['supplierName'].'</td>';
          if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID != "'.$stInactive['id'].'"');
          }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID != "'.$stInactive['id'].'"');
          }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
          }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
          }
          $totalnewRequest = count($newRequest);
          $output .= '<td align="right">';
          if($totalnewRequest){
              $dates = "";
              if(!empty($fromdateP) AND !empty($fromdateP)){
                $dates = '&fromDate='.$fromdateP.'&toDate='.$todateP.'';
              }
              $categoryGet = "";
              if(!empty($categoryPost)){
                $categoryGet = "&category=".$categoryPost."";
              }
              $output .= '<a target="_blank" class="anchor" href="stm_supp_det.php?supps='.$sups['id'].''.$dates.''.$categoryGet.'&newRequestDate">'.$totalnewRequest.'</a>';  
          }
          $output .= '</td>';
        if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }

        $totalcomplet = count($complet);
          $output .= '<td align="right">';
          if($totalcomplet){
              $dates = "";
              if(!empty($fromdateP) AND !empty($fromdateP)){
                $dates = '&fromDate='.$fromdateP.'&toDate='.$todateP.'';
              }
              $categoryGet = "";
              if(!empty($categoryPost)){
                $categoryGet = "&category=".$categoryPost."";
              }
              $output .= '<a target="_blank" class="anchor" href="stm_supp_det.php?supps='.$sups['id'].''.$dates.''.$categoryGet.'&completedDate">'.$totalcomplet.'</a>';  
          }
          $output .= '</td>';
         if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }
         $totalpendin = count($pendin);
         $output .= '<td align="right">';
          if($totalpendin){
              $dates = "";
              if(!empty($fromdateP) AND !empty($fromdateP)){
                $dates = '&fromDate='.$fromdateP.'&toDate='.$todateP.'';
              }
              $categoryGet = "";
              if(!empty($categoryPost)){
                $categoryGet = "&category=".$categoryPost."";
              }
              $output .= '<a target="_blank" class="anchor" href="stm_supp_det.php?supps='.$sups['id'].''.$dates.''.$categoryGet.'&pendingDate">'.$totalpendin.'</a>';  
          }
          $output .= '</td>';
      $output .= '</tr>';
    }
    
  }
  echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "showmore"){
$row = $_POST['row'];
  $suppPost = "";
  if(isset($_POST['supplierPost'])){
    $suppPost = $_POST['supplierPost'];
  }
  $fromdateP = "";
  if(isset($_POST['fromDate'])){
    $fromdateP = $_POST['fromDate'];  
  }
  $todateP = "";
  if(isset($_POST['fromDate'])){
    $todateP = $_POST['toDate'];  
  }
  $categoryPost = "";
  if(isset($_POST['catPost'])){
    $categoryPost  = $_POST['catPost'];  
  }
  

$rowperpage = 11;

// selecting posts
$query = 'SELECT * FROM stm_supplier limit '.$row.','.$rowperpage;
$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$output = '';

foreach ($result as $sups) {
  $output .= '<tr class="post" id="post_'.$sups['id'].'">';
        $output .= '<td>'.$sups['supplierName'].'</td>';
          if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID != "'.$stInactive['id'].'"');
          }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID != "'.$stInactive['id'].'"');
          }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID != "'.$stInactive['id'].'"');
          }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $newRequest = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
          }
          $totalnewRequest = count($newRequest);
          $output .= '<td align="right">';
          if($totalnewRequest){
              $dates = "";
              if(!empty($fromdateP) AND !empty($fromdateP)){
                $dates = 'fromDate='.$fromdateP.'&toDate='.$todateP.' ';
              }
              $categoryGet = "";
              if(!empty($categoryPost)){
                $categoryGet = "&category=".$categoryPost."";
              }
              $output .= '<a target="_blank" class="anchor" href="stm_supp_det.php?'.$dates.''.$categoryGet.'&newRequestDate">'.$totalnewRequest.'</a>';  
          }
          $output .= '</td>';
        if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $complet = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID = "'.$reviewed['id'].'" AND taskStatusID != "'.$stInactive['id'].'"');
         }

        $totalcomplet = count($complet);
          $output .= '<td align="right">';
          if($totalcomplet){
              $dates = "";
              if(!empty($fromdateP) AND !empty($fromdateP)){
                $dates = 'fromDate='.$fromdateP.'&toDate='.$todateP.' ';
              }
              $categoryGet = "";
              if(!empty($categoryPost)){
                $categoryGet = "&category=".$categoryPost."";
              }
              $output .= '<a target="_blank" class="anchor" href="stm_supp_det.php?'.$dates.''.$categoryGet.'&completedDate">'.$totalcomplet.'</a>';  
          }
          $output .= '</td>';
         if(!empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskTypeID = "'.$categoryPost.'" AND taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(empty($categoryPost) AND !empty($fromdateP) AND !empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskCreationDate BETWEEN "'.$fromdateP.'" AND "'.$todateP.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(!empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskTypeID = "'.$categoryPost.'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }else if(empty($categoryPost) AND empty($fromdateP) AND empty($todateP)){
            $pendin = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_tasks','taskSupplierID = "'.$sups['id'].'" AND taskStatusID NOT IN ('.$reviewed['id'].','.$stInactive['id'].')');
         }
         $totalpendin = count($pendin);
         $output .= '<td align="right">';
          if($totalpendin){
              $dates = "";
              if(!empty($fromdateP) AND !empty($fromdateP)){
                $dates = 'fromDate='.$fromdateP.'&toDate='.$todateP.' ';
              }
              $categoryGet = "";
              if(!empty($categoryPost)){
                $categoryGet = "&category=".$categoryPost."";
              }
              $output .= '<a target="_blank" class="anchor" href="stm_supp_det.php?'.$dates.''.$categoryGet.'&pendingDate">'.$totalpendin.'</a>';  
          }
          $output .= '</td>';
      $output .= '</tr>'; 
}

echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "leaveType"){
  
  $list = $objUser->SingleDataWhere('stm_attendance_user_details','userID = "'.$_POST['user'].'" AND month > DATE_SUB(NOW(), INTERVAL 1 MONTH)');

  $month = $list['month'];
  echo $month;

}     
if(isset($_POST['post_m']) && $_POST['post_m'] == "updateAnnounce"){
  $query = $objAnnouce->stm_update_annouce($_POST['id'],$_POST['detail'],$_POST['title']);

  if($query){
    echo '1';
  }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "editAnnounce"){
  $data = $DB_HELPER_CLASS->SingleDataWhere('stm_announcements','id = "'.$_POST['id'].'"');
  $data_array = array("id"=>$data['id'],"title" => $data['title'], "detail" => $data['detail']);
  echo json_encode($data_array);
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "deleteAnnounce"){
  $qury = $objAnnouce->stm_remove_annoucement($_POST['id']);
  if($qury){
    echo '1';
  }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "announceStatus"){
  $qury = $objAnnouce->stm_update_annoucement($_POST['annouceID'],$_POST['statusID']);
  if($qury){
    echo '1';
  }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "saveAnnounce"){
  $createdBy = date('Y-m-d H:i:s');
  $annouceQuery = $objAnnouce->stm_add_annoucement($_POST['title'],addslashes($_POST['detail']),$_POST['status'],$createdBy);
  $lastID = $DB_HELPER_CLASS->lastID();
  if($lastID){
    echo "1";
  }
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "search_content"){
  $output = '';
  if($_POST['searchValue']){
    $que = 'SELECT * FROM stm_tasks WHERE taskContent LIKE "%'.$_POST['searchValue'].'%"';

    $statement = $db->prepare($que);
    $statement->execute();
    $result = $statement->fetchAll();
    
    $output .= '
    <div class="table-responsive">
    <table class="table table-striped table-sm" id="userTable">
      <tr>
        <th>TASK#</th>
        <th>TITLE</th>
      </tr>
    ';
    foreach ($result as $rows) {
      $output .= '<tr>';
        $output .= '<td><a class="anchor" target="_blank" href="stmtaskdetail.php?id='.$rows['id'].'&view">'.$rows['id'].'</a></td>';
        $output .= '<td>'.$rows['taskName'].'</td>';
      $output .= '</tr>';
    }
    $output .= '</table>';
    $output .= '</div>';
  }
  
  echo $output;
}     
if(isset($_POST['post_m']) && $_POST['post_m'] == "copyTask"){

  $taskData = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks','id = "'.$_POST['taskID'].'"');
  $taskCreationDate = date('Y-m-d');
  $task_query = $objUser->stm_addTask(addslashes($taskData['taskName']),addslashes($taskData['taskDescription']),$taskData['taskTypeID'],$taskData['taskListingTypeID'],$session_id,$taskData['taskPriorityID'],$taskData['taskBrandID'],$taskData['taskOurBrandID'],
    $taskData['taskSupplierID'],$taskData['taskStatusID'],
    $taskCreationDate);
  
  $lastID = $DB_HELPER_CLASS->lastID();

  if($task_query){
    
    $assignees = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID = "'.$_POST['taskID'].'"');

    foreach ($assignees as $data) {
      
      $subTasksData = $objUser->stm_addSubTask($data['subTaskID'],
        $data['subTaskDescription'],$lastID,$data['taskchannelID'],$data['taskstoreID'],$data['taskuserID'],$data['taskStatusID'],$data['taskSupervisorID'],$taskCreationDate,$data['taskDeadline']);
      
      if($subTasksData){
        echo "1";
      }
    
    }
  }
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "copyAssignee"){

  $asigneeData = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned','id = "'.$_POST['assigneeId'].'"');
  
  $data = $objUser->stm_addSubTask($asigneeData['subTaskID'],
        addslashes($asigneeData['subTaskDescription']),
        $asigneeData['taskID'],
        $asigneeData['taskchannelID'],
        $asigneeData['taskstoreID'],
        $asigneeData['taskuserID'],
        $asigneeData['taskStatusID'],
        $asigneeData['taskSupervisorID'],
        $asigneeData['taskCreationDate'],
        $asigneeData['taskDeadline']
        );
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "listingContent"){


  $objUser->updateContent($_POST['taskID'],$_POST['listingContent']);
  // $data = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks','id = "'.$_POST['taskID'].'"');
  // echo $data['taskContent'];
  echo "1";
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "replymessage"){

  $objUser->stm_message($taskID,$message,$createdBy,$recipient,$createdOn);

  $data_indent_level = $DB_HELPER_CLASS->SingleDataWhere('stm_reply_message','messageID = "'.$_POST['messageID'].'"');

  $db_indent_level = "";
  if(!$data_indent_level['indentLevel']){
    $db_indent_level = 1;
  }else{
    $db_indent_level = $data_indent_level['indentLevel'] + 1;
  }
  $createdOn = date('Y-m-d H:i:s');
  $objUser->stm_reply_message($_POST['messageID'],$db_indent_level,$session_id,$createdOn);

}


if(isset($_POST['post_m']) && $_POST['post_m'] == "directMessageDetailSent"){

  $objUser->updateUserMessage($_POST['id'],"1");

  $messageBody = $DB_HELPER_CLASS->SingleDataWhere('stm_task_comments','id = "'.$_POST['id'].'"');
  $createdOn = date('Y-m-d H:i:s',strtotime($messageBody['createdOn']));

  $user = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$messageBody['createdBy'].'"');

  $ary = array("date" => $createdOn, "from" => $user['userName'],
  "message" => $messageBody['message']);

  echo json_encode($ary);
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "replyPost"){

  $messageBody = $DB_HELPER_CLASS->SingleDataWhere('stm_message_details','id = "'.$_POST['pID'].'"');

  $createdOn = date('Y-m-d H:i:s');



  if($messageBody['msgFrom'] == $_POST['userID']){
    $toMessage = $messageBody['msgTo'];
  }else{
    $toMessage = $messageBody['msgFrom'];
  }

  $objUser->stm_reply_message($_POST['thread'],$_POST['msg'],$_POST['userID'],$toMessage,0,$createdOn);

  $lastID = $DB_HELPER_CLASS->lastID();

  $lastmessageBody = $DB_HELPER_CLASS->SingleDataWhere('stm_message_details',"id = '$lastID'");

  $userFrom = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$lastmessageBody['msgFrom'].'"');

  $userTo= $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$lastmessageBody['msgTo'].'"');
  $output  = "";
  $output .= '<div class="row-fluid">';
  $output .= '<div class="span2"></div>';
  $output .= '<div class="span8">';
  $output .= '<div class="row-fluid">';
  $output .= '<div class="span4">';
  $output .= '<p>
              <b>'.date('d/m/Y H:i:s', strtotime($lastmessageBody['createdOn'])).'&nbsp&nbspFrom: '.$userFrom['userName'].'<br>'.'To:'.$userTo['userName'].'</b>'.'</p>';           
  $output .='</div></div>';
  $output .= '<div class="row-fluid">';
  $output .= '<div class="span8"><div class="row-fluid">'.$lastmessageBody['message'].'</div></div></div><hr>
    </div>';
  $output .= '<div class="span2"></div></div>';

  echo $output;

}      
if(isset($_POST['post_m']) && $_POST['post_m'] == "directMessage"){

  date_default_timezone_set("Asia/Karachi");
  $time = date('Y-m-d H:i:s'); 
  
  $query = $objUser->stm_message($_POST['taskID'],$time);

  $lastID = $DB_HELPER_CLASS->lastID();

  $query1 = $objUser->stm_reply_message($lastID,$_POST['msg'],$_POST['userID'],$_POST['assignedTo'],0,$time);
 
  if($query){
     echo "1"; 
  }
}     
if(isset($_POST['post_m']) && $_POST['post_m'] == "postMessage"){

  date_default_timezone_set("Asia/Karachi");
  $time = date('Y-m-d H:i:s');

  $query = $objUser->stm_message($_POST['taskID'],$time);

  $lastID = $DB_HELPER_CLASS->lastID();

  $query = $objUser->stm_reply_message($lastID,addslashes($_POST['msg']),$_POST['userID'],$_POST['assignedTo'],0,$time);

  $objUser->updateUserMessage($lastID,"0");

  $comments = $DB_HELPER_CLASS->SingleDataWhere('stm_message_details','messageID = "'.$lastID.'"');
  $createdBy = $DB_HELPER_CLASS->SingleDataWhere("stm_users","id = '".$comments['msgFrom']."'");

  $assignedTo = $DB_HELPER_CLASS->SingleDataWhere("stm_users","id = '".$comments['msgTo']."'");
  
  $output  = "";
  $output .= '<div class="row-fluid">';
  $output .= '<div class="span2"></div>';
  $output .= '<div class="span8">';
  $output .= '<div class="row-fluid">';
  $output .= '<div class="span4">';
  $output .= '<p>
              <b>'.date('d/m/Y H:i:s', strtotime($comments['createdOn'])).'&nbsp&nbspFrom: '.$createdBy['userName'].'<br>'.'To:'.$assignedTo['userName'].'</b>'.'</p>';           
  $output .='</div></div>';
  $output .= '<div class="row-fluid">';
  $output .= '<div class="span8"><div class="row-fluid">'.$comments['message'].'</div></div></div><hr>
    </div>';
  $output .= '<div class="span2"></div></div>';

  echo $output;

}

if(isset($_POST['post_m']) && $_POST['post_m'] == "issueNote"){
  
  $content = $objUser->updatePreListingNote($_POST['row_id'],$_POST['note']);
  if($content){
    echo '1';
  }
}     
if(isset($_POST['post_m']) && $_POST['post_m'] == "mapping_area_filter"){
  $output = "";
  $channelID = $_POST['channelID'];
  $stores = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_stores","storeChannelID = '$channelID'");
  $output .= "<select class='form-control store_names' style='width:20%; float:left;'>";
  $output .= "<option value=''>Select Store</option>";
  foreach ($stores as $storesList) {
    $output .= "<option value='".$storesList['id']."'>".$storesList['storeName']."</option>";
  }
  $output .= "</select>&nbsp&nbsp";

  $status = $DB_HELPER_CLASS->allRecordsOrderBy("stm_linked_statuses","statusName DESC");
  $output .= "<select class='form-control statusStore' style='width:20%; float:left; margin-left:3px;'>";
  $output .= "<option value=''>Select Status</option>";
  foreach ($status as $statusList) {
    $output .= "<option value='".$statusList['id']."'>".$statusList['statusName']."</option>";
  }
  $output .= "</select>&nbsp&nbsp";
  $output .= "<button type='button' class='btn btn-primary filterButton' data-id='".$channelID."' style='float:left; margin-left:3px;'>Filter</button>&nbsp";
  $output .= "<a href='stmlinkedsku.php' class='btn btn-warning' style='float:left; margin-left:3px;'>Reset</a>";
  echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "sync_sku"){
  $query = $objUser->sync_sku_update($_POST['row_id'],$_POST['statusID']);
  $data = $DB_HELPER_CLASS->SingleDataWhere("stm_prelistings","id = '".$_POST['row_id']."'");

  $statusName = $DB_HELPER_CLASS->SingleDataWhere('stm_linked_statuses','id = "'.$data['LinkedStatusID'].'"');
  
  echo $statusName['statusName'];
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "update_detail_prelst"){

   $query = $objUser->stm_update_prelisting($_POST['taskdetailID'],addslashes($_POST['ref_url']),addslashes($_POST['ref_title']),$_POST['productCode'],$_POST['channel'],$_POST['store'],$_POST['salePrice'],addslashes($_POST['storeSKU']),addslashes($_POST['linkedSKU']),$_POST['EAN'],$_POST['listingType'],$_POST['ASIN'],$_POST['purchasePrice'],$_POST['quantity']);
   
   $data = $DB_HELPER_CLASS->SingleDataWhere('stm_prelistings',"id = '".$_POST['taskdetailID']."' ");

   $listingType = $DB_HELPER_CLASS->SingleDataWhere('stm_listingtype',"id = '".$data['listingTypeID']."'");

   $aray = array("type" =>$listingType['listingTypeName']);

   echo json_encode($aray);

}
if(isset($_POST['post_m']) && $_POST['post_m'] == "update_detail_row"){

   $finalFileName = "";
   $output = "";
   if(!empty($_FILES['file_name']['name']))
   {
    $fileName = $_FILES['file_name']['name'];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(uniqid());
    $fileDest = '../../images/'.$newFileName.'.'.$ext;
    $justFileName = $newFileName.'.'.$ext;

    if ($_FILES["file_name"]["size"] > 5000000) {
      echo "Sorry, your file is too large. Please upload less then 5MB";
    }else{
      
        if($_POST['old_file']){
          unlink('../../images/'.$_POST['old_file']);  
        }
        
        move_uploaded_file($_FILES['file_name']['tmp_name'], $fileDest);
        $finalFileName .= $justFileName;
    }         
   }else{
    $finalFileName .= $_POST['old_file'];
   }

   $query = $objUser->stm_update_taskDetails($_POST['taskdetailID'],addslashes($_POST['ref_url']),$_POST['productCode'],$_POST['amzPrice'],$_POST['ebayPrice'],$_POST['webPrice'],$_POST['storeSKU'],$_POST['linkedSKU'],$_POST['EAN'],$_POST['ASIN'],$_POST['purchasePrice'],$_POST['quantity'],$finalFileName);
   
   $detailImage = $DB_HELPER_CLASS->SingleDataWhere('stm_task_details','id = "'.$_POST['taskdetailID'].'"');
   if($detailImage['attachement']){
    $extension = pathinfo($detailImage['attachement'], PATHINFO_EXTENSION);
    $imgExtArr = ['jpg', 'jpeg', 'png','svg','webp'];
    if(in_array($extension, $imgExtArr)){
      $output .= '<img class="file_show" src="images/'.$detailImage['attachement'].'">';
    }else{
      $output .=  "<span class='file_show'>".$detailImage['attachement']."</span>";
    }
   }
    echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "add_prelisting"){

  $status = $DB_HELPER_CLASS->SingleDataWhere('stm_linked_statuses','statusName = "Unlinked"');

  $query = $objUser->stm_preListing($_POST['taskID'],addslashes($_POST['ref_url']),addslashes($_POST['ref_title']),$_POST['productCode'],$_POST['channels'],$_POST['stores'],$_POST['salePrice'],
    addslashes($_POST['storeSKU']),addslashes($_POST['linkedSKU']),$_POST['EAN'],$_POST['listingType'],$status['id'],$_POST['ASIN'],$_POST['purchasePrice'],$_POST['quantity']);

  $lastID = $DB_HELPER_CLASS->lastID();

  $prelisting = $DB_HELPER_CLASS->SingleDataWhere("stm_prelistings","id = '$lastID'");
  $output = "";

  $output .= "<tr class='row_table_".$lastID."'>";

  $output .= '<td id="ref_url_db">';  
  $output .= '<input type="hidden" class="detail_id_'.$lastID.'" value="'.$lastID.'" style="display:none;">';
  $output .= '<input type="hidden" class="task_ID" value="'.$_POST['taskID'].'" style="display:none;">';
  $output .=' <input type="text" value="'.$prelisting['refURL'].'" class="form-control form-control-sm ref_url_'.$lastID.'" id="ref_urls" disabled>';
  $output .= "</td>";

  $output .= '<td id="ref_title_db">';  
  $output .= '<input type="text" class="form-control form-control-sm refTitle_'.$lastID.'" value="'.$prelisting['refTitle'].'" id="refTitle" disabled>';
  $output .= "</td>";

  $output .= '<td id="Db_td_productCode">';  
  $output .= '<input type="text" class="form-control form-control-sm productCode_'.$lastID.'" value="'.$prelisting['productCode'].'" id="productCode3" disabled>';
  $output .= "</td>";

  $output .= '<td>';  
  $output .= '<input type="text" class="form-control form-control-sm purchasePrice_'.$lastID.'" value="'.$prelisting['purchasePrice'].'" id="purchasePrice3" disabled>';
  $output .= "</td>";

  $output .= '<td>';  
  $output .= '<input type="text" value="'.$prelisting['quantity'].'" class="form-control form-control-sm quantity_'.$lastID.'" id="quantity3" disabled>';
  $output .= "</td>";

  $output .= '<td>';  
  $output .= '<select class="form-control form-control-sm channels_row" id="channel_'.$lastID.'" data-id="'.$lastID.'" disabled><option value="0">Select</option>';
    $dataC = $DB_HELPER_CLASS->allRecordsOrderBy("stm_channels","channelName ASC");
    foreach ($dataC as $channelsData) {
      $slect = "";
      if($channelsData['id'] == $prelisting['channelID']){
        $slect = "selected = 'selected'";
      }
      $output .= '<option value="'.$channelsData['id'].'" '.$slect.'>
                    '.$channelsData['channelName'].'</option>';  
    }
  $output .= '</select>';
  $output .= "</td>";

  $output .= '<td>';  
  $output .= '<select class="form-control form-control-sm stores_'.$lastID.'" id="store_'.$lastID.'" data-id="'.$lastID.'" disabled>';
  $storeData = $DB_HELPER_CLASS->SingleDataWhere("stm_stores","id = '".$prelisting['storeID']."'");
    
    if($storeData['storeName']){
      $output .='<option value="'.$storeData['id'].'">'.$storeData['storeName'].'</option>';  
    }else{
      $output .='<option value="0">Select</option>';
    }    
   
  $output .= '</select>';
  $output .= "</td>";

  $output .= '<td>';
    $output .= '<input type="text" class="form-control form-control-sm salePrice_'.$lastID.'" value="'.$prelisting['salePrice'].'" id="salePrice3" disabled>';
  $output .= "</td>";

  $output .= '<td>';
    $output .= '<input type="text" class="form-control form-control-sm storeSKU_'.$lastID.'" value="'.$prelisting['storeSKU'].'" id="storeSKU3" disabled>';
  $output .= "</td>";

  $output .= '<td>';
    $output .= '<input type="text" class="form-control form-control-sm linkedSKU_'.$lastID.'" value="'.$prelisting['linkedSKU'].'" id="linkedSKU3" disabled>';
  $output .= "</td>";

  $output .= '<td>';
    $output .= '<input type="text" class="form-control form-control-sm EAN_'.$lastID.'" value="'.$prelisting['EAN'].'" id="EAN3" disabled>';
  $output .= "</td>";

  $output .= '<td>';
    $output .= '<input type="text" class="form-control form-control-sm ASIN_'.$lastID.'" value="'.$prelisting['ASIN'].'" disabled>';
  $output .= "</td>";

  if($prelisting['listingTypeID'] == "1"){
    $color ="style='background-color:#9999ff; color:white;'";
  }else if($prelisting['listingTypeID'] == "3"){
    $color ="style='background-color:#70dbdb'";
  }else if($prelisting['listingTypeID'] == "2"){
    $color ="style='background-color:#4d79ff; color:white;'";
  }

  $output .= '<td>';
    $output .= '<select class="form-control form-control-sm listingType_'.$lastID.'" id="listingType" '.$color.' disabled>';
    $lst = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_listingtype','listingTypeName !="Variation" ORDER By id DESC');
    foreach($lst as $lstType){
      $select = "";
      if($prelisting['listingTypeID'] == $lstType['id']){
        $select = "selected = 'selected'";
      }
      $output .= '<option id="" value="'.$lstType['id'].'" '.$select.'>
                  '.$lstType['listingTypeName'].'</option>';
    }
  $output .= '</td>'; 

  $output .= '<td>';
    $output .= '<svg class="edit_detail_prelst" id="clone_'.$lastID.'" style="color:#04AA6D; display: inline-block;" data-id="'.$lastID.'" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><button class="btn btn-success btn-sm update_detail_prelst" id="update_detail_prelst_'.$lastID.'" data-id="'.$lastID.'" type="button" style="display: none;">Save</button><svg class="prelst_rem_detail" style="color:red; display: inline-block;" id="clone_rem_'.$lastID.'" data-id="'.$lastID.'" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
  $output .= '</td>';

  $output .= "</tr>";

  echo $output;

}
if(isset($_POST['post_m']) && $_POST['post_m'] == "task_prices_detail"){

   $finalFileName = "";
   if(!empty($_FILES['file_name']['name']))
   {
    $fileName = $_FILES['file_name']['name'];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(uniqid());
    $fileDest = '../../images/'.$newFileName.'.'.$ext;
    $justFileName = $newFileName.'.'.$ext;

    if ($_FILES["file_name"]["size"] > 5000000) {
      echo "0";
    }else{

        move_uploaded_file($_FILES['file_name']['tmp_name'], $fileDest);
        $finalFileName .= $justFileName;
    }         
   }

  $query = $objUser->stm_insert_task_details($_POST['taskID'],$_POST['ref_url'],$_POST['productCode'],$_POST['amzPrice'],$_POST['ebayPrice'],$_POST['webPrice'],$_POST['storeSKU'],$_POST['linkedSKU'],$_POST['EAN'],$_POST['ASIN'],$_POST['purchasePrice'],$_POST['quantity'],$finalFileName);

  if($query){
     echo "1";
  }else{
    echo "0";
  }

}
if(isset($_POST['post_m']) && $_POST['post_m'] == "sku_prices"){
  
  $data = $DB_HELPER_CLASS->SingleDataWhere('stm_skuprices','storeSKU = "'.$_POST['storeSKU'].'" OR linkedSKU = "'.$_POST['linkedSKU'].'"');
  $linkedSK = $data['linkedSKU'];
  $storeSKU = $data['storeSKU'];

  if($storeSKU == $_POST['storeSKU']){
    echo "store exist";
  }else if($linkedSK == $_POST['linkedSKU']){
    echo "linked sku";
  }else{

    $query = $objUser->stm_insert_skuprices($_POST['taskID'],$_POST['storeSKU'],$_POST['linkedSKU'],$_POST['Ean_barcode'],$_POST['skutype'],$_POST['sale_price'],$_POST['purchase_price'],$_POST['qty']);

    if($query){
      echo "1";
    }else{
      echo "0";
    }
    
  }

}       

if(isset($_POST['post_m']) && $_POST['post_m'] == "taskDescription"){
  
  $desData = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned', 'id = "'.$_POST['descID'].'"');
  
  $ary = array("subTaskDescription" => $desData['subTaskDescription']);
  echo json_encode($ary);

}    

if(isset($_POST['post_m']) && $_POST['post_m'] == "updateBrand"){
  $brandData = $DB_HELPER_CLASS->SingleDataWhere('stm_brands', 'id = "'.$_POST['id'].'"');
  $supplierData = $DB_HELPER_CLASS->SingleDataWhere('stm_supplier','id = "'.$brandData['supplierID'].'"');
  $ary = array("brandName" => $brandData['brandName'],"supplierID"=>$supplierData['id'],"supplierName" => $supplierData['supplierName']);

  echo json_encode($ary);
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "FinalupdateBrand"){
  
  if($_POST['supplier'] != ""){
    $supplier = $_POST['supplier'];
  }else if($_POST['supplier'] == ""){
    $supplier = $_POST['supplier_ID'];
  }

  $supplierData = $objUser->update_brands($_POST['brandID'],$_POST['brandName'],$supplier);
  
  if($supplierData){
    echo "1";
  }
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "updatePrices"){
  
 
  $updatePrices = $objUser->stm_update_skuprices($_POST['row_id'],$_POST['storeSKU'],$_POST['linkedSKU'],$_POST['EAN'],$_POST['typeSKU'],$_POST['salePrice'],$_POST['purchasePrice'],$_POST['quantity']);
  
  if($updatePrices){
    echo "1";
  }
  
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "FinalupdateSupplier"){

  
  if($_POST['supplierType'] != ""){
    $supplierType = $_POST['supplierType'];
  }else if($_POST['supplierType'] == ""){
    $supplierType = $_POST['supplierTypeID'];
  }

  if($_POST['supervisorID'] != ""){
    $supervisorID = $_POST['supervisorID'];
  }else if($_POST['supervisorID'] == ""){
    $supervisorID = 0;
  }

  $supplierData = $objUser->update_suppliers($supervisorID,$_POST['supplierID'],$_POST['supplierName'],$supplierType);
  
  if($supplierData){
    echo "1";
  }
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "updateSupplier"){
  $supplierData = $DB_HELPER_CLASS->SingleDataWhere('stm_supplier', 'id = "'.$_POST['id'].'"');
  
  $supplierTypeData = $DB_HELPER_CLASS->SingleDataWhere('stm_suppliers_type','id = "'.$supplierData['supplierTypeID'].'"');

  $supervisor = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$supplierData['userID'].'"');

  $ary = array("supplierName" => $supplierData['supplierName'],"supplierTypeID"=>$supplierTypeData['id'],"supplierType" => $supplierTypeData['supplierType'],"supervisor" => $supervisor['userName']);

  echo json_encode($ary);
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "stmsubtask"){
 
  $query  = $objUser->subtaskUPdate($_POST['stmtasktypes'],$_POST['id']);
  
  if($query){
      echo "1";
  }    
 
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "stmtasktypes"){
 
  $query  = $objUser->tasktypeUpdae($_POST['stmtasktypes'],$_POST['id']);
  
  if($query){
      echo "1";
  }    
 
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "channelChange"){
  $output = "";
  $channelID = $_POST['channelID'];
  $stores_table = "stm_stores";
  $storeWH = "storeChannelID = '$channelID'";

  if($channelID){
    $data = $DB_HELPER_CLASS->allRecordsRepeatedWhere($stores_table, $storeWH);
    $output .="<option value='0'>Select Store</option>";
    if($data){
      foreach($data as $storeList){
        $storeID = $storeList['id'];
        $storeName = $storeList['storeName'];
        $output .= "<option value=".$storeID.">".$storeName."</option>";  
      }
    }
    
    
  }else{
    $output .="<option value='0'>Select Store</option>";
  }

  echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "supplierChange"){
  $output = "";
  $supplierID = $_POST['supplierID'];
  
  if($supplierID){
    $data = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_brands', "supplierID = '$supplierID'");

    $output .="<option value='0'>Select Brand</option>";
    if($data){
      foreach($data as $storeList){
        $brandID = $storeList['id'];
        $brandName = $storeList['brandName'];
        $output .= "<option value=".$brandID.">".$brandName."</option>";  
      }
    }
    
  }else{
    $output .="<option value='0'>Select Brand</option>";
  }
   echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "supplier_type_name"){
 $output = "";
  $supplier_type_name = $_POST['supplier_type_name'];

  if($supplier_type_name){
    $data = $DB_HELPER_CLASS->allRecordsWhereOrderBy("stm_supplier", "supplierTypeID = '$supplier_type_name' ORDER BY supplierName ASC ");
    $output .="<option value='0'>Select Supplier</option>";
    if($data){
      foreach($data as $supplier_data){
        $supplier_id = $supplier_data['id'];
        $supplier_name = $supplier_data['supplierName'];
        $output .= "<option value=".$supplier_id.">".$supplier_name."</option>";  
      }
    } 
  }else{
    $output .="<option>Select Supplier</option>";
  }
  echo $output; 
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "task_new_assignee"){
    
    $output = "";
    $taskCreationDate = date("Y-m-d");
    $taskDeadline = date("Y-m-d", strtotime($_POST["subDeadline"]));

    $tb = "stm_statuses";
    $wher = "statusName = '1-New Task'";
    $dataStatus =  $DB_HELPER_CLASS->SingleDataWhere($tb, $wher);
    
      $data = $objUser->stm_addSubTask(
        $_POST['subtask'],
        addslashes($_POST['description_new']),
        $_POST['taskID'],
        $_POST['channels'],
        $_POST['stores'],
        $dataStatus['id'],
        $_POST['user'],
        $taskCreationDate,
        $taskDeadline
        );

      $lastID = $DB_HELPER_CLASS->lastID();
      if($lastID){
        $dataUsers = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$_POST['user'].'"');
      }  
      $tbl = "stm_taskassigned";
      $whe = "taskID = '".$_POST['taskID']."' AND id = '$lastID' ORDER BY id ASC";
      
      $subTasksList = $DB_HELPER_CLASS->SingleDataWhere($tbl,$whe);
      
          $tbUser = "stm_users";
          $wher = "id = '".$subTasksList['taskuserID']."'";
          $userData = $DB_HELPER_CLASS->SingleDataWhere($tbUser, $wher);

          $tbc = "stm_channels";
          $wherc = "id = '".$subTasksList['taskchannelID']."'";
          $cData = $DB_HELPER_CLASS->SingleDataWhere($tbc, $wherc);

          $tbS = "stm_stores";
          $wherS = "id = '".$subTasksList['taskstoreID']."'";
          $SData = $DB_HELPER_CLASS->SingleDataWhere($tbS, $wherS);

          $tbSt = "stm_statuses";
          $wherSt = "id = '".$subTasksList['taskStatusID']."'";
          $StData = $DB_HELPER_CLASS->SingleDataWhere($tbSt, $wherSt);
          $statusClass="";
          if($StData['statusName'] == "6-Reviewed"){
          $statusClass .= "primary";
        }
        if($StData['statusName'] == "1-New Task"){
          $statusClass .= "important";
        }
        if($StData['statusName'] == "3-In Progress" OR $StData['statusName'] == "2-Started"){
          $statusClass .= "warning";
        }
        if($StData['statusName'] == "4-Ended" OR $StData['statusName'] == "5-Done"){
          $statusClass .= "success";
        }

          $creatDate = date("d M Y", strtotime($subTasksList['taskCreationDate']));
          $endDate = date("d M Y", strtotime($subTasksList['taskDeadline']));

          $output .= "<tr>";
          $output .= "<td>".$userData['userName']."</td>";
          $output .= "<td>".$subTasksList['subTaskName']."</td>"; 
          $output .= "<td>".$cData['channelName']."</td>";
          $output .= "<td>".$SData['storeName']."</td>";
          $output .= "<td>".$endDate."</td>";
          $output .= "<td><a class='subtask_Desc' data-id='".$subTasksList['id']."'>Click</a></td>";
          $output .= "<td>".$creatDate."</td>";
          $output .= "</tr>";
    
      echo $output;
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "NewTaskEmail"){

  
  $tasks = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks','id = "'.$_POST['taskID'].'"');

  $dataTaskUsers = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$tasks['taskAssignedBy'].'"');

  $dataTaskSupervisor = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$tasks['taskSupervisorID'].'"');

      $mail = new PHPMailer(); 
      //$mail->SMTPDebug=3;
      $mail->IsSMTP(); 
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'ssl'; 
      $mail->Host = "mail.swiftitsol.net";
      $mail->Port = "465"; 
      $mail->IsHTML(true);
      $mail->Subject = 'STM - New Task Created';

      $mail->CharSet = 'UTF-8';
      $mail->Username = "stm@swiftitsol.net";
      $mail->Password = '*+on2&12#$$r';
      $mail->SetFrom("stm@swiftitsol.net");

      
      $username = array();
      $assignees = $DB_HELPER_CLASS->onlyDISTINCTRecords('stm_taskassigned','taskID = "'.$_POST['taskID'].'"');
      foreach($assignees as $allassignees){

        $allassigneesName = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_users','id = "'.$allassignees['taskuserID'].'"');
        foreach($allassigneesName as $assignedUserInfo){
           $mail->AddAddress($assignedUserInfo['userEmail']); 
           array_push($username,$assignedUserInfo['userName']);
        }
        

      }
      $mail->AddAddress($dataTaskSupervisor['userEmail']); 
      $mail->AddAddress($dataTaskUsers['userEmail']); 
      
      $task_assignee = implode(',', $username);

      $msg = "<h4>Dear Team Member(s):</h4>";
        $msg .= "<p>Please note that a New Task has been created on the STM with the following details and this email is sent to you because you are either the creator of this task or a supervisor to finally review and sign off the task or one of the assignees to work on this task. You may click on the Task ID or the Task Title to go directly to the task page, please make sure you are logged-in in order to access the system.</p><br>";
        $msg .= "<ul>";
        $msg .= "<li>Task ID : ".$_POST['taskID']."</li>";
        $msg .= "<li>Created By : ".$dataTaskUsers['userName']."</li>";
        $msg .= "<li>Task Title : ".$tasks['taskName']."</li>";
        $msg .= "<li>Supervisor : ".$dataTaskSupervisor['userName']."</li>";
        $msg .= "<li>Assignees : ".$task_assignee."</li>";
        $msg .= "</ul>";
        $msg .= "<p>Best regards</p><br>";
        $msg .= "<p>System Administrator</p>";
        $msg .= "<p>SITS Task Management (STM)</p>";
        $msg .= "<p>a product of Swift IT Solutions Pvt. Ltd.</p>";
        $mail->Body = $msg;
      $mail->SMTPOptions=array('ssl'=>array(
          'verify_peer'=>false,
          'verify_peer_name'=>false,
          'allow_self_signed'=>false
      ));
      if(!$mail->Send()){
          echo $mail->ErrorInfo;
      }
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "new_sub_Task"){

    $output = "";
    $taskCreationDate = date("Y-m-d");
    $taskDeadline = date("Y-m-d", strtotime($_POST["subDeadline"]));

    $tb = "stm_statuses";
    $wher = "statusName = '1-New Task'";
    $dataStatus =  $DB_HELPER_CLASS->SingleDataWhere($tb, $wher);
    
      $data = $objUser->stm_addSubTask($_POST['subtask'],
        addslashes($_POST['des_new_aasignee']),
        $_POST['taskID'],
        $_POST['channels'],
        $_POST['stores'],
        $_POST['user'],
        $dataStatus['id'],
        $_POST['supervisor'],
        $taskCreationDate,
        $taskDeadline
        );
      
      $lastID = $DB_HELPER_CLASS->lastID();
      if($lastID){
        $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");

        $task_status_data = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks',"id = '".$_POST['taskID']."'");

        //if($stat['id'] == $task_status_data['taskStatusID']){

          $records = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."'");

          $totalTaskRecord = count($records);

          $recordsDone = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '12' ");
            $NewRecords = count($recordsDone);
            
            if($NewRecords == $totalTaskRecord){
              $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");
              $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);
            }else{
              $status_prog_id = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '3-In Progress'");
              $objUser->updateTaskStatus($_POST['taskID'],$status_prog_id['id']);
            }
            
        //}
        
        $task_table_data = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks','id = "'.$_POST['taskID'].'"');
          $main_status_id = $task_table_data['taskStatusID'];
            $stataus_task = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','id = "'.$main_status_id.'"');
          $tbl = "stm_taskassigned";
          $whe = "taskID = '".$_POST['taskID']."' AND id = '$lastID' ORDER BY id ASC";
      
          $subTasksList = $DB_HELPER_CLASS->SingleDataWhere($tbl,$whe);
      
          $tbUser = "stm_users";
          $wher = "id = '".$subTasksList['taskuserID']."'";
          $userData = $DB_HELPER_CLASS->SingleDataWhere($tbUser, $wher);

          $tbc = "stm_channels";
          $wherc = "id = '".$subTasksList['taskchannelID']."'";
          $cData = $DB_HELPER_CLASS->SingleDataWhere($tbc, $wherc);

          $tbS = "stm_stores";
          $wherS = "id = '".$subTasksList['taskstoreID']."'";
          $SData = $DB_HELPER_CLASS->SingleDataWhere($tbS, $wherS);



          $tbSt = "stm_statuses";
          $wherSt = "id = '".$subTasksList['taskStatusID']."'";
          $StData = $DB_HELPER_CLASS->SingleDataWhere($tbSt, $wherSt);
          $statusClass="";
          if($StData['statusName'] == "6-Reviewed"){
          $statusClass .= "primary";
        }
        if($StData['statusName'] == "1-New Task"){
          $statusClass .= "important";
        }
        if($StData['statusName'] == "3-In Progress" OR $StData['statusName'] == "2-Started"){
          $statusClass .= "warning";
        }
        if($StData['statusName'] == "4-Ended" OR $StData['statusName'] == "5-Done"){
          $statusClass .= "success";
        }

        $creatDate = date("d M Y", strtotime($subTasksList['taskCreationDate']));
        $endDate = date("d M Y", strtotime($subTasksList['taskDeadline']));

        $assigne_data = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned',"id = '$lastID'");

      // $output = "";
      // $output .= "<tr class='row_assigtr_".$lastID."'>";
      // $output .= '<td><input type="hidden" class="form-control task_id" value="'.$_POST['taskID'].'">
      //     <select name="owner" class="form-control owner_'.$assigne_data['id'].'" id="owner" style="width:100%" disabled><option value="none">Select</option>';
          
      //     $userTypes = $DB_HELPER_CLASS->allRecordsOrderBy('stm_users','userName ASC');

      //     foreach($userTypes as $list){
      //       $ownerSelected = "";
      //       if($assigne_data['taskuserID'] == $list['id'])
      //       {
      //         $ownerSelected = "selected = 'selected'";
      //       }
      //       $output .='<option value="'.$list['id'].'" '.$ownerSelected.'>'.$list['userName'].'</option>';
      //     }
      // $output .= '</select></td>';

      // $output .= '<td><select name="subtask" class="form-control subtask_'.$assigne_data['id'].'" id="subtask" style="width:100%" disabled><option value="none">Select</option>';
      //     $userTypes = $DB_HELPER_CLASS->allRecordsOrderBy('stm_subtask','subTask ASC');
      //     foreach($userTypes as $list){
      //       $subTaskSelected = "";
      //       if($assigne_data['subTaskID'] == $list['id'])
      //       {
      //         $subTaskSelected = "selected = 'selected'";
      //       }
      //       $output .='<option value="'.$list['id'].'" '.$subTaskSelected.'>'.$list['subTask'].'</option>';
      //     }
      // $output .= '</select></td>';

      // $output .= '<td><select name="channels" class="form-control channels_'.$assigne_data['id'].'" id="channel_new" style="width:100%" disabled><option value="none">Select</option>';
      //     $userTypes = $DB_HELPER_CLASS->allRecordsOrderBy('stm_channels','channelName ASC');
      //       $channelSelected = "";
      //       foreach($userTypes as $list){
      //         if($assigne_data['taskchannelID'] == $list['id'])
      //         {
      //           $channelSelected = "selected = 'selected'";
      //         }
      //         $output .='<option value="'.$list['id'].'" '.$channelSelected.'>'.$list['channelName'].'</option>';
      //       }
      // $output .= '</select></td>';


      // $output .= '<td><select name="stores" class="form-control stores_'.$assigne_data['id'].'" id="store_new" style="width:100%" disabled>';
      //     if($assigne_data['taskstoreID']){
            
      //       $dataStore = $DB_HELPER_CLASS->SingleDataWhere('stm_stores','id = "'.$assigne_data['taskstoreID'].'"');

      //         $output .='<option value="'.$assigne_data['id'].'">'.$dataStore['storeName'].'</option>';
      //     }
      // $output .= '</select></td>';
      
      // $output .= '<td><input class="form-control" type="date" id="subDeadline_'.$assigne_data['id'].'" name="date" value="'.strftime('%Y-%m-%d',strtotime($assigne_data['taskDeadline'])).'" style="width:92%" disabled></td>';

      // $output .= '<td><input type="text" class="form-control form-control-sm" id="des_new_aasignee_'.$assigne_data['id'].'" value="'.$assigne_data["subTaskDescription"].'" disabled></td>';

      // $output .= '<td><svg class="savesubTask" style="color:#04AA6D;" data-id="'.$assigne_data['id'].'" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><svg class="remsubTask" style="color:red;" data-id="'.$assigne_data['id'].'" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></td>';

      // $output .= "</tr>";
      $ary = array("main_status_id" => $stataus_task['statusName']);
      echo json_encode($ary);
  
      }//lastid

      
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "changeStatus"){
  
  $data = $DB_HELPER_CLASS->SingleDataWhere("stm_statuses", "id = ".$_POST['statusID']."");

  $datataks = $DB_HELPER_CLASS->SingleDataWhere("stm_taskassigned", "id = '".$_POST['subID']."'");
  
  if($data['statusName'] == "1-New Task"){
    $started_at = Null;
    $end_date = Null;
    $taskURL = "";
    $taskComments = "";

  }else if($data['statusName'] == "5-Done"){
    $started_at = $datataks['taskStartDate'];
    $end_date = date("Y-m-d");
    $taskURL = $datataks['taskURL'];
    $taskComments = $datataks['taskComments'];


  }else if($data['statusName'] == "2-Started"){
    
    $started_at = date("Y-m-d");
    $end_date = Null;
    $taskURL = $datataks['taskURL'];
    $taskComments = $datataks['taskComments'];
    
  }
  

  $query = $objUser->updateStatus($_POST['subID'],$_POST['statusID'],$started_at,$end_date,$taskURL,$taskComments);

 
  $records = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['id']."'");

    $totalTaskRecord = count($records);
    $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '5-Done'");
    $Approved = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = 'Approved'");
    $progress = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '3-In Progress'");
    $newtask = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");
    $startedtask = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '2-Started'");
    $recordsDone = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['id']."' AND taskStatusID = '".$stat['id']."' ");

    $DoneRecords = count($recordsDone);

    $recordsApproved = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['id']."' AND taskStatusID = '".$Approved['id']."' ");

    $totalApproved = count($recordsApproved);
    $recordsProgress = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['id']."' AND taskStatusID = '".$progress['id']."' ");

    $totalProgress = count($recordsProgress);

    $recordsNew = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['id']."' AND taskStatusID = '".$newtask['id']."' ");

    $totalNew = count($recordsNew);

    $recordsStarted = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['id']."' AND taskStatusID = '".$startedtask['id']."' ");

    $totalStarted = count($recordsStarted);

    if($totalNew == $totalTaskRecord){
      $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");
      $objUser->updateTaskStatus($_POST['id'],$stat['id']);
    }else if($totalApproved == $totalTaskRecord){
      $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '6-Reviewed'");
      $objUser->updateTaskStatus($_POST['id'],$stat['id']);
    }else if($DoneRecords == $totalTaskRecord){
      $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");
      $objUser->updateTaskStatus($_POST['id'],$stat['id']);
    }else if($totalProgress == '1' OR $totalNew == '1' OR $totalStarted == '1'){
      $objUser->updateTaskStatus($_POST['id'],$progress['id']);
    }

  $updatedDataforStatus = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned','id = "'.$_POST['subID'].'"');
  
  $userStatus = "";
  $output = "";
  $tbl1 = 'stm_statuses';
  if($updatedDataforStatus['taskStartDate'] != "" AND $updatedDataforStatus['taskEndDate'] == ""){
    $wher1 = "statusName IN ('5-Done','1-New Task') ORDER by statusName ASC";
    $userStatus = $DB_HELPER_CLASS->allRecordsRepeatedWhere($tbl1, $wher1);
    

    $output .='<select class="form-control status" style="width:100%;"><option value="none">Change</option>';
      foreach($userStatus as $list){
        $selc = "";
        if($updatedDataforStatus['taskStatusID'] == $list['id']){
            $selc .= "selected = 'selected'";
        }
        $output .= '<option value='.$list['id'].' '.$selc.'>'.$list['statusName'].'</option>';
      }
    $output .= '</select>';
  }

  $ary = array("start_date" => $updatedDataforStatus['taskStartDate'], "end_date" =>  $updatedDataforStatus['taskEndDate'], "output" => $output);

  echo json_encode($ary);

}

if(isset($_POST['post_m']) && $_POST['post_m'] == "taskStatus"){
  
  $data = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses',"id = '".$_POST['statusID']."'");

  $dataGet = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks',"id = '".$_POST['id']."'");

  $reviewStartedAt = "";
  $reviewEndAt = "";
  if($data['statusName'] == '2-Started'){
    $reviewStartedAt = date("Y-m-d");
    $reviewEndAt = "";    
  }else if($data['statusName'] == '6-Reviewed'){
    $reviewStartedAt = $dataGet['reviewStartedAt'];
    $reviewEndAt = date("Y-m-d");
    $query = $objUser->updateTaskStatus($_POST['id'],$_POST['statusID']);
    
  }

    $query1 = $objUser->updateReview($_POST['id'],$reviewStartedAt,$reviewEndAt);
    if($query1){
      $row = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks','id = "'.$_POST['id'].'"');
      // $status = "stm_statuses";
      // $statuspio = "id = '".$row['taskStatusID']."'";
      // $get_data = $DB_HELPER_CLASS->SingleDataWhere($status, $statuspio);
    
      // $slect = "";
      // if($get_data['statusName'] == "7-For Review"){
        
      //   $tbl1 = "stm_statuses";
      //   $wher1 = "statusName = '6-Reviewed' OR statusName = '2-Started'";
      //   $userStatus = $DB_HELPER_CLASS->allRecordsRepeatedWhere($tbl1,$wher1);

      //   $slect .= "<select class='form-control form-control-sm statusid' data-id=".$row['id'].">";
      //   $slect .= "<option value='none'>Select</option>";
      //     foreach($userStatus as $list){
      //       $id = $list['id'];
      //       $statusName = $list['statusName'];
      //       $selected = "";
      //       if($get_data['id'] == $id){
      //         $selected .= "selected = 'selected'";
      //       }
      //       $slect .= "<option value=".$id." $selected>".$statusName."</option>";  
      //     }
      //   $slect .= "</select>";

      // }
      if($row['reviewEndAt'] != ""){
        $end_date = date('m-d-Y',strtotime($row['reviewEndAt']));   
      }else{
        $end_date = "";
      }

       $start_date = date('m-d-Y',strtotime($row['reviewStartedAt']));
      
      $ary = array("start_date" => $start_date, "end_date" => $end_date);
      echo json_encode($ary); 

    }

}

if(isset($_POST['post_m']) && $_POST['post_m'] == "saveInfo"){
  $query = $objUser->updateURL($_POST['subID'],$_POST['URL'],$_POST['comments']);
  if($query){
    echo "1";
  } 
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "viewInfo"){
  $data = $DB_HELPER_CLASS->SingleDataWhere("stm_taskassigned", "id = ".$_POST['subID']."");
  $array = array("URL" => $data['taskURL'], "comments" => $data['taskComments']);
  echo json_encode($array);
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "edittask"){
  
  $output = "";
  $channels = "";
  $data = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned','id ="'.$_POST['subID'].'"');

  $dataTaskUser= $data['taskuserID'];
  $datachannelID = $data['taskchannelID'];
  $datastoreID = $data['taskstoreID'];

$records = $DB_HELPER_CLASS->SingleDataWhere('stm_users',"id = '$dataTaskUser'");
  
$chnls = $DB_HELPER_CLASS->SingleDataWhere('stm_channels', "id = '$datachannelID'");

$stores = $DB_HELPER_CLASS->SingleDataWhere('stm_stores', "id = '$datastoreID'");
  
  $deadline = date("d/m/Y", strtotime($data['taskDeadline']));
  $array = array("subtask" => $data['subTaskName'], "output" => $records['userName'], "channels" => $chnls['channelName'],"stores" => $stores['storeName'],"deadline" => $deadline, "subID" => $data['id'],"description_edit" => $data['subTaskDescription']);
  echo json_encode($array);  
}


if(isset($_POST['post_m']) && $_POST['post_m'] == "exportFile"){
        $tb = "stm_users";
        $wh = "id = '$session_id'";
        $session_data = $DB_HELPER_CLASS->SingleDataWhere($tb, $wh);

        $delimiter = ","; 
        $filename = "tasks-data_" . date('Y-m-d') . ".csv"; 
         
        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 
         
        // Set column headers 
        $fields = array('Task ID','Priority','Task Date','Category','Task Title','Created By','Skype','Status','Task URL','NewTask Assignees','InProgress Assignees','Done Assignees');
        fputcsv($f, $fields, $delimiter); 

        $fromDate = date("Y-m-d", strtotime($_POST['fromdate']));
        $toDate = date("Y-m-d", strtotime($_POST['todate']));
        $query = $db->prepare("select * from stm_tasks WHERE taskCreationDate BETWEEN '$fromDate' AND '$toDate'");
        $ex = $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
             $tblType = "stm_tasktypes";
    $wheType = "id = '".$row['taskTypeID']."'";
    $type = $DB_HELPER_CLASS->SingleDataWhere($tblType, $wheType);

    $status = "stm_statuses";
    $statuspio = "id = '".$row['taskStatusID']."'";
    $StData = $DB_HELPER_CLASS->SingleDataWhere($status, $statuspio);

    $statusClass = "";
    if($StData['statusName'] == "6-Reviewed"){
      $statusClass .= "primary";
    }
    if($StData['statusName'] == "1-New Task"){
      $statusClass .= "important";
    }
    if($StData['statusName'] == "3-In Progress" OR $StData['statusName'] == "2-Started"){
      $statusClass .= "warning";
    }
    if($StData['statusName'] == "4-Ended" OR $StData['statusName'] == "5-Done"){
      $statusClass .= "success";
    }
    
    $stClass = "";
    if($row['reviewStartedAt'] == "" AND $row['reviewEndAt'] == "" ){
      $stClass .= "1-New Task";
    }else if($row['reviewStartedAt'] != "" AND $row['reviewEndAt'] == ""){
      $stClass .= "3-In Progress";
    }else if($row['reviewEndAt'] !=""){
      $stClass .= "6-Reviewed";
    }
   

    $use = "stm_users";
    $wheuse = "id = '".$row['taskAssignedBy']."'";
    $dataUse = $DB_HELPER_CLASS->SingleDataWhere($use, $wheuse);
    $task_assignee = "";
    $statData = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', 'statusName = "5-DONE"');
    $newData = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', 'statusName = "1-New Task"');
    $ProgData = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', 'statusName = "3-In Progress"');
  $task_assignee_new = "";
  $dataNew = $DB_HELPER_CLASS->onlyDISTINCTRecords('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskStatusID ="'.$newData['id'].'"');
    foreach($dataNew as $task_assignee_data){
      
      $dataName = $DB_HELPER_CLASS->SingleDataWhere('stm_users', 'id = "'.$task_assignee_data['taskuserID'].'"');

      $taskData = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskuserID = "'.$task_assignee_data['taskuserID'].'" ORDER By taskStatusID ASC');

      $statusD = $DB_HELPER_CLASS->SingleDataWhere("stm_statuses", "id = '".$taskData['taskStatusID']."'");
     
    $task_assignee_new .= trim($dataName['displayName'].'|'.$statusD['statusName'].',');
    }

  $task_assignee_progress = "";
  $dataProg = $DB_HELPER_CLASS->onlyDISTINCTRecords('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskStatusID = "'.$ProgData['id'].'"');
    foreach($dataProg as $task_assignee_data){
      
      $dataName = $DB_HELPER_CLASS->SingleDataWhere('stm_users', 'id = "'.$task_assignee_data['taskuserID'].'"');

      $taskData = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskuserID = "'.$task_assignee_data['taskuserID'].'" ORDER By taskStatusID ASC');

      $statusD = $DB_HELPER_CLASS->SingleDataWhere("stm_statuses", "id = '".$taskData['taskStatusID']."'");
     
    $task_assignee_progress .= trim($dataName['displayName'].'|'.$statusD['statusName'].',');
    }
  $task_assignee_done = "";
  $dataDone = $DB_HELPER_CLASS->onlyDISTINCTRecords('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskStatusID ="'.$statData['id'].'"');
    foreach($dataDone as $task_assignee_data){
      
      $dataName = $DB_HELPER_CLASS->SingleDataWhere('stm_users', 'id = "'.$task_assignee_data['taskuserID'].'"');

      $taskData = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned', 'taskID = "'.$row['id'].'" AND taskuserID = "'.$task_assignee_data['taskuserID'].'" ORDER By taskStatusID ASC');

      $statusD = $DB_HELPER_CLASS->SingleDataWhere("stm_statuses", "id = '".$taskData['taskStatusID']."'");
     
    $task_assignee_done .= trim($dataName['displayName'].'|'.$statusD['statusName'].',');
    }
    $dataURL = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_taskassigned','taskID = "'.$row['id'].'"');
    $taskURL = "";
    foreach($dataURL as $dataURlList){
      $taskURL .= $dataURlList['taskURL'].','; 
    }
    $proio = "stm_priorities";
    $wheproio = "id = '".$row['taskPriorityID']."'";
    $datawheproio = $DB_HELPER_CLASS->SingleDataWhere($proio, $wheproio);

    $creationDate = date("d M Y", strtotime($row["taskCreationDate"]));
    // $deadlineDate = date("d M Y", strtotime($row["taskDeadline"]));

    $start_date = date('d M Y');
    // $end_date = date("d M Y", strtotime($row["taskDeadline"]));

      $taskName = $row['taskName'];
      
        $lineData = array($row['id'], $datawheproio['taskpriorityName'], $creationDate, $type['tasktypeName'], $taskName, $dataUse['displayName'], $row['taskSkypeGroup'], $StData['statusName'],$taskURL,$task_assignee_new,$task_assignee_progress,$task_assignee_done);
            fputcsv($f, $lineData, $delimiter);
        }
        
       
        fseek($f, 0); 
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
         
        //output all remaining data on a file pointer 
        return fpassthru($f);
        
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "savesubTask"){

$data = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned','id = "'.$_POST['subID'].'" AND taskID = "'.$_POST['taskID'].'"');

$owner = $_POST['user'];
$channels = $_POST['channels'];
$stores = $_POST['stores'];

$deadline = "";
if($_POST['subDeadline'] == ""){
  $deadline = $data['taskDeadline'];
}else{
  $end = $_POST['subDeadline'];
  $deadline = date("Y-m-d", strtotime($end));
}

$query = $objUser->updateTaskAssigned($_POST['subtask'],addslashes($_POST['description_edit']),$_POST['taskID'],$channels,$stores,$owner,$_POST['supervisor'],$deadline,$_POST['subID']);

if($query){
  $tasks = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks','id = "'.$_POST['taskID'].'"');

  $dataTaskUsers = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$tasks['taskAssignedBy'].'"');

  $dataTaskSupervisor = $DB_HELPER_CLASS->SingleDataWhere('stm_users','id = "'.$tasks['taskSupervisorID'].'"');

      // $mail = new PHPMailer(); 
      // //$mail->SMTPDebug=3;
      // $mail->IsSMTP(); 
      // $mail->SMTPAuth = true; 
      // $mail->SMTPSecure = 'ssl'; 
      // $mail->Host = "mail.swiftitsol.net";
      // $mail->Port = "465"; 
      // $mail->IsHTML(true);
      // $mail->Subject = 'STM - New Task Created';

      // $mail->CharSet = 'UTF-8';
      // $mail->Username = "stm@swiftitsol.net";
      // $mail->Password = '*+on2&12#$$r';
      // $mail->SetFrom("stm@swiftitsol.net");

      
      // $username = array();
      // $assignees = $DB_HELPER_CLASS->onlyDISTINCTRecords('stm_taskassigned','taskID = "'.$_POST['taskID'].'"');
      // foreach($assignees as $allassignees){

      //   $allassigneesName = $DB_HELPER_CLASS->allRecordsRepeatedWhere('stm_users','id = "'.$allassignees['taskuserID'].'"');
      //   foreach($allassigneesName as $assignedUserInfo){
      //      $mail->AddAddress($assignedUserInfo['userEmail']); 
      //      array_push($username,$assignedUserInfo['userName']);
      //   }
        

      // }
      // $mail->AddAddress($dataTaskSupervisor['userEmail']); 
      // $mail->AddAddress($dataTaskUsers['userEmail']); 
      
      // $task_assignee = implode(',', $username);

      // $msg = "<h4>Dear Team Member(s):</h4>";
      //   $msg .= "<p>Please note that the Administrator/Task Creator has updated the previously entered info in the task # from his/her end and the info is available for the other team members who might be depending on that info. This email is sent to you because you are either the creator of this task or a supervisor to finally review and sign off the task or one of the assignees to work on this task. You may click on the Task ID or the Task Title to go directly to the task page, please make sure you are logged-in in order to access the system.</p><br>";
      //   $msg .= "<ul>";
      //   $msg .= "<li>Task ID : ".$_POST['taskID']."</li>";
      //   $msg .= "<li>Created By : ".$dataTaskUsers['userName']."</li>";
      //   $msg .= "<li>Task Title : ".$tasks['taskName']."</li>";
      //   $msg .= "<li>Supervisor : ".$dataTaskSupervisor['userName']."</li>";
      //   $msg .= "<li>Assignees : ".$task_assignee."</li>";
      //   $msg .= "</ul>";
      //   $msg .= "<p>Best regards</p><br>";
      //   $msg .= "<p>System Administrator</p>";
      //   $msg .= "<p>SITS Task Management (STM)</p>";
      //   $msg .= "<p>a product of Swift IT Solutions Pvt. Ltd.</p>";
      //   $mail->Body = $msg;
      // $mail->SMTPOptions=array('ssl'=>array(
      //     'verify_peer'=>false,
      //     'verify_peer_name'=>false,
      //     'allow_self_signed'=>false
      // ));
      // if(!$mail->Send()){
      //     echo $mail->ErrorInfo;
      // }
}else{
  echo "0";
}

  

}

if(isset($_POST['post_m']) && $_POST['post_m'] == "viewDetail"){
  $data = $DB_HELPER_CLASS->SingleDataWhere("stm_taskassigned", "id = ".$_POST['subID']."");
  $array = array("comments" => $data['taskComments']);
  echo json_encode($array); 
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "rem_detail"){
  $query = $objUser->stm_rem_detail($_POST['id']);
  
  $del_record_attach = $DB_HELPER_CLASS->SingleDataWhere('stm_task_details', "id = '".$_POST['id']."' ");
    
    echo unlink('../../images/'.$del_record_attach['attachement']); 
    echo "1"; 
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "prelst_rem_detail"){
  
  $query = $objUser->stm_rem_prelst($_POST['id']);
  
}

if(isset($_POST['post_m']) && $_POST['post_m'] == "inActive"){
  
  $deleteStatus = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "In-Active"');
  
  $qury = $objUser->stm_task_inactive($_POST['taskID'],$deleteStatus['id']);

  $objUser->stm_assignees_inactive($_POST['taskID']);

  if($qury){
    echo "1";
  }
  
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "active"){
  
  $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");

  $task_assignee_done = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '5-Done'");

  $task_assignee_new = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");

  $task_status_data = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks',"id = '".$_POST['taskID']."'");

  //if($stat['id'] == $task_status_data['taskStatusID']){

  $records = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."'");

  $totalTaskRecord = count($records);

  $recordsDone = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$task_assignee_done['id']."' ");
    $all_done_records = count($recordsDone);

    $recordsNew = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$task_assignee_new['id']."' ");
    $NewRecords = count($recordsNew);
   
    if($all_done_records == $totalTaskRecord){
      
      if($task_status_data['reviewEndAt'] != ""){
        
        $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '6-Reviewed'");

        $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);
      }else{
        $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");

        $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);
      }

      

    }else if($NewRecords == $totalTaskRecord){
      
      $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");
      $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);
    }else{
      $status_prog_id = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '3-In Progress'");
      $objUser->updateTaskStatus($_POST['taskID'],$status_prog_id['id']);
    }
  
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "remove_sub_task"){
  $qury = $objUser->deleteSubTask($_POST['id']);
  if($qury){
     $records = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."'");
     $totalTaskRecord = count($records);

      $recordsDone = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '16' ");
      $DoneRecords = count($recordsDone);
      $newTaskS = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses','statusName = "1-New Task"');
      $recordsNew = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$newTaskS['id']."' ");
      $totalNew = count($recordsNew);

      $stats_ids = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '3-In Progress'");
      $recordsProgres = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$stats_ids['id']."' ");
      $totalProg = count($recordsProgres);

     if($totalTaskRecord){

        if($DoneRecords == $totalTaskRecord){
          $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");
          $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);
        }else if($totalNew == $totalTaskRecord){
          $objUser->updateTaskStatus($_POST['taskID'],$newTaskS['id']);
        }else if($totalProg == '1'){
          $objUser->updateTaskStatus($_POST['taskID'],$stats_ids['id']);
        }
     }else{
        $objUser->updateTaskStatus($_POST['taskID'],$newTaskS['id']);
     }
      
   }
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "rejected"){

  $createdOn = date('Y-m-d H:i:s');
  $objUser->updateMessage($_POST['taskID'],$createdOn);

  $messageID = $DB_HELPER_CLASS->lastID();
  
  $asigneData = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned','id = "'.$_POST['subID'].'"');

  $msgTo = $asigneData['taskuserID'];

  $objUser->updateMessageDetail($messageID,$_POST['reason'],$session_id,$msgTo,'0','1',$createdOn);

  $messageDetailID = $DB_HELPER_CLASS->lastID();

  $statusRejected = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses',"statusName = 'Rejected'");
  
  $ru = $objUser->updateAssigneesStatus($_POST['subID'],$statusRejected['id'],$messageDetailID);

  $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");

  $task_assignee_done = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '5-Done'");

  $task_assignee_new = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");

  $task_status_data = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks',"id = '".$_POST['taskID']."'");

  //if($stat['id'] == $task_status_data['taskStatusID']){

  $records = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."'");

  $totalTaskRecord = count($records);

  $recordsDone = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$task_assignee_done['id']."' ");
    $all_done_records = count($recordsDone);

    $recordsNew = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$task_assignee_new['id']."' ");
    $NewRecords = count($recordsNew);
   
    if($NewRecords == $totalTaskRecord){
      
      $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");
      $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);
    }else{
      $status_prog_id = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '3-In Progress'");
      $objUser->updateTaskStatus($_POST['taskID'],$status_prog_id['id']);
    }
}
// if(isset($_POST['post_m']) && $_POST['post_m'] == "saveReason"){
//   $query = $objUser->updateReason($_POST['subID'], $_POST['reason']);
//   if($query){
//     echo "1";
//   }
// }
if(isset($_POST['post_m']) && $_POST['post_m'] == "viewReason"){
 $query = $DB_HELPER_CLASS->SingleDataWhere('stm_taskassigned','id = "'.$_POST['subID'].'"');
 echo $query['subTaskDescription'];
}
if(isset($_POST['post_m']) && $_POST['post_m'] == "approved"){

  $task_assignee_approved = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = 'Approved'");
  $approvedOn = date('Y-m-d');
  
  $qury = $objUser->updateApproved($_POST['id'],$task_assignee_approved['id'],$approvedOn);

  $stat = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '7-For Review'");

  $task_assignee_new = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '1-New Task'");

  $task_status_data = $DB_HELPER_CLASS->SingleDataWhere('stm_tasks',"id = '".$_POST['taskID']."'");

  //if($stat['id'] == $task_status_data['taskStatusID']){

  $records = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."'");

  $totalTaskRecord = count($records);

  $recordsApproved = $DB_HELPER_CLASS->allRecordsRepeatedWhere("stm_taskassigned","taskID = '".$_POST['taskID']."' AND taskStatusID = '".$task_assignee_approved['id']."' ");
    $all_approved_records = count($recordsApproved);

   
    if($all_approved_records == $totalTaskRecord){
      
      $statusid = $DB_HELPER_CLASS->SingleDataWhere('stm_statuses', "statusName = '6-Reviewed'");

      $objUser->updateTaskStatus($_POST['taskID'],$statusid['id']);

    }
}
?>