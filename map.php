<script type="text/javascript" src="pulseMap.js"></script>
<link rel="stylesheet" type="text/css" href="tableStyles.css">
<?php
/**
 * Created by PhpStorm.
 * User: edsan
 * Date: 12/10/14
 * Time: 9:53 PM
 */


echo '<body onload="loadScript()">';
echo '<div id="map-canvas"></div>';
echo '<table class="TFtable">';
echo '<tr>';
echo '<th>ID</th><th>Date</th><th>Time</th><th>Species Name</th><th>Longitude</th><th>Latitude</th><th>Notes</th><th>Source</th><th>Image</th>';
echo '</tr>';
echo '</table>';
echo '</body>';

?>

<script>
    function reqListener () {
        console.log(this.responseText);
    }
</script>


