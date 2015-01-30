/**
 * Created by edsan on 12/10/14.
 */
function initialize() {
    var mapOptions = {
        zoom: 1,
        center: new google.maps.LatLng(0, 0)
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //console.log(xmlhttp.responseText);
            var structure = JSON.parse(xmlhttp.responseText);
            var allMarkers = [];
            var rows = [];
            var allInfoWindows = [];
            var table = document.getElementsByClassName('TFtable')[0];
            var count = 1;
            for(var obj in structure){

                //Creates the markers and adds content boxes to each of the markers
                var latitude = structure[obj]['latitude'];
                var longitude = structure[obj]['longitude'];
                var speciesInfo = structure[obj]['species_name'];
                var id = structure[obj]['id'];
                var date = structure[obj]['date'];
                var time = structure[obj]['time'];
                var notes = structure[obj]['notes'];
                var image = structure[obj]['image'];
                var device_info = structure[obj]['device_type']
                //console.log(latitude + " " + longitude + " " + speciesInfo + " " + id + " " + date + " " + time + " " + notes + " " + image);
                //if(latitude == 0 && longitude == 0){
                //    continue;
                //}

                var contentString = '<div id=contentString><h3>Species Info: '+speciesInfo+'</h3></div>';
                var infowindow = new google.maps.InfoWindow({
                        content: speciesInfo
                 });

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map: map,
                    animation: google.maps.Animation.DROP,
                    title: speciesInfo
                });
                allMarkers.push(marker);
                //allInfoWindows.push(infowindow);
                google.maps.event.addListener(marker,'click', (function(marker,contentString,infowindow){
                    return function() {
                        infowindow.setContent(contentString);
                        infowindow.open(map,marker);
                    };
                })(marker,contentString,infowindow));

                var row = table.insertRow(count);
                row.id = ""+count;
                //allMarkers[row_id].setAnimation(google.maps.Animation.BOUNCE)
                row.addEventListener('mouseenter', function(e){animateMarker(allMarkers,e);});
                row.addEventListener('mouseleave', function(e){stopMarker(allMarkers,e);});




                var idCol = row.insertCell(0);
                var dateCol = row.insertCell(1);
                var timeCol = row.insertCell(2);
                var speciesCol = row.insertCell(3);
                var longitudeCol = row.insertCell(4);
                var latitudeCol = row.insertCell(5);
                var notesCol = row.insertCell(6);
                var sourceCol = row.insertCell(7);
                var imageCol = row.insertCell(8);

                idCol.innerHTML = id;
                dateCol.innerHTML = date;
                timeCol.innerHTML = time;
                speciesCol.innerHTML = speciesInfo;
                longitudeCol.innerHTML = longitude;
                latitudeCol.innerHTML = latitude;
                notesCol.innerHTML = notes;
                sourceCol.innerHTML = device_info;
                imageCol.innerHTML = "<img src='/~edsan"+image+"' height='10%'>";
                rows.push(row);
                count++;


            }
            //var i =0;
            //for(var r in rows){
            //    rows[i].onmouseover = function(){allMarkers..setAnimation(google.maps.Animation.BOUNCE)};
            //    rows[i].onmouseout = function(){allMarkers[i].setAnimation(null)};
            //    i++;
            //
            //}
        }
    };
    xmlhttp.open("GET", "getLocationData.php", true);
    xmlhttp.send();
}

function animateMarker(markers, e){
    markers[e.target.id-1].setAnimation(google.maps.Animation.BOUNCE);
}
function stopMarker(markers, e){
    markers[e.target.id-1].setAnimation(null);
}

function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
    'callback=initialize';
    document.body.appendChild(script);
}


