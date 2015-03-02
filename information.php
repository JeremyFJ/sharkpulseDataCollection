<?php
/**
* Created by PhpStorm.
* User: edsan
* Date: 2/22/15
* Time: 12:44 PM
*/
//require('wp-blog-header.php');
?>
<!DOCTYPE html>
<html>
    
<?php /*get_header();*/ ?>
  <div id="menu">
   <ul>
   <?php/* wp_list_pages('exclude=271&sort_column=menu_order&title_li='); */?>
    </ul>
  </div>
    <body onload="loadScript()">
            <?php

                require_once('postgreConfig.php');
                if ($_SERVER['QUERY_STRING'] == "")
                {
                    exit(1);
                }
                if (isset($_REQUEST['table']) && isset($_REQUEST['id'])) {
                // param was set in the query string
                    if (empty($_REQUEST['table']) || empty($_REQUEST['id'])) {
                        exit(1);
                    } else {
                        $table = $_GET['table'];
                        $id = $_GET['id'];
                        $sql = "SELECT date, time, users_email, species_name, latitude, longitude, img_name, notes ".
                            "FROM $table where id=$id";
                        $result = pg_query($dbconn, $sql);
                        if (!$result) {
                            $errormessage = pg_errormessage($dbconn);
                            echo $errormessage;
                            exit();
                        }
                        else{
                            $row = pg_fetch_row($result);
                            //echo "$row[0] $row[1] $row[3] $row[4] $row[5] $row[6] $row[7]
                            $date = $row[0];
                            $time = $row[1];
                            $users_email = $row[2];
                            $species_name = $row[3];
                            $latitude = $row[4];
                            $longitude = $row[5];
                            $image = $row[6];
                            $notes = $row[7];
                            //$deviceType = $row[8];
			    echo "<div id=\"contain\">";
                            //echo "<div id=\"information_header\">
                           // echo "<h1><span id='species_name'>$species_name</span> observed by $users_email on $date</h1>";
                            echo "<div id=\"location_information\">";
		            echo "<h1><span id='species_name'>$species_name</span></h1><img src=\"$image\"><div id=\"map-canvas-info\"></div>";
                            echo "<div id=\"location_description\">";
                            //echo "<li id=\"location\">Location: </li>";
                            //echo "<li id=\"places\">Places: </li>";
                            echo "<p>Latitude: <span id=\"latitude\">$latitude</span></p>";
                            echo "<p>Longitude: <span id=\"longitude\">$longitude</span></p>";
			    echo "<p>Date: <span id=\"date\">$date</span></p>";
                            echo "</div>";
                            echo "</div>";
			   //get_sidebar();

                        }
                        pg_close();
                    }
                }
            ?>
        </div>
      <script type="text/javascript" src="js/informationMap.js"></script>
    </body>
</html>
