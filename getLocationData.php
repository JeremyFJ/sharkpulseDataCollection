<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 12/10/14
 * Time: 7:52 PM
 */
    require_once('postgreConfig.php');
    $sql = "SELECT id, date, time, users_email, species_name, latitude, longitude, img_name, notes, device_type FROM sharkpulse";
    $result = pg_query($dbconn, $sql);
    if (!$result) {
	exit;
    }
    $data = array();
    $i = 1;
        while($row = pg_fetch_row($result)) {
            //echo "$row[0] $row[1] $row[3] $row[4] $row[5] $row[6] $row[7]";
            $record = array();
            $record['id'] =  $row[0];
            $record['date'] = $row[1];
            $record['time'] = $row[2];
            $record['users_email'] = $row[3];
            $record['species_name'] = $row[4];
            $record['latitude'] = $row[5];
            $record['longitude'] = $row[6];
            $record['image'] = $row[7];
            $record['notes'] = $row[8];
            $record['device_type'] = $row[9];

            $data["".$i] = $record;
            $i +=1;
        }
        $data = json_encode($data);
        echo $data;

?>
