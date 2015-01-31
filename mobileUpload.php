<?php
    //photograph, lat long, email,
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once(__ROOT__.'postgreConfig.php');
    if(getenv('REQUEST_METHOD') == "POST"){
        //echo print_r(array_keys($_POST));
        $headers = getallheaders();
        $agent = $headers['User-Agent'];
        $users_information = Array(
            "DATE" => "",
            "TIME" => "",
            "USERS_EMAIL" => "",
            "SHARK_NAME" => "",
            "IMAGE_NAME" => "",
            "LATITUDE" => "",
            "LONGITUDE" => "",
            "NOTES" => "");
        $users_information["DATE"] = $_POST["DATE"];
        $users_information['TIME'] = $_POST['TIME'];
        $users_information['SHARK_NAME'] = $_POST["SPECIES"];
        $users_information['IMAGE_NAME'] = $_FILES['PHOTOGRAPH']['name'];
        $users_information['LATITUDE'] = $_POST["LATITUDE"];
        $users_information["LONGITUDE"] = $_POST["LONGITUDE"];
        $users_information["USERS_EMAIL"] = $_POST['EMAIL'];
        $users_information["NOTES"] = $_POST['NOTES'];
        if($_FILES['PHOTOGRAPH']['name'] && !$_FILES['PHOTOGRAPH']['error']){
	    $target_dir = __ROOT__."/uploads/";
            $target_file = $target_dir . basename($_FILES["PHOTOGRAPH"]["name"]);
	    echo "\nTarget dir: ".$target_dir."\n";
	    echo "Target File: ".$target_file."\n\n";
	    echo "Temporary Name: ".$_FILES["PHOTOGRAPH"]["tmp_name"]."\n\n";
            if (move_uploaded_file($_FILES["PHOTOGRAPH"]["tmp_name"], $target_file)) {
                chmod($target_file, 0755);
		$uploaded_file_name = basename( $_FILES["PHOTOGRAPH"]["name"]); 
	        $users_information['IMAGE_NAME'] = $target_dir . basename($_FILES["PHOTOGRAPH"]["name"]);
                echo "The file $uploaded_file_name has been uploaded.";
                echo $users_information["NOTES"];
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
       }
        $query_string = "insert into mobile_table (date, time, users_email, species_name,latitude, longitude, img_name, notes, device_type) values ('"
        .$users_information['DATE']."','".$users_information['TIME']."','".$users_information['USERS_EMAIL']."','".$users_information['SHARK_NAME']."','"
        .$users_information['LATITUDE']."','".$users_information['LONGITUDE']."','uploads/$uploaded_file_name','".$users_information['NOTES']."','".$agent."');";
	echo "\n\nQuery String: ". $query_string;
	$result=pg_query($dbconn, $query_string);
        if($result)
        {
	    echo "PSQL injection successful";
            $email = $_REQUEST['email'] ;
            $subject = "I saw a shark!";
            $header  = 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $message = "
                <html>
                <head>
                    <tite>Shark Pulse Alert</title>
                </head>
                <body>
                    <h1>Pulse Information</h1>
		    <img src='http://baseline2.stanford.edu/testdistro/test_uploads/$uploaded_file_name'>
                    <h2>Species guessed: ".$users_information['SHARK_NAME']."</h2>
                    <h2>Latitude: ".$users_information['LATITUDE']."</h2>
                    <h2>Longitude: ".$users_information['LONGITUDE']."</h2>
                    <h2>Date: ".$users_information['DATE']."</h2>
		    <h2>Notes: ".$users_information['NOTES']."</h2>
                    <h2>Posted by: <a href=$email> ".$users_information['USERS_EMAIL']."</a></h2>
                </body>
                </html>
            ";

  if(mail( "edsan5678@sbcglobal.net, danydexte@gmail.com, sharkbaselines@gmail.com", $subject,
    $message, $header )){
    echo "Mail Sent Successfully";
    }
        }
        else{
            header("HTTP/1.0 404 Not Found");
        }

    }else{
        echo "\n The method is not post";
    }

?>
