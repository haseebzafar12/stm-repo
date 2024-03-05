<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Push Notification</title>
	<link rel="manifest" href="manifest.json">
</head>
<body>

	<center>Hi</center>

	<script src="assets/js/libs/jquery-3.1.1.min.js"></script>
	<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
	<script>
	  
	  const config = {
	    apiKey: "AIzaSyBnRicYs-2vKP7tbJMyGIS7sboNG8Es6Jg",
	    authDomain: "sttm-ca6b6.firebaseapp.com",
	    projectId: "sttm-ca6b6",
	    storageBucket: "sttm-ca6b6.appspot.com",
	    messagingSenderId: "514164391674",
	    appId: "1:514164391674:web:2c4fd47d4d4ff5cb225343"
	  };

	  // Initialize Firebase
	    firebase.initializeApp(config);

	  // Retrieve Firebase Messaging object.
		const messaging = firebase.messaging();
		messaging.requestPermission()
		.then(function() {
		  console.log('Notification permission granted.');
		  // TODO(developer): Retrieve an Instance ID token for use with FCM.
		  if(isTokenSentToServer()) {
		  	console.log('Token already saved.');
		  } else {
		  	getRegToken();
		  }
		})
		.catch(function(err) {
		  console.log('Unable to get permission to notify.', err);
		});
		function getRegToken(argument) {
		   messaging.getToken()
		  .then(function(currentToken) {
		    if (currentToken) {
		        var post_m = "tokken";
				$.ajax({
			        method: "post",
			        url:"common/ajax/ajax.php",
			        dataType: 'text',
			        data:{currentToken:currentToken,post_m:post_m},
			        success:function(data){
			          console.log(data);
			        }
				});
		      // $('.tokken').val(currentToken);
		      console.log(currentToken);
		      setTokenSentToServer(true);
		    } else {
		      console.log('No Instance ID token available. Request permission to generate one.');
		      setTokenSentToServer(false);
		    }
		  })
		  .catch(function(err) {
		    console.log('An error occurred while retrieving token. ', err);
		    setTokenSentToServer(false);
		  });
		}
		function setTokenSentToServer(sent) {
		    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
		}
		function isTokenSentToServer() {
		    return window.localStorage.getItem('sentToServer') == 1;
		}
		
		messaging.onMessage(function(payload) {
		  console.log("Message received. ", payload);
		  notificationTitle = payload.data.title;
		  notificationOptions = {
		  	body: payload.data.body
		  };
		  var notification = new Notification(notificationTitle,notificationOptions);
		});
		




	  </script>
</body>
</html>