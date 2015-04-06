<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 3/9/15
 * Time: 8:57 PM
 */

require_once('postgreConfig.php');
if(getenv('REQUEST_METHOD') == "POST") {
    if (isset($_GET['table']) && isset($_GET['id'])) {
        $table = $_GET['table'];
        $id = $_GET['id'];
        $radioString = "radio_$table" . "_" . "$id";
        if (isset($_POST[$radioString])) {
            $radioChoice = $_POST[$radioString];
            if ($radioChoice == "no") {
                //add no to record
                $sql = "update data_mining SET validated=true, is_shark=false where id=$id;";
                $result = pg_query($dbconn, $sql);
                if ($result) {
                    //echo "Result Recorded: $sql ";
                }

            } else if ($radioChoice = "yes") {
                if (isset($_POST['species'])) {
                    $species = $_POST['species'];
                    if ($species != "") {
                        $sql = "update data_mining set species_name='$species', validated=true, is_shark=true where id=$id;";
                        $result = pg_query($dbconn, $sql);
                        if ($result) {
                            //echo "Result Recorded: $sql ";
                        }
                    } else {
                        $sql = "update data_mining set validated=true, is_shark=true WHERE id=$id;";
                        $result = pg_query($dbconn, $sql);
                        if ($result) {
                            //echo "Result recorded: $sql";
                        }
                    }

                } else {
                    exit;
                }
            }
        }
    }
    $referer = $_SERVER['HTTP_REFERER'];
//    $arr = explode("?", $referer, 2);
//    $first = $arr[0];
//    echo "$first";
    header("Location: $referer");
}
?>