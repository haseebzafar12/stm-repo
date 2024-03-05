<?php 

	class db_helper{

		public function __construct($db){
        	$this->conn = $db;   
	    }

	    public function lastID(){
	        $stmt = $this->conn->lastInsertId();
	        return $stmt;
	    }
	    public function timeago($date) {
	       $timestamp = strtotime($date);   
	       
	       $strTime = array("sec", "min", "h", "day", "mon", "year");
	       $length = array("60","60","24","30","12","10");

	       $currentTime = time();
	       if($currentTime >= $timestamp) {
	            $diff     = time()- $timestamp;
	            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
	            $diff = $diff / $length[$i];
	            }

	            $diff = round($diff);
	            return $diff . " " . $strTime[$i] . "(s) ago ";
	       }
	    }
	    function SingleDataWhere($table,$where){
	        $query = "SELECT * FROM ".$table." WHERE ".$where."  ";  
	        $stmt = $this->conn->prepare( $query );
	        
	        $stmt->execute();
	        //$stm->fetch(PDO::FETCH_ASSOC)
	        if($record = $stmt->fetch(PDO::FETCH_ASSOC))
	        {
	            return $record;
	        }
	    }
	    function removeCountry($countryID){
	    	$query = "DELETE FROM am_country where countryID = '$countryID' ";  
	        $stmt = $this->conn->prepare( $query );
	        $stmt->execute();
	    }
		function removeRecordWhere($code){
	        $query = "DELETE FROM am_chartofaccount where showCode = '$code' ";  
	        $stmt = $this->conn->prepare( $query );
	        $stmt->execute();
	    }
	    function allRecordsRepeatedWhere($table, $where){
	    	$query = $this->conn->prepare("select * from ".$table." WHERE ".$where."  ");
	        $ex = $query->execute();
	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }
	        return $data;
	    }

	    function singleRecordwithDistict($table,$where){
	    	$query = "SELECT DISTINCT taskSupervisorID FROM ".$table." WHERE ".$where."  ";  
	        $stmt = $this->conn->prepare( $query );
	        
	        $stmt->execute();
	        //$stm->fetch(PDO::FETCH_ASSOC)
	        if($record = $stmt->fetch(PDO::FETCH_ASSOC))
	        {
	            return $record;
	        }	
	    }
	    function singleRecordDistictColumn($table,$colume,$where){
	    	$query = "SELECT DISTINCT ".$colume." FROM ".$table." WHERE ".$where."  ";  
	        $stmt = $this->conn->prepare( $query );
	        
	        $stmt->execute();
	        //$stm->fetch(PDO::FETCH_ASSOC)
	        if($record = $stmt->fetch(PDO::FETCH_ASSOC))
	        {
	            return $record;
	        }	
	    }

	    function onlyDISTINCTRecords($table,$where){
	    	
	    	$query = $this->conn->prepare("select DISTINCT taskuserID from ".$table." WHERE ".$where."  ");
	        $ex = $query->execute();
	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }
	        return $data;
	    }
	    function DISTINCTRecords($colume,$table,$where){
	    	
	    	$query = $this->conn->prepare("select DISTINCT ".$colume." from ".$table." WHERE ".$where."  ");
	        $ex = $query->execute();
	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }
	        return $data;
	    }
	    function allDISTINCTRecords($colume,$table){
	    	
	    	$query = $this->conn->prepare("select DISTINCT ".$colume." from ".$table." ");
	        $ex = $query->execute();
	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }
	        return $data;
	    }
	    function DISTINCTRecordSupervisor($table,$where){
	    	
	    	$query = $this->conn->prepare("select DISTINCT taskSupervisorID from ".$table." WHERE ".$where."  ");
	        $ex = $query->execute();
	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }
	        return $data;
	    }

	    function allRecordsRepeated($table){
	    	
	    	$query = $this->conn->prepare("select * from ".$table." ORDER BY statusName ASC");
	        
	        $ex = $query->execute();

	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }

	        return $data;
	    }
	    function recordswithColumns($colums,$table){
	    	
	    	$query = $this->conn->prepare("select ".$colums." from ".$table."");
	        
	        $ex = $query->execute();

	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }

	        return $data;
	    }
	    function allRecordsOrderBy($table,$orderby){
	    	
	    	$query = $this->conn->prepare("select * from ".$table."  ORDER BY ".$orderby."");
	        
	        $ex = $query->execute();

	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }

	        return $data;
	    }
	    function allRecordsWhereOrderBy($table,$where){
	    	
	    	$query = $this->conn->prepare("select * from ".$table." WHERE ".$where." ");
	        
	        $ex = $query->execute();

	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }

	        return $data;
	    }
	    
	    function allRecords($table){
	    	
	    	$query = $this->conn->prepare("select * from ".$table."");
	        
	        $ex = $query->execute();

	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }

	        return $data;
	    }

	    function searchQuery($title){
	    	$query = $this->conn->prepare("select * from account_master where title LIKE '%$title%'");
	    	$ex = $query->execute();
	        $data = array();
	        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	            $data[] = $row;
	        }

	        return $data;
	    }
	    function allAccountChart($table){
	    	
	    	$query = $this->conn->prepare("select * from ".$table." ");
	        
	        $ex = $query->execute();

	        $data = array();
	        while ($list_of_account = $query->fetch(PDO::FETCH_ASSOC)) {
	        	
	            $sub_data['code'] = $list_of_account['code'];
		        $sub_data['title'] = $list_of_account['title'];
		        $sub_data['text'] = $list_of_account['title'];
		        $sub_data['parent'] = $list_of_account['parent'];

		        $datas[] = $sub_data;
	        }

	        return $datas;
	    }

	    function allAcChart($table){

	    	$query = $this->conn->prepare("select * from ".$table." ");
	        
	        $ex = $query->execute();

	        $arrayAcccounts = array();
	        while ($list_of_account = $query->fetch(PDO::FETCH_ASSOC)) {
	        	
	        	$arrayAcccounts[$list_of_account['code']] = array("parent_id" => $list_of_account['parent'], "title" =>$list_of_account['title']);
		        
	        }
	        return $arrayAcccounts;
	    }

	    //Country Function

	    function total_number_of_records($table){
	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM ".$table." ");
			$stmt->execute();
		    $records = $stmt->fetch();
		    $totalRecords = $records['allcount'];

			return $totalRecords;
	    }

	    function total_number_of_records_filtering($searchQuery,$searchArray){
	    	
	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM am_state t1 INNER JOIN am_country t2 on t1.countryID = t2.countryID WHERE 1 ".$searchQuery);

	    	// $stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM 
	    	// 	am_country WHERE 1 ".$searchQuery);

		    $stmt->execute($searchArray);
		    $records = $stmt->fetch();

		    return $totalRecordwithFilter = $records['allcount'];
	    }

	    function total_records($searchQuery,$columnName,$columnSortOrder,$searchArray,$row,$rowperpage){
	    	
	    	// $stmt = $this->conn->prepare("SELECT * FROM orders t1 INNER JOIN users t2 on t1.orderBookerID = t2.id WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM am_state t1 INNER JOIN am_country t2 on t1.countryID = t2.countryID WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

	    	// $stmt = $this->conn->prepare("SELECT * FROM am_country
	    	//     WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

	    	    foreach ($searchArray as $key=>$search) {
			      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
			    }

			    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
			    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
			    $stmt->execute();
			    return $empRecords = $stmt->fetchAll();

			    $data = array();
		        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		            $data[] = $row;
		        }

		        return $data;

	    }

	     //State Function

	    function total_number_of_records_state(){
	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM am_country");
			$stmt->execute();
		    $records = $stmt->fetch();
		    $totalRecords = $records['allcount'];

			return $totalRecords;
	    }

	    function total_number_of_records_filtering_state($searchQuery,$searchArray){
	    	
	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM am_state t1 INNER JOIN am_country t2 on t1.countryID = t2.countryID WHERE 1 ".$searchQuery);

	    	// $stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM 
	    	// 	am_state WHERE 1 ".$searchQuery);

		    $stmt->execute($searchArray);
		    $records = $stmt->fetch();

		    return $totalRecordwithFilter = $records['allcount'];
	    }

	    function total_records_state($searchQuery,$columnName,$columnSortOrder,$searchArray,$row,$rowperpage){
	    	
	    	$stmt = $this->conn->prepare("SELECT * FROM am_state t1 INNER JOIN am_country t2 on t1.countryID = t2.countryID WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

	    	// $stmt = $this->conn->prepare("SELECT * FROM am_state
	    	//     WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

	    	    foreach ($searchArray as $key=>$search) {
			      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
			    }

			    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
			    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
			    $stmt->execute();
			    return $empRecords = $stmt->fetchAll();

			    $data = array();
		        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		            $data[] = $row;
		        }

		        return $data;

	    }

	    function total_number_of_records_city(){
	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM am_city");
			$stmt->execute();
		    $records = $stmt->fetch();
		    $totalRecords = $records['allcount'];

			return $totalRecords;
	    }

	    function total_number_of_records_filtering_city($searchQuery,$searchArray){
	    	
	    	$stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM am_state t1 INNER JOIN am_city t2 on t1.stateID = t2.stateID WHERE 1 ".$searchQuery);

	    	// $stmt = $this->conn->prepare("SELECT COUNT(*) AS allcount FROM 
	    	// 	am_state WHERE 1 ".$searchQuery);

		    $stmt->execute($searchArray);
		    $records = $stmt->fetch();

		    return $totalRecordwithFilter = $records['allcount'];
	    }

	    function total_records_city($searchQuery,$columnName,$columnSortOrder,$searchArray,$row,$rowperpage){
	    	
	    	$stmt = $this->conn->prepare("SELECT * FROM am_state t1 INNER JOIN am_city t2 on t1.stateID = t2.stateID WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

	    	    foreach ($searchArray as $key=>$search) {
			      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
			    }

			    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
			    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
			    $stmt->execute();
			    return $empRecords = $stmt->fetchAll();

			    $data = array();
		        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		            $data[] = $row;
		        }

		        return $data;

	    }

	    function am_subarea_total_data($query){
	    	$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$total_data = $stmt->rowCount();

			return $total_data;
	    }

	    function am_subarea_total_filer_data($filter_query){
	    	$stmt = $this->conn->prepare($filter_query);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$total_filter_data = $stmt->rowCount();
			return $result;
			return $total_filter_data;
	    }

	 
	}	
?>