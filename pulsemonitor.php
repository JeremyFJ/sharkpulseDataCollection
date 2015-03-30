<?php require "pulseMonitorFunctions.php";?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/tableStyles.css">
    <title></title>
</head>
<body>

    <div id="map-canvas"></div>
<!--    <div id="legend">Toggle Markers</div>-->
    <div id="sliding_bar">
        <div id="output_div"><output for="date_range" id="date"></output></div>
        <ouput for="date_range" id="dates_min"></ouput>
        <input id="date_range" type="range" min="0" max="100" value="10"/>
        <ouput for="date_range" id="dates_max"></ouput>
        <output for="date_range" id="dates_output"></output>
    </div>
    <div id="species_box">
        <label for="sn_dropbox_radio">Scientific Name</label> <input type="radio" value="Scientific Name" name="dropbox_radio" id="sn_dropbox_radio" checked>
        <label for="cn_dropbox_radio">Common Name</label><input type="radio" value="Common Name" name="dropbox_radio" id="cn_dropbox_radio"><br>
        <select id="species_select">
    <!--<?php //getDistinctSpecies()?>-->
        </select>
    </div>
    <div id=tableDiv>
        <table class="TFtable" id="monitor_table">
            <tr>
                <th>ID</th><th>Date</th><th>Species Name</th><th>Longitude</th><th>Latitude</th><th>Image</th>
            </tr>
        </table>
    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="js/fillDropDown.js"></script>
<script src="https:///maps.googleapis.com/maps/api/js?key=AIzaSyD2TQJAVhbKDfweq5rWpNMTWQyOFIZ36YQ"></script>
<script type="text/javascript" src="js/pulseMonitor.js"></script>
</html>