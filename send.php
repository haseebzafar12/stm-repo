<?php
    ob_start();
	session_start();

	define('SERVER_API_KEY', 'AAAAd7aV_vo:APA91bHMh0CCtGQeE3ZtjDAeeWsrOCLTLphsS1nqIgNLNGEIy-Ao0ugf_aAKvhN0Tg54F53gJdgGQp7gGuXLaAJgWxkvB7VHXMmcCzvIGzX8q-Kn0nfjFF7y610f2QYAS7fM5JBovrAt');

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
	
	$records = $db_helper->allRecordsRepeatedWhere('stm_token','userID = "4"');
	
	
	foreach($records as $recs){
		$registration_ids[] = $recs['token'];
		$users[] = $recs['userID'];
		$header = [
		'Authorization: Key=' . SERVER_API_KEY,
		'Content-Type: Application/json'
		];

		$msg = [
			'title' => 'Testing Notification',
			'body' => 'Testing Notification from localhost'
		];

		$payload = [
			'registration_ids' 	=> $users,
			'data'				=> $msg 
		];

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode( $payload ),
		  CURLOPT_HTTPHEADER => $header
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}	
	}
	// print_r($registration_ids);
	// exit();
	

}
 ?>