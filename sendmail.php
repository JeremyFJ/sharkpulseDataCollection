<?php
  $email = $_REQUEST['email'] ;
  $subject = "Alert! A new pulse has been posted to SharkPulse";
  $header  = 'MIME-Version: 1.0' . "\r\n";
  $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $header .= 'From: SharkPulse <sharkbaselines@example.com>' . "\r\n";

  $message = "
  <html> 
  	<head>
  		<tite>Shark Pulse Alert</title>
  	</head>
  	<body>
  		<h1>Pulse Information</h1><br>
  		<h2>Species guessed: A Species</h2><br>
  		<h2>Latitude: </h2><br>
  		<h2>Longitude: </h2><br>
		<h2>Date: </h2><br>
		<h2>Posted by: <a href=$email> $email</a></h2><br>
  	</body>
  </html>
  ";

  if(mail( "edsan5678@sbcglobal.net", $subject,
    $message, $header )){
    echo "Mail Sent Successfully";
	}
  #header( "Location: http://www.example.com/thankyou.html" );
?>
