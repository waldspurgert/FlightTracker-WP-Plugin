<?php
/**
 * Plugin Name: FlightAware Map Plugin
 * Description: A custom plugin to integrate FlightAware API and plot airplanes on a Google Map.
 * Version: 1.0
 * Author: Trevor Waldspurger
 */

// Start output buffering to prevent premature header sending
ob_start();

// Enqueue scripts and styles
function flightaware_map_enqueue_scripts() {
    $google_maps_api_key = get_option('flightaware_map_settings')['google_maps_api_key'];
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
    ob_start(); // Start output buffering

    include plugin_dir_path(__FILE__) . 'templates/flightaware_map_settings_template.php';

    echo ob_get_clean(); // Output the content
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
        'flightaware_username',
        'FlightAware Username',
        'flightaware_username_field_callback',
        'flightaware-map-settings',
        'flightaware_map_section'
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
}
add_action('admin_init', 'flightaware_map_settings_init');

// Callback function for the section
function flightaware_map_section_callback() {
    echo '<p>Enter your FlightAware API credentials and Google Maps API Key below:</p>';
}

// Callback functions for the fields
function flightaware_username_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[flightaware_username]" value="' . esc_attr($options['flightaware_username'] ?? '') . '" />';
}

function flightaware_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[flightaware_api_key]" value="' . esc_attr($options['flightaware_api_key'] ?? '') . '" />';
}

function google_maps_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[google_maps_api_key]" value="' . esc_attr($options['google_maps_api_key'] ?? '') . '" />';
}

// Callback function for the custom area section
function flightaware_map_track_area_section_callback() {
    echo '<p>Enter latitude and longitude coordinates for the area you want to track planes.</p>';
}

// Callback functions for the custom area fields
function custom_track_area_lat_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[custom_track_area_lat]" value="' . esc_attr($options['custom_track_area_lat'] ?? '') . '" />';
}

function custom_track_area_lng_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[custom_track_area_lng]" value="' . esc_attr($options['custom_track_area_lng'] ?? '') . '" />';
}

// Shortcode callback to display the map
function flightaware_map_shortcode_callback() {
    ob_start(); // Start output buffering

    echo '<div id="flightaware-map" style="width: 100%; height: 500px;"></div>';

    echo ob_get_clean(); // Output the content
}
add_shortcode('flightaware_map', 'flightaware_map_shortcode_callback');

// Function to fetch data from the FlightAware API
function flightaware_map_get_flight_data() {
    $options = get_option('flightaware_map_settings');
    $username = $options['flightaware_username'] ?? '';
    $api_key = $options['flightaware_api_key'] ?? '';

    // If the API credentials are not provided, return empty data
    if (empty($username) || empty($api_key)) {
        return array();
    }

    // Construct the API URL
    $api_url = "https://flightaware.com/api/v1/inFlightInfo?ident={$username}&howMany=10&offset=0";

    // Set up cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic ' . base64_encode("{$username}:{$api_key}"),
    ));

    // Execute the cURL session and get the API response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $flight_data = json_decode($response, true);

    return $flight_data['flights'] ?? array();
}
