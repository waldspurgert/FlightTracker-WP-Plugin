// flightaware_map_script.js

(function ($) {
    function initMap() {
        var map = new google.maps.Map(document.getElementById('flightaware-map'), {
            center: { lat: YOUR_CENTER_LATITUDE, lng: YOUR_CENTER_LONGITUDE },
            zoom: 10,
        });

        // Call the function to fetch the flight data from the server
        fetchFlightData(map);
    }

    function fetchFlightData(map) {
        $.ajax({
            url: 'https://aeroapi.flightaware.com/aeroapi/v3/your-endpoint-here',
            method: 'GET',
            dataType: 'json',
            headers: {
                'x-apikey': YOUR_FLIGHTAWARE_API_KEY,
            },
            success: function (data) {
                // Process the flight data and plot the airplanes on the map or display in table format
                if (data && data.flights && data.flights.length > 0) {
                    if (YOUR_SHOW_TABLE_FORMAT === '1') {
                        displayFlightsInTable(data.flights);
                    } else {
                        displayFlightsOnMap(data.flights, map);
                    }
                } else {
                    console.error('No flight data found.');
                }
            },
            error: function () {
                console.error('Error fetching flight data from FlightAware API.');
            },
        });
    }

    function displayFlightsOnMap(flights, map) {
        // Use the 'flights' data to plot airplanes on the map using Google Maps API
        // For example:
        // flights.forEach(function (flight) {
        //     var marker = new google.maps.Marker({
        //         position: { lat: flight.latitude, lng: flight.longitude },
        //         map: map,
        //         title: 'Flight ID: ' + flight.flight_id,
        //     });
        // });
    }

    function displayFlightsInTable(flights) {
        // Use the 'flights' data to display airplanes in table format
        // For example:
        // var tableHtml = '<table>';
        // tableHtml += '<thead><tr><th>Flight ID</th><th>Latitude</th><th>Longitude</th><th>Altitude</th></tr></thead>';
        // tableHtml += '<tbody>';
        // flights.forEach(function (flight) {
        //     tableHtml += '<tr>';
        //     tableHtml += '<td>' + flight.flight_id + '</td>';
        //     tableHtml += '<td>' + flight.latitude + '</td>';
        //     tableHtml += '<td>' + flight.longitude + '</td>';
        //     tableHtml += '<td>' + flight.altitude + '</td>';
        //     tableHtml += '</tr>';
        // });
        // tableHtml += '</tbody>';
        // tableHtml += '</table>';

        // Display the 'tableHtml' on the page or append it to a specific element
        // For example:
        // $('#table-container').html(tableHtml);
    }

    // Call the initMap function when the page is loaded
    google.maps.event.addDomListener(window, 'load', initMap);
})(jQuery);
