<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 3/18/15
 * Time: 6:49 PM
 */

require_once "pdopostgreconfig.php";
function findUnknownTSN(){
    $dbconn = getConnection("pelagic");

    $sql = "select distinct(species_name), tsn from sharkpulse;";
    //$sql= "select distinct split_part(species_name, ' ', 1) as genus from sharkpulse;";
    $stmt = $dbconn->prepare($sql);
    if($stmt->execute()){
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row){
            if($row['tsn'] == -1){
                generateTSN($row['species_name']);
            }
        }
    }
}
function queryITIS($speciesName){
    $itisConnection = getConnection("ITIS");
    $sql = "select tsn from taxonomic_units where complete_name like '$speciesName';";
    $itisSTMT = $itisConnection->prepare($sql);
    if($itisSTMT->execute()){
        $res = $itisSTMT->fetch(PDO::FETCH_ASSOC);
        //print_r($res);
        $tsn = $res['tsn'];
        if($tsn != ""){
            return $tsn;
        }
    }
    return -1;
}
function queryITISVernaculars($speciesName)
{
    $itisConnection = getConnection("ITIS");
    $sql = "select tsn from vernaculars where vernacular_name like '$speciesName';";
    $itisSTMT = $itisConnection->prepare($sql);
    if ($itisSTMT->execute()) {
        $res = $itisSTMT->fetch(PDO::FETCH_ASSOC);
        //print_r($res);
        $tsn = $res['tsn'];
        //echo "SEcond echo: $tsn";
        if($tsn != ""){
            return $tsn;
        }
    }
    return -1;
}
function generateTSN($speciesName)
{
    $dbconn = getConnection("pelagic");
    $sql = "select id from sharkpulse where species_name like '$speciesName' order by id;";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        $tsn = queryITIS($speciesName);
        if ($tsn == -1) {
           // echo "Checking vernaculars: <br>";
            $tsn = queryITISVernaculars($speciesName);
        }
//        echo "Species name: $speciesName<br>";
//        echo "TSN: $tsn<br>";
        if ($tsn != -1) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                    //echo "Outside conditional statements: $tsn";
                //echo "Updating result for id: " . $row['id'] . "<br>";
                $sql = "update sharkpulse set tsn=$tsn where id=" . $row['id'] . ";";
                $stmt = $dbconn->prepare($sql);
                $stmt->execute();

            }
        }

    }
}
function getCompleteName($tsn){
    $dbconn = getConnection("ITIS");
    $sql= "select complete_name as species_name from taxonomic_units where tsn=$tsn limit 1;";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result[0]["tsn"] = $tsn;
    return $result;
}
function getVernacularName($tsn){
    $dbconn =getConnection("ITIS");
    $sql = "select vernacular_name as species_name from vernaculars where tsn=$tsn and language='English' limit 1;";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result[0]["tsn"] = $tsn;
    return $result;
}

function getDistinctTSN(){
    $dbconn = getConnection("pelagic");
    $sql = "select distinct(tsn) from sharkpulse;";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $result = json_encode($result);
    return $result;
}
function getRecordsFromTSN($tsn){
    $dbconn = getConnection("pelagic");
    $sql = "select id, species_name, date, time, latitude, longitude, img_name from sharkpulse where tsn=$tsn order by date DESC ;";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function getDataMiningPoints(){
    $dbconn = getConnection("pelagic");
    $sql = "select latitude, longitude, img_name, date, time, id from data_mining WHERE validated=false order by date asc;";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["value"])){
        $value = $_GET["value"];
        $tsnInDB = getDistinctTSN();
        $species = array();
        foreach($tsnInDB as $tsn){
            if($value == "Scientific Name"){
                $species[] = getCompleteName($tsn['tsn'])[0];
            }
            elseif($value == "Common Name"){
                $species[] = getVernacularName($tsn['tsn'])[0];
            }


        }
        //sort($species);
        $species = json_encode($species);
        echo $species;
    }elseif(isset($_GET['tsn'])){
        $tsn = $_GET['tsn'];

        echo json_encode(getRecordsFromTSN($tsn));
    }elseif(isset($_GET['validate'])){
        $records = getDataMiningPoints();
        $records = json_encode($records);
        echo $records;
    }
    else{
        findUnknownTSN();
    }
}
?>