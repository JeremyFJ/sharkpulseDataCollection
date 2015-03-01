<?php
/**
* Created by PhpStorm.
* User: edsan
* Date: 2/22/15
* Time: 12:44 PM
*/
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="js/informationMap.js"></script>
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: auto; padding: 0; width:800px; }
            #map-canvas { height:300px;
                width: 300px; }
            img{
                width: 300px;
            }
            #container{
                display: flex;

            }
        </style>

    </head>
    <body onload="loadScript()">
        <div id="wrapper">
            <?php

                define('__ROOT__', dirname(dirname(__FILE__)));
                require_once(__ROOT__.'/testdistro/postgreConfig.php');
                if ($_SERVER['QUERY_STRING'] == "")
                {
//                    echo "The query string is empty\n";
//                    exit(1);
                }
                if (isset($_REQUEST['table']) && isset($_REQUEST['id'])) {
                // param was set in the query string
                    if (empty($_REQUEST['table']) || empty($_REQUEST['id'])) {
                        // query string had param set to nothing ie ?param=&param2=something
//                        echo "Query parameters are empty\n";
//                        exit(1);
                    } else {
                        $table = $_GET['table'];
                        $id = $_GET['id'];
                        $sql = "SELECT date, time, users_email, species_name, latitude, longitude, img_name, notes, device_type ".
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
                            $deviceType = $row[8];
                            echo "<div id=\"information_header\">";
                            echo "<h1><span id='species_name'>$species_name</span>observed by $users_email on $date</h1></div><div id=\"container\">";
                            echo "<div id=\"location_information\"><div id=\"map-canvas\"></div>";
                            echo "<div id=\"location_description\">";
                            echo "<ul>";
                            echo "<li id=\"location\">Location: </li>";
                            echo "<li id=\"places\">Places: </li>";
                            echo "<li>Latitude: <span id=\"latitude\">$latitude</span></li>";
                            echo "<li>Longitude: <span id=\"longitude\">$longitude</span></li>";
                            echo "</ul>";
                            echo "</div></div>";
                            echo "<div id=\"media_information\"><img src=\"/~edsan$image\"></div></div>";

                        }
                        pg_close();
                    }
                }



            ?>


        </div>
    </body>
</html>