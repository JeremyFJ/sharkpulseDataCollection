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
function animateMarker(marker,eventinfo){

    if (marker.getAnimation() != null) {
        marker.setAnimation(null);

    } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
    }
}
function generateMessage(date, species_name, img_name, id){
    var contentString = "" +
        "<div id='contentString'>" +
        "   <p><strong>Date: </strong>"+ date+"</p>" +
        "   <p><strong>Species Info: </strong><span style='font-style: italic;'>"+species_name+"</span></p>" +
        "   <a href='information.php?table=sharkpulse&id=" + id + "'><img src="+img_name+" width=\"150px\"></a>" +
        "</div>";
    return contentString;

}
function addInfoWindow(marker, message, row) {


    var infoWindow = new google.maps.InfoWindow({
        content: message
    });

    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.open(map, marker);
        //row.css("background","#FF8080");
        //row.focus();
        //`row.scrollIntoView();
    });

}


function generateTablesandPoints(index, val){

    var latlng = new google.maps.LatLng(val.latitude, val.longitude);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        animation: google.maps.Animation.DROP,
        title: val.species_name
    });

    var contentString = '' +
        '<tr>' +
        '   <td>'+val.id+'</td><td>'+val.date+'</td><td>'+val.species_name+'</td>' +
        '   <td>'+val.longitude+'</td><td>'+val.latitude+'</td>' +
        '   <td><a href="information.php?table=sharkpulse&id=' + val.id + '"><img src="'+val.img_name+'"></a></td>' +
        '</tr>';
    var row = $('#monitor_table tr:last');
    row.after(contentString);
    row = $('#monitor_table tr:last');
    row.mouseenter(function(eventinfo){animateMarker(marker,eventinfo);});
    row.mouseleave(function(eventinfo){animateMarker(marker,eventinfo);});
    var message = generateMessage(val.date, val.species_name, val.img_name, val.id);
    addInfoWindow(marker, message, row);
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
            //console.log(json);
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
        minZoom: 2,
//        maxZoom: 12,
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

