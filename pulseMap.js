/**
 * Created by edsan on 12/10/14.
 */

function Record(latitude, longitude, speciesInfo, id, date, time, notes, image, device_info, marker, infoWindow, row) {
    this.latitude = latitude;
    this.longitude = longitude;
    this.speciesInfo = speciesInfo;
    this.id = id;
    this.date = date;
    this.time = time;
    this.notes = notes;
    this.image = image;
    this.deviceInfo = device_info;
    this.marker = marker;
    this.infoWindow = infoWindow;
    this.row = row;

}

function generateContentString(date, speciesInfo, notes, device_info, image){
    var contentString = '<div id=contentString><p><strong>Date: </strong>'+ date+'</p>';
    if(speciesInfo != "") {
        contentString = contentString.concat('<p><strong>Species Info:</strong> '+speciesInfo+'</p>');
    }
    if(notes != ""){
        contentString.concat('<p><strong>Notes: </strong>'+notes+'</p');
    }
    if(device_info != ""){
        contentString.concat('<p><strong>Source: </strong>' + device_info + '</p>');
    }
    contentString = contentString.concat('<img src="'+image+'" width="150px"></div>');
    return contentString;
}

function getFlickerRecords(){
    var pinImage = new google.maps.MarkerImage('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2%7C99FF99');
    var allMarkers = [];
    var jqxhr = $.get( "getDataMiningPoints.php", function() {
    })
        .done(function(data) {
            var structure = JSON.parse(data);
            for(var obj in structure) {
                var latitude = structure[obj]['latitude'];
                var longitude = structure[obj]['longitude'];
                var imgURL = structure[obj]['img_url'];
                var date = structure[obj]['date'];
                var time = structure[obj]['time'];

                var contentString = generateContentString(date, "","","",imgURL);

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map: null,
                    icon: pinImage
                });
                allMarkers.push(marker);
                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });
                google.maps.event.addListener(marker,'click', (function(marker,contentString,infowindow){
                    return function() {
                        infowindow.setContent(contentString);
                        infowindow.open(map,marker);
                    };
                })(marker,contentString,infowindow));
            }
        })
        .fail(function() {
            //alert( "error" );
        })
        .always(function() {
            //alert( "finished" );
        });
    jqxhr.always(function() {
        //alert( "second finished" );
        var checkbox = document.getElementById("flickrCheckBox").addEventListener("click",function(){toggleFlickr(allMarkers)});
        return allMarkers;
    });

}

function initialize() {
    var mapOptions = {
        zoom: 1,
        center: new google.maps.LatLng(0, 0)
    };
    var table = document.getElementsByClassName('TFtable')[0];
    var count = 1;
    var records = [];
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(
        document.getElementById('legend'));
        var jqxhr = $.get( "getLocationData.php", function() {
        })
            .done(function(data) {
                var structure = JSON.parse(data);
                for(var obj in structure) {
                    var latitude = structure[obj]['latitude'];
                    var longitude = structure[obj]['longitude'];
                    var speciesInfo = structure[obj]['species_name'];
                    var id = structure[obj]['id'];
                    var date = structure[obj]['date'];
                    var time = structure[obj]['time'];
                    var notes = structure[obj]['notes'];
                    var image = structure[obj]['image'];
                    var device_info = structure[obj]['device_type'];

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(latitude, longitude),
                        map: map,
                        animation: google.maps.Animation.DROP,
                        title: speciesInfo
                    });

                    var contentString = generateContentString(date, speciesInfo, notes, device_info,image);
                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    var row = table.insertRow(count);
                    row.id = ""+count;

                    google.maps.event.addListener(marker,'click', (function(marker,contentString,infowindow){
                        return function() {
                            infowindow.setContent(contentString);
                            infowindow.open(map,marker);
                        };
                    })(marker,contentString,infowindow));



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
                    imageCol.innerHTML = "<img src='/~edsan"+image+"' width='100px'>";
                    count++;

                    var record = new Record(latitude,longitude,speciesInfo,id,date,time,notes,image,device_info,marker,infowindow,row);
                    record.row.addEventListener("mouseenter",function(e){animateMarker(records,e);});
                    record.row.addEventListener("mouseleave",function(e){stopMarker(records,e);});
                    records.push(record);
                }
            })
            .fail(function() {
                //alert( "error" );
            })
            .always(function() {
                //alert( "finished" );
            });
        jqxhr.always(function() {
            var flickrRecords = getFlickerRecords();

            var legend = document.getElementById('legend');
            var div = document.createElement('div');
            div.innerHTML = '<img src="img/Farm-Fresh_flickr.png" style="float: right;"><input type="checkbox" name="flickrCheckBox" id="flickrCheckBox">';
            legend.appendChild(div);

        });
}

function animateMarker(records, e){
    records[e.target.id-1].marker.setAnimation(google.maps.Animation.BOUNCE);
}
function stopMarker(records, e){
    records[e.target.id-1].marker.setAnimation(null);
}

function toggleFlickr(flickrRecords){
    var x = document.getElementById("flickrCheckBox");
    if(x.checked){
        for(var i = 0; i < flickrRecords.length; i++){
            flickrRecords[i].setMap(map);
        }
    }
    else{
        for(var i = 0; i < flickrRecords.length; i++){
            flickrRecords[i].setMap(null);
        }
    }
}

function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
    'callback=initialize';
    document.body.appendChild(script);
    script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js';
    document.body.appendChild(script);
    script = document.createElement('script');
    script.type = 'text/javascript';
    script.src= 'js/jquery-csv.js';
    document.body.appendChild(script);
}


