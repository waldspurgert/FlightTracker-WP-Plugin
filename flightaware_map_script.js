function initMap() {
    var customAreaLat = parseFloat(<?php echo json_encode(isset($options['custom_track_area_lat']) ? $options['custom_track_area_lat'] : '0'); ?>);
    var customAreaLng = parseFloat(<?php echo json_encode(isset($options['custom_track_area_lng']) ? $options['custom_track_area_lng'] : '0'); ?>);

    var mapOptions = {
        center: customAreaLat && customAreaLng ? { lat: customAreaLat, lng: customAreaLng } : { lat: 39.9526, lng: -75.1652 }, // Philadelphia coordinates as default
        zoom: 10,
    };

    var map = new google.maps.Map(document.getElementById("flightaware-map"), mapOptions);

    // Fetch data from the FlightAware API
    var flightData = <?php echo json_encode(flightaware_map_get_flight_data()); ?>;

    // Process and display plane markers on the map
    for (var i = 0; i < flightData.length; i++) {
        var plane = flightData[i];
        var marker = new google.maps.Marker({
            position: { lat: parseFloat(plane.latitude), lng: parseFloat(plane.longitude) },
            map: map,
            title: plane.ident + ' (' + plane.aircrafttype + ')',
        });

        // Add additional info to the marker (optional)
        var infoWindowContent = 'Origin: ' + plane.origin + '<br>Destination: ' + plane.destination + '<br>Altitude: ' + plane.altitude + ' ft<br>Heading: ' + plane.heading + '°<br>Speed: ' + plane.speed + ' knots';
        var infoWindow = new google.maps.InfoWindow({
            content: infoWindowContent,
        });

        // Show info window on marker click
        marker.addListener('click', function () {
            infoWindow.open(map, marker);
        });
    }
}
google.maps.event.addDomListener(window, 'load', initMap);