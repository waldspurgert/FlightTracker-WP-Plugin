<?php
/**
 * Plugin Name: FlightAware Map Plugin
 * Description: A custom plugin to integrate FlightAware API and plot airplanes on a Google Map or display them in a table format.
 * Version: 1.0
 * Author: Trevor Waldspurger, Fly KPHL
 */

// Start output buffering to prevent premature header sending
ob_start();

// Enqueue scripts and styles
function flightaware_map_enqueue_scripts() {
    $options = get_option('flightaware_map_settings');
    $google_maps_api_key = isset($options['google_maps_api_key']) ? $options['google_maps_api_key'] : '';

    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&libraries=places', array(), '1.0', true);
    wp_enqueue_script('flightaware-map-script', plugin_dir_url(__FILE__) . 'flightaware_map_script.js', array('google-maps'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'flightaware_map_enqueue_scripts');

// Add the settings page to the admin menu
function flightaware_map_add_settings_page() {
    add_menu_page(
        'FlightAware Map Settings',
        'FlightAware Map',
        'manage_options',
        'flightaware-map-settings',
        'flightaware_map_settings_page',
        'dashicons-admin-plugins',
        99
    );
}
add_action('admin_menu', 'flightaware_map_add_settings_page');

// Callback function for rendering the settings page
function flightaware_map_settings_page() {
    $options = get_option('flightaware_map_settings');
    ?>
    <div class="wrap">
        <h1>FlightAware Map Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('flightaware_map_options'); ?>
            <?php do_settings_sections('flightaware-map-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings and fields for the settings page
function flightaware_map_settings_init() {
    register_setting('flightaware_map_options', 'flightaware_map_settings');

    add_settings_section(
        'flightaware_map_section',
        'API Settings',
        'flightaware_map_section_callback',
        'flightaware-map-settings'
    );

    add_settings_field(
        'flightaware_api_key',
        'FlightAware API Key',
        'flightaware_api_key_field_callback',
        'flightaware-map-settings',
        'flightaware_map_section'
    );

    add_settings_field(
        'google_maps_api_key',
        'Google Maps API Key',
        'google_maps_api_key_field_callback',
        'flightaware-map-settings',
        'flightaware_map_section'
    );

    add_settings_section(
        'flightaware_map_track_area_section',
        'Custom Area to Track Planes',
        'flightaware_map_track_area_section_callback',
        'flightaware-map-settings'
    );

    add_settings_field(
        'custom_track_area_lat',
        'Latitude',
        'custom_track_area_lat_field_callback',
        'flightaware-map-settings',
        'flightaware_map_track_area_section'
    );

    add_settings_field(
        'custom_track_area_lng',
        'Longitude',
        'custom_track_area_lng_field_callback',
        'flightaware-map-settings',
        'flightaware_map_track_area_section'
    );

    // Add a new section for the table format option
    add_settings_section(
        'flightaware_map_table_section',
        'Table Format Settings',
        'flightaware_map_table_section_callback',
        'flightaware-map-settings'
    );

    // Add the new option field for the table format
    add_settings_field(
        'show_table_format',
        'Show Planes in Table Format',
        'show_table_format_field_callback',
        'flightaware-map-settings',
        'flightaware_map_table_section'
    );
}
add_action('admin_init', 'flightaware_map_settings_init');

// Callback function for the sections
function flightaware_map_section_callback() {
    echo '<p>Enter your FlightAware API key and Google Maps API Key below:</p>';
}

function flightaware_map_track_area_section_callback() {
    echo '<p>Enter latitude and longitude coordinates for the area you want to track planes.</p>';
}

// Callback functions for the fields
function flightaware_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    $api_key = isset($options['flightaware_api_key']) ? $options['flightaware_api_key'] : '';
    echo '<input type="text" name="flightaware_map_settings[flightaware_api_key]" value="' . esc_attr($api_key) . '" />';
}

function google_maps_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    $google_maps_api_key = isset($options['google_maps_api_key']) ? $options['google_maps_api_key'] : '';
    echo '<input type="text" name="flightaware_map_settings[google_maps_api_key]" value="' . esc_attr($google_maps_api_key) . '" />';
}

function custom_track_area_lat_field_callback() {
    $options = get_option('flightaware_map_settings');
    $custom_track_area_lat = isset($options['custom_track_area_lat']) ? $options['custom_track_area_lat'] : '';
    echo '<input type="text" name="flightaware_map_settings[custom_track_area_lat]" value="' . esc_attr($custom_track_area_lat) . '" />';
}

function custom_track_area_lng_field_callback() {
    $options = get_option('flightaware_map_settings');
    $custom_track_area_lng = isset($options['custom_track_area_lng']) ? $options['custom_track_area_lng'] : '';
    echo '<input type="text" name="flightaware_map_settings[custom_track_area_lng]" value="' . esc_attr($custom_track_area_lng) . '" />';
}

function flightaware_map_table_section_callback() {
    echo '<p>Select whether to show planes in table format or not.</p>';
}

function show_table_format_field_callback() {
    $options = get_option('flightaware_map_settings');
    $show_table_format = isset($options['show_table_format']) ? $options['show_table_format'] : '0';
    echo '<input type="checkbox" name="flightaware_map_settings[show_table_format]" value="1" ' . checked(1, $show_table_format, false) . ' />';
}

// Shortcode callback to display the map or table format
function flightaware_map_shortcode_callback($atts) {
    ob_start();
    $flight_data = flightaware_map_get_flight_data();

    // Check if the table format option is enabled
    $options = get_option('flightaware_map_settings');
    $show_table_format = isset($options['show_table_format']) ? $options['show_table_format'] : '0';

    if ($flight_data) {
        if (isset($atts['format']) && $atts['format'] === 'table') {
            // Display the flight data in table format
            echo '<table>';
            echo '<thead><tr><th>Flight ID</th><th>Latitude</th><th>Longitude</th><th>Altitude</th></tr></thead>';
            echo '<tbody>';
            foreach ($flight_data['flights'] as $flight) {
                echo '<tr>';
                echo '<td>' . $flight['flight_id'] . '</td>';
                echo '<td>' . $flight['latitude'] . '</td>';
                echo '<td>' . $flight['longitude'] . '</td>';
                echo '<td>' . $flight['altitude'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            // Display the flight data on the map
            // For example:
            // echo '<div id="flightaware-map"></div>';
        }
    } else {
        echo 'Unable to fetch flight data from FlightAware API.';
    }

    return ob_get_clean();
}
add_shortcode('flightaware_map', 'flightaware_map_shortcode_callback');

// Function to fetch data from the FlightAware API
function flightaware_map_get_flight_data() {
    $options = get_option('flightaware_map_settings');
    $api_key = isset($options['flightaware_api_key']) ? $options['flightaware_api_key'] : '';

    // Customize the URL based on the FlightAware API endpoint and query parameters
    $custom_track_area_lat = isset($options['custom_track_area_lat']) ? $options['custom_track_area_lat'] : '';
    $custom_track_area_lng = isset($options['custom_track_area_lng']) ? $options['custom_track_area_lng'] : '';
    $api_url = 'https://aeroapi.flightaware.com/aeroapi/v3/your-endpoint-here?lat=' . $custom_track_area_lat . '&lng=' . $custom_track_area_lng;

    // Set up the request arguments
    $request_args = array(
        'headers' => array(
            'x-apikey' => $api_key,
        ),
    );

    // Make the HTTP request to the FlightAware API
    $response = wp_remote_get($api_url, $request_args);

    // Check if the request was successful
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        // Error handling if the API request failed
        return false;
    }

    // Parse the JSON response
    $data = json_decode(wp_remote_retrieve_body($response), true);

    // Process the data and return the necessary information
    // For example:
    // $flights = $data['flights'];
    // $filtered_data = array();
    // foreach ($flights as $flight) {
    //     $filtered_data[] = array(
    //         'flight_id' => $flight['flight_id'],
    //         'latitude' => $flight['latitude'],
    //         'longitude' => $flight['longitude'],
    //         'altitude' => $flight['altitude'],
    //         // Add more data as needed...
    //     );
    // }
    // return $filtered_data;

    // Return your processed data or false in case of an error
    return $data;
}

// Deactivation hook
function flightaware_map_deactivate() {
    // Deactivation tasks if needed...
}
register_deactivation_hook(__FILE__, 'flightaware_map_deactivate');
