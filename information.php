<?php
/**
* Created by PhpStorm.
* User: edsan
* Date: 2/22/15
* Time: 12:44 PM
 * require('wp-blog-header.php');
*/
require_once('pdopostgreconfig.php');
function getData($table, $id){
    $dbconn = getConnection("pelagic");
    $sql = "SELECT date, time, users_email, species_name, latitude, longitude, img_name, notes ".
        "FROM $table where id=$id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $errormessage = pg_errormessage($dbconn);
        echo $errormessage;
        exit();
    }
    return $result;
}

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
        $data = getData($table, $id);
        $species_name = $data['species_name'];
        $time = $data['time'];
        $users_email = $data['users_email'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $image = $data['img_name'];
        $notes = $data['notes'];
        $date = $data['date'];
        $image = str_replace('"', "", $image);
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/infoStyle.css">
    <script type="text/javascript" src="js/informationMap.js"></script>

  </head>
    <body onload="loadScript()">
        <div id="contain">
            <div id="location_information">
                <h1><span id='species_name'><?=$species_name?></span></h1>
                <img src='<?=$image?>'>
                <div id="map-canvas-info"></div>
                <div id="location_description">
                    <!--<li id="location">Location: </li>
                    <li id="places">Places: </li>-->
                    <p>Latitude: <span id="latitude"><?=$latitude?></span></p>
                    <p>Longitude: <span id="longitude"><?=$longitude?></span></p>
                    <p>Date: <span id="date"><?=$date?></span></p>
                </div>
                <?php
                    if($table=='data_mining'){
                        echo "<form action='flickrForm.php?table=data_mining&id=$id' method='POST'> Is this a real shark (no shark in aquaria)? <br>
                            <label for='radio_data_mining_".$id."_yes'>Yes</label>
                            <input type='radio' name='radio_data_mining_".$id."' value='yes' id='radio_data_mining_".$id."_yes'>
                            <label for='radio_data_mining_".$id."_no'>No</label>
                            <input type='radio' name='radio_data_mining_".$id."' value='no' id='radio_data_mining_".$id."_no'><br>
                            <input type='hidden' value='$id'>
                            What species?<input type='text' name='species'>
                            <input type='submit' value='Submit' id='submit_button'><br>
                        </form>";
                    }?>
            </div>
        </div>
    </body>
</html>
