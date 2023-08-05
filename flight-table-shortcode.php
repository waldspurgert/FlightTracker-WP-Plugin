<?php
// Register the shortcode to display active flights in a table format
function flight_tracker_table_shortcode($atts) {
    $atts = shortcode_atts(array(), $atts);
    ob_start();
    ?>
    <!-- Add your HTML and PHP code here to fetch and display active flights in a table -->
    <table>
        <thead>
            <tr>
                <th>Flight</th>
                <th>Heading</th>
                <th>Altitude</th>
                <th>Speed</th>
                <th>Tail Number</th>
                <th>Registration Number</th>
                <th>Squawk Code</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Replace 'YOUR_FLIGHTAWARE_API_KEY' with your actual FlightAware API key
            $flightAwareApiKey = 'YOUR_FLIGHTAWARE_API_KEY';
            $flightAwareApiUrl = 'https://aeroapi.flightaware.com/aeroapi/flightxml/v3/flights/search'; // FlightAware API search endpoint

            // Get latitude and longitude from the user's settings
            $latitude = get_option('flight_tracker_latitude', '');
            $longitude = get_option('flight_tracker_longitude', '');
            
            // Construct the query string for active flights around the user's location
            $searchQuery = '-latlong "' . ($latitude - 0.1) . ' ' . ($longitude - 0.1) . ' ' . ($latitude + 0.1) . ' ' . ($longitude + 0.1) . '"';

            // Make the FlightAware API request to get active flight data
            $response = wp_remote_get($flightAwareApiUrl . '?' . $searchQuery, array(
                'headers' => array(
                    'x-apikey' => $flightAwareApiKey,
                ),
            ));

            if (!is_wp_error($response) && $response['response']['code'] === 200) {
                $data = json_decode($response['body'], true);
                if ($data && isset($data['flights'])) {
                    foreach ($data['flights'] as $flight) {
                        ?>
                        <tr>
                            <td><?php echo $flight['ident']; ?></td>
                            <td><?php echo $flight['heading']; ?></td>
                            <td><?php echo $flight['altitude']; ?> ft</td>
                            <td><?php echo $flight['groundspeed']; ?> knots</td>
                            <td><?php echo $flight['tailnumber']; ?></td>
                            <td><?php echo $flight['ident']; ?></td>
                            <td><?php echo $flight['squawk']; ?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}
add_shortcode('flight_tracker_table', 'flight_tracker_table_shortcode');
