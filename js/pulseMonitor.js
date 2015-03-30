/**
 * Created by edsan on 3/29/15.
 */

var map;
var markers = [];
var bounds;


function deleteMarkers() {
    clearMarkers();
    markers = [];
}
function clearMarkers() {
    setAllMap(null);
}
function setAllMap(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}
function setBounds(latlng){
    bounds.extend(latlng);
}

function generateTablesandPoints(index, val){
    var latlng = new google.maps.LatLng(val.latitude, val.longitude);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        animation: google.maps.Animation.DROP,
        title: val.species_name
    });
    var contentString = '<tr><td>'+val.id+'</td><td>'+val.date+'</td><td>'+val.species_name+'</td>' +
        '<td>'+val.longitude+'</td><td>'+val.latitude+'</td><td><img src="'+val.img_name+'"></td></tr>'
    $('#monitor_table tr:last').after(contentString);
    setBounds(latlng);
    markers.push(marker);
}

function getPoints(tsn){
    $.ajax({
        url: "pulseMonitorFunctions.php",
        type: "GET",
        data: {
            tsn:tsn
        },
        success:function(json){
            console.log(json);
            $.each($.parseJSON(json), generateTablesandPoints);
        },
        error: function (xhr, status, errorThrown) {
            //alert( "Sorry, there was a problem!" );
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },
        complete: function( xhr, status ) {
            //alert( "The request is complete!" );
            map.fitBounds(bounds);
        }
    });
}

function initialize() {
    var mapOptions = {
        zoom: 3,
        minZoom: 3,
        maxZoom: 12,
        center: new google.maps.LatLng(0, 0),
        panControl: true,
        panControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE
        },
        scaleControl: true,  // fixed to BOTTOM_RIGHT
        streetViewControl: true
    };

    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    //map.controls[google.maps.ControlPosition.RIGHTCENTER].push(dropdown);
    map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(
        document.getElementById('species_box'));
    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(
        document.getElementById('sliding_bar'));
    //console.log($("#species_select").val());

}


google.maps.event.addDomListener(window, 'load', initialize);

$(document).ajaxComplete(function(event,request, setting) {
    // Add same check you use on your setTimeout to make sure that "form-item-linkit-rel" input exists.
    // You could also use some of the values of the "setting" param of ajaxComplete() function to use additional checks.
    //console.log("Event:  " + event);
    //console.log("Request:  " + request);
    //console.log("Setting:  " + setting.url);
    //$(document).unbind('ajaxComplete');
    if(setting.url === "pulseMonitorFunctions.php?value=Scientific+Name" ||
        setting.url === "pulseMonitorFunctions.php?value=Common+Name"){
        //console.log("Triggered response")
        bounds = new google.maps.LatLngBounds();
        var tsn = $("#species_select").val();
        deleteMarkers();
        $('#monitor_table > tbody > tr:nth-child(n+2)').remove();
        getPoints(tsn);

    }

});

$("#species_select").change(function(){
    bounds = new google.maps.LatLngBounds();
    deleteMarkers();
    $('#monitor_table > tbody > tr:nth-child(n+2)').remove();
    var tsn = $("#species_select").val();
    getPoints(tsn)
});