<?php /*require('wp-blog-header.php');*/?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="js/pulsemap.js"></script>
        <link rel="stylesheet" type="text/css" href="css/tableStyles.css">
        <meta charset="utf-8">
    </head>
    <body onload="loadScript()">
	  <div id="menu">
   <ul>
    </ul>
  </div>
        <div id="map-canvas"></div>
        <div id="legend">Toggle Markers</div>
        <div id="sliding_bar">
            <div id="output_div"><output for="date_range" id="date"></output></div>
            <ouput for="date_range" id="dates_min">5</ouput>
            <input id="date_range" type="range" min="0" max="100" value="10"/>
            <ouput for="date_range" id="dates_max">5</ouput>
            <output for="date_range" id="dates_output">10</output>
        </div>
	<div id=tableDiv>
            <table class="TFtable">
                <tr>
                    <th>ID</th><th>Date</th><th>Time</th><th>Species Name</th><th>Longitude</th><th>Latitude</th><th>Notes</th><th>Source</th><th>Image</th>
                </tr>
            </table>
	</div>
    </body>
    <script>
        function reqListener () {
            console.log(this.responseText);
        }
    </script>
</html>

