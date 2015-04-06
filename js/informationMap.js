var geocoder;

function initialize() {
    geocoder = new google.maps.Geocoder();
    var latitude = document.getElementById("latitude").innerHTML;
    var longitude = document.getElementById("longitude").innerHTML;
    var myLatlng = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
        zoom: 3,
        center: myLatlng
    };
    var map = new google.maps.Map(document.getElementById('map-canvas-info'),
        mapOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map
    });
    var placesListItem = document.getElementById("places");
    geocoder.geocode({'latLng': myLatlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results) {

            }
        } else {
           // alert("Geocoder failed due to: " + status);
        }
    });
}

function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp' +
    '&signed_in=true&callback=initialize';
    document.body.appendChild(script);
}

//window.onload = loadScript;
