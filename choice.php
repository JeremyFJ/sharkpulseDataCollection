<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 1/25/15
 * Time: 4:48 PM
 */
    define('__ROOT__', dirname(dirname(__FILE__)));
    require_once(__ROOT__.'/testdistro/postgreConfig.php');
    if ($_SERVER['QUERY_STRING'] == "")
    {
        echo "The query string is empty\n";
        exit(1);
    }
    if (isset($_REQUEST['table']) && isset($_REQUEST['id']) && isset($_REQUEST['action'])) {
        // param was set in the query string
        if (empty($_REQUEST['table']) || empty($_REQUEST['id']) || empty($_REQUEST['action'])) {
            // query string had param set to nothing ie ?param=&param2=something
            echo "Query parameters are empty\n";
            exit(1);
        } else {
            $table = $_GET['table'];
            $id = $_GET['id'];
            $action = $_GET['action'];
            if($action == 'remove'){
                $sql = "delete from $table where id = $id;";
                echo "Sql: $sql\n";
                $result = pg_query($dbconn, $sql);
                if (!$result) {
                    $errormessage = pg_errormessage($dbconn);
                    echo $errormessage;
                    exit();
                }
                pg_close();
            }
            elseif($action == 'approve'){
                $sql = "SELECT id, date, time, users_email, species_name, latitude, longitude, img_name, notes, device_type from $table where id=$id;";
                $result = pg_query($dbconn, $sql);
                while($row = pg_fetch_row($result)) {
                    //echo "$row[0] $row[1] $row[3] $row[4] $row[5] $row[6] $row[7]
                    $recordDate = $row[1];
                    $recordTime = $row[2];
                    $recordUsers_email = $row[3];
                    $recordSpecies_name = $row[4];
                    $recordLatitude = $row[5];
                    $recordLongitude = $row[6];
                    $recordImage = $row[7];
                    $recordNotes = $row[8];
                    $recordDeviceType = $row[9];
                }
                $sql = "insert into sharkpulse (date, time, users_email, species_name, latitude, longitude, img_name, notes, device_type) values
                        ('$recordDate', '$recordTime','$recordUsers_email', '$recordSpecies_name','$recordLatitude','$recordLongitude','$recordImage','$recordNotes','$recordDeviceType');";
                echo $sql;
                $result = pg_query($dbconn, $sql);
                if (!$result) {
                    $errormessage = pg_errormessage($db);
                    echo $errormessage;
                    exit();
                }
                $sql = "delete from $table where id = $id;";
                $result = pg_query($dbconn, $sql);
                if (!$result) {
                    $errormessage = pg_errormessage($db);
                    echo $errormessage;
                    exit();
                }
                pg_close();
            }
            header("Location: http://localhost/~edsan/testdistro/createTables.php");
            die();
}
    }else{
        echo "Query parameters are not set.\n";
        exit(1);
    }


?>