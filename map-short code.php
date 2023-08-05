<?php
// Register the shortcode
function flight_tracker_map_shortcode($atts) {
    $atts = shortcode_atts(array(), $atts);
    ob_start();
    ?>
    <!-- Add your Google Maps API integration code here -->
    <div id="flight-tracker-map" style="height: 600px;"></div>
    <script>
    // Initialize Google Maps
    function initMap() {
        const map = new google.maps.Map(document.getElementById('flight-tracker-map'), {
            center: { lat: <?php echo esc_attr(get_option('flight_tracker_latitude', '')); ?>, lng: <?php echo esc_attr(get_option('flight_tracker_longitude', '')); ?> },
            zoom: <?php echo esc_attr(get_option('flight_tracker_zoom', 8)); ?>
        });

        // Function to add a marker on the map for each flight
        function addFlightMarker(flight) {
            const flightMarker = new google.maps.Marker({
                position: { lat: flight.latitude, lng: flight.longitude },
                map: map,
                title: flight.ident
            });

            // Create info window content with flight data
            const infoWindowContent = `
                <h2>${flight.ident}</h2>
                <p>Heading: ${flight.heading}</p>
                <p>Altitude: ${flight.altitude} ft</p>
                <p>Speed: ${flight.groundspeed} knots</p>
                <p>Tail Number: ${flight.tailnumber}</p>
                <p>Registration Number: ${flight.ident}</p>
                <p>Squawk Code: ${flight.squawk}</p>
            `;

            const infoWindow = new google.maps.InfoWindow({
                content: infoWindowContent
            });

            // Add a click event listener to show the info window when the marker is clicked
            flightMarker.addListener('click', () => {
                infoWindow.open(map, flightMarker);
            });
        }

        // Replace 'YOUR_FLIGHTAWARE_API_KEY' with your actual FlightAware API key
        const flightAwareApiKey = flightTrackerSettings.flightAwareApiKey;
        const flightAwareApiUrl = 'https://aeroapi.flightaware.com/aeroapi/flightxml/v3/flights/search'; // FlightAware API search endpoint

        // Get latitude and longitude from the user's settings
        const latitude = <?php echo esc_attr(get_option('flight_tracker_latitude', '')); ?>;
        const longitude = <?php echo esc_attr(get_option('flight_tracker_longitude', '')); ?>;
        
        // Construct the query string for active flights around the user's location
        const searchQuery = `-latlong "${latitude - 0.1} ${longitude - 0.1} ${latitude + 0.1} ${longitude + 0.1}"`;

        // Make the FlightAware API request to get active flight data
        fetch(flightAwareApiUrl + "?" + searchQuery, {
            method: 'GET',
            headers: {
                'x-apikey': flightAwareApiKey,
            },
        })
        .then(response => response.json())
        .then(data => {
            // Process the flight data and add markers on the map
            if (data && data.flights) {
                data.flights.forEach(flight => {
                    addFlightMarker(flight);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching FlightAware data:', error);
        });
    }

    // Load the Google Maps API with the callback to initialize the map
    function loadGoogleMapsScript() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${flightTrackerSettings.googleMapsApiKey}&callback=initMap`;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Trigger loading the Google Maps API script
    loadGoogleMapsScript();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('flight_tracker_map', 'flight_tracker_map_shortcode');
