/**
 * Created by edsan on 4/5/15.
 */

var map;
var range;
var markers = [];
var dates = [];
var pinImage = new google.maps.MarkerImage('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2%7C99FF99');
var dateMinLabel = document.getElementById("dates_min");
var dateMaxLabel = document.getElementById("dates_max");
var dateLabel = document.getElementById("date");
var datesOutput = document.getElementById("dates_output");
var resultLength = 0;



function updateMap(previousValue, currentValue){

    var difference = currentValue - previousValue;
    console.log(difference);

    if(difference < 0){
        var beginningIndex = Math.floor(dates.length * (previousValue / 100));
        var endingIndex = beginningIndex + Math.floor(dates.length * (difference / 100));
        console.log("Begin: " + beginningIndex);
        console.log("End: " + endingIndex);
        if(endingIndex != -1){
            for(var i = endingIndex; i < beginningIndex; i++){
                markers[i].setMap(null);

            }
            dateLabel.value = dates[endingIndex];
        }else{
            markers[0].setMap(null);
        }

    }
    else{

        var beginningIndex = Math.floor(dates.length * (previousValue / 100));
        var endingIndex = beginningIndex + Math.floor(dates.length * (difference / 100));
        console.log("Begin: " + beginningIndex);
        console.log("End: " + endingIndex);
        for(var i = beginningIndex; i < endingIndex; i++){
            markers[i].setMap(map);
            dateLabel.value = dates[i];
        }
    }


}

function generateMessage(date, img_name, id){
    var contentString = "" +
        "<div id='contentString'>" +
        "   <p><strong>Date: </strong>"+ date+"</p>" +
        "   <a href='information.php?table=data_mining&id=" + id + "'><img src="+img_name+" width=\"150px\"></a>" +
        "</div>"+
        "<form action='flickrForm.php?table=data_mining&id="+id+"' method='POST'> Is this a real shark (no shark in aquaria)? <br>" +
            "<label for='radio_data_mining_"+id+"_yes'>Yes</label>" +
            "<input type='radio' name='radio_data_mining_"+id+"' value='yes' id='radio_data_mining_"+id+"_yes'>" +
            "<label for='radio_data_mining_"+id+"_no'>No</label>" +
            "<input type='radio' name='radio_data_mining_"+id+"' value='no' id='radio_data_mining_"+id+"_no'><br>" +
            "<input type='hidden' value='"+id+"'>" +
            "What species?<input type=\"text\" name=\"species\">" +
            "<input type='submit' value='Submit' id='submit_button'><br>" +
        "</form>";
    return contentString;

}
function addInfoWindow(marker, message) {


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


function generateMarkers(index, val){
    var latlng = new google.maps.LatLng(val.latitude, val.longitude);
    var marker = new google.maps.Marker({
        position: latlng,
        //map: map,
        //animation: google.maps.Animation.DROP,
        title: val.species_name,
        icon: pinImage

    });
    if(index < Math.floor(resultLength * .1)){
        marker.setMap(map);
    }
    var message = generateMessage(val.date, val.img_url, val.id);
    addInfoWindow(marker, message);
    dates.push(val.date);
    markers.push(marker);

}
function getPoints(){
    $.ajax({
        url:"getDataMiningPoints.php",
        data:{
            validation:"true"
        },
        method:"GET",
        success:function(json){
            var result = $.parseJSON(json);
            resultLength = result.length;
            $.each(result, generateMarkers);

        },
        error: function (xhr, status, errorThrown) {
            //alert( "Sorry, there was a problem!" );
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },
        complete: function( xhr, status ) {
            //alert( "The request is complete!" );
            //console.log(markers);

            dateMinLabel.innerHTML = dates[0];
            var value = $("#date_range").val();
            datesOutput.innerHTML = value;
            datesOutput.style.visibility = "hidden";
            value = Math.floor(dates.length/parseInt(value));
            dateMaxLabel.innerHTML = dates[dates.length - 1];
            dateLabel.innerHTML = dates[value];
        }
    });
}
function initialize() {
    range = document.getElementById("sliding_bar");

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
    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(range);
    //range.style.visibility = "hidden";
    getPoints();
}

$("#date_range").on("input", function(){
    var previousRange = datesOutput.innerHTML;
    datesOutput.innerHTML = this.value;
    var currentRange = this.value;
    updateMap(previousRange, currentRange);
});


google.maps.event.addDomListener(window, 'load', initialize);