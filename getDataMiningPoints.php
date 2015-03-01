<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 2/7/15
 * Time: 9:54 PM
 */


require_once('postgreConfig.php');
$sql = "select latitude, longitude, img_name, date, time, id from data_mining order by date asc;";
$result = pg_query($dbconn, $sql);
if (!$result) {
    exit;
}
$data = array();

$i = 1;
while($row = pg_fetch_row($result)) {
    //echo "$row[0] $row[1] $row[3] $row[4] $row[5] $row[6] $row[7]";
    $record = array();
    $record['latitude'] =  $row[0];
    $record['longitude'] = $row[1];
    $record['img_url'] = $row[2];
    $record['date'] = $row[3];
    $record['time'] = $row[4];
    $record['id'] = $row[5];
    array_push($data, $record);
//    $data["".$i] = $record;
//    $i +=1;
}
$data = json_encode($data);
echo $data;



?>
