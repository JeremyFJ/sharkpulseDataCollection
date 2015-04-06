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
    <div id="output_div" style="text-align: center"><output for="date_range" id="date"></output></div>
    <ouput for="date_range" id="dates_min"></ouput>
    <input id="date_range" type="range" min="0" max="100" value="10"/>
    <ouput for="date_range" id="dates_max"></ouput>
    <output for="date_range" id="dates_output"></output>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https:///maps.googleapis.com/maps/api/js?key=AIzaSyD2TQJAVhbKDfweq5rWpNMTWQyOFIZ36YQ"></script>
<script type="text/javascript" src="js/validationMonitor.js"></script>
</body>
</html>