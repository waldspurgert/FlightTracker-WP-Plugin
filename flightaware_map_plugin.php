<?php
/**
 * Plugin Name: FlightAware Map Plugin
 * Description: A custom plugin to integrate FlightAware API and plot airplanes on a Google Map or display them in a table format.
 * Version: 1.0.1
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

    // Existing settings section
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

    // New section for shortcodes
    add_settings_section(
        'flightaware_map_shortcode_section',
        'Shortcodes',
        'flightaware_map_shortcode_section_callback',
        'flightaware-map-settings'
    );

    // Fields for shortcodes
    add_settings_field(
        'flight_map_shortcode',
        'Flight Map Shortcode',
        'flight_map_shortcode_field_callback',
        'flightaware-map-settings',
        'flightaware_map_shortcode_section'
    );

    add_settings_field(
        'flight_table_shortcode',
        'Flight Table Shortcode',
        'flight_table_shortcode_field_callback',
        'flightaware-map-settings',
        'flightaware_map_shortcode_section'
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
    $show_table_format = isset($options['show_table_format']) ? $options['show_table_format'] : '';
    echo '<input type="checkbox" name="flightaware_map_settings[show_table_format]" '.checked($show_table_format, 'on', false).'/>';
}

function flightaware_map_shortcode_section_callback() {
    echo '<p>Copy the following shortcodes to display the flight map and table on any page or post:</p>';
}

function flight_map_shortcode_field_callback() {
    echo '<code>[flightaware_map]</code>';
    echo '<p>Use this shortcode to display the flight map.</p>';
}

function flight_table_shortcode_field_callback() {
    echo '<code>[flightaware_map format="table"]</code>';
    echo '<p>Use this shortcode to display the flights in a table format.</p>';
}

// Function for handling the shortcode
function flightaware_map_shortcode($atts) {
    $options = get_option('flightaware_map_settings');
    $show_table_format = isset($options['show_table_format']) ? $options['show_table_format'] : '';

    $atts = shortcode_atts(array(
        'format' => $show_table_format ? 'table' : 'map'
    ), $atts, 'flightaware_map');

    if ($atts['format'] === 'table') {
        // Display the flights in a table format
    } else {
        // Display the flights on a map
    }
}
add_shortcode('flightaware_map', 'flightaware_map_shortcode');

?>
