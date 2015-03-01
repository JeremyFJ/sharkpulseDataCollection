
<link rel="stylesheet" type="text/css" href="css/tableStyles.css">
<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 12/10/14
 * Time: 5:42 PM
 */

    define('__ROOT__', dirname(dirname(__FILE__)));
    require_once(__ROOT__.'/testdistro/postgreConfig.php');
    $sql = "SELECT id, date, time, users_email, species_name, latitude, longitude, img_name, notes, device_type FROM mobile_table;";
    $mobile_result = pg_query($dbconn, $sql);
    $sp_sql = "SELECT id, date, time, users_email, species_name, latitude, longitude, img_name, notes,device_type FROM sharkpulse_temp;";
    $sptemp_result = pg_query($dbconn, $sp_sql);
    if (!$mobile_result && !$sptemp_result) {
        echo "An error occurred.\n";
        exit;
    }
    else {
        // output data of each row
        echo '<table class="TFtable">';
        echo '<tr>';
        echo '<th>ID</th><th>Date</th><th>Time</th><th>User Email</th><th>Species Name</th><th>Latitude</th><th>Longitude</th><th>Notes</th><th>Device Type</th><th>Image</th><th>Approval</th>';
        echo '</tr>';
        while($row = pg_fetch_row($mobile_result)) {
	    echo '<tr>';
            echo "<td>". $row[0] ."</td> <td>".$row[1]."</td><td>". $row[2]."</td><td>".$row[3]. "</td><td>$row[4]</td><td>" .$row[5].
                "</td><td>".$row[6]. "</td><td>". $row[8]. '</td><td>'.$row[9].'</td><td><img src=/~edsan/testdistro/'.$row[7].' height="10%"></td>
                <td><a href="choice.php/?action=approve&table=mobile_table&id='.$row[0].'"><img src="/~edsan/testdistro/green_check.png" height="10%"></a><a href="choice.php/?action=remove&table=mobile_table&id='.$row[0].'"><img src="/~edsan/testdistro/red_x.png" height="10%"</a></td>';
            echo '</tr>';

        }
        while($row = pg_fetch_row($sptemp_result)) {
            echo '<tr>';
            echo "<td>". $row[0] ."</td> <td>".$row[1]."</td><td>". $row[2]."</td><td>".$row[3]. "</td><td>$row[4]</td><td>" .$row[5].
                "</td><td>".$row[6]. "</td><td>". $row[8]. '</td><td>'.$row[9].'</td><td><img src=/~edsan/testdistro'.$row[7].' height="10%"></td>
                <td><a href="choice.php/?action=approve&table=sharkpulse_temp&id='.$row[0].'"><img src="/~edsan/testdistro/green_check.png" height="10%"></a><a href="choice.php/?action=remove&table=mobile_table&id='.$row[0].'"><img src="/~edsan/testdistro/red_x.png" height="10%"</a></td>';
            echo '</tr>';

        }
        echo '</table>';
    }

?>
