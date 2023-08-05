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
    $google_maps_api_key = get_option('flightaware_map_settings')['goog<?php
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
    echo '<input type="text" name="flightaware_map_settings[flightaware_username]" value="' . esc_attr($options['flightaware_username'] ?? 'waldspurgert') . '" />';
}

function flightaware_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[flightaware_api_key]" value="' . esc_attr($options['flightaware_api_key'] ?? 'cwICdVV8dCeS5gKqoUAOfWw25k169f2x') . '" />';
}

function google_maps_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[google_maps_api_key]" value="' . esc_attr($options['google_maps_api_key'] ?? 'YOUR_GOOGLE_MAPS_API_KEY') . '" />';
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
    ob_start();
    // The rest of your shortcode callback code...
    return ob_get_clean();
}
add_shortcode('flightaware_map', 'flightaware_map_shortcode_callback');

// Function to fetch data from the FlightAware API
function flightaware_map_get_flight_data() {
    // The rest of your API fetch function code...
}

// Deactivation hook
function flightaware_map_deactivate() {
    // Add any deactivation tasks here if needed
}
register_deactivation_hook(__FILE__, 'flightaware_map_deactivate');
le_maps_api_key'];
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
    include plugin_dir_path(__FILE__) . 'templates/flightaware_map_settings_template.php';
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
    echo '<input type="text" name="flightaware_map_settings[flightaware_username]" value="' . esc_attr($options['flightaware_username'] ?? 'waldspurgert') . '" />';
}

function flightaware_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[flightaware_api_key]" value="' . esc_attr($options['flightaware_api_key'] ?? 'cwICdVV8dCeS5gKqoUAOfWw25k169f2x') . '" />';
}

function google_maps_api_key_field_callback() {
    $options = get_option('flightaware_map_settings');
    echo '<input type="text" name="flightaware_map_settings[google_maps_api_key]" value="' . esc_attr($options['google_maps_api_key'] ?? 'YOUR_GOOGLE_MAPS_API_KEY') . '" />';
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
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/flightaware_map_template.php';
    return ob_get_clean();
}
add_shortcode('flightaware_map', 'flightaware_map_shortcode_callback');

// Function to fetch data from the FlightAware API
function flightaware_map_get_flight_data() {
    $options = get_option('flightaware_map_settings');
    $username = $options['flightaware_username'] ?? 'waldspurgert';
    $api_key = $options['flightaware_api_key'] ?? 'cwICdVV8dCeS5gKqoUAOfWw25k169f2x';
    $custom_lat = $options['custom_track_area_lat'] ?? '';
    $custom_lng = $options['custom_track_area_lng'] ?? '';

    // The rest of your API fetch function code...
}

// Deactivation hook
function flightaware_map_deactivate() {
    // Add any deactivation tasks here if needed
}
register_deactivation_hook(__FILE__, 'flightaware_map_deactivate');
