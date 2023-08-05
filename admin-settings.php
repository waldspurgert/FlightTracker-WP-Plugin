<?php
// Add settings page to the WordPress dashboard
function flight_tracker_admin_menu() {
    add_options_page(
        'Flight Tracker Settings',
        'Flight Tracker',
        'manage_options',
        'flight-tracker',
        'flight_tracker_settings_page'
    );
}
add_action('admin_menu', 'flight_tracker_admin_menu');

// Render the settings page
function flight_tracker_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if form submitted
    if (isset($_POST['submit'])) {
        update_option('flight_tracker_flightaware_key', sanitize_text_field($_POST['flightaware_key']));
        update_option('flight_tracker_google_maps_key', sanitize_text_field($_POST['google_maps_key']));
        update_option('flight_tracker_latitude', sanitize_text_field($_POST['latitude']));
        update_option('flight_tracker_longitude', sanitize_text_field($_POST['longitude']));
        update_option('flight_tracker_zoom', absint($_POST['zoom']));
        echo '<div class="notice notice-success"><p>Settings updated successfully!</p></div>';
    }

    // Get saved settings
    $flightaware_key = get_option('flight_tracker_flightaware_key', '');
    $google_maps_key = get_option('flight_tracker_google_maps_key', '');
    $latitude = get_option('flight_tracker_latitude', '');
    $longitude = get_option('flight_tracker_longitude', '');
    $zoom = get_option('flight_tracker_zoom', 8);

    // Settings page content
    ?>
    <div class="wrap">
        <h1>Flight Tracker Settings</h1>
        <form method="post" action="">
            <label for="flightaware_key">FlightAware API Key:</label>
            <input type="text" id="flightaware_key" name="flightaware_key" value="<?php echo esc_attr($flightaware_key); ?>" required>
            
            <label for="google_maps_key">Google Maps API Key:</label>
            <input type="text" id="google_maps_key" name="google_maps_key" value="<?php echo esc_attr($google_maps_key); ?>" required>
            
            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" value="<?php echo esc_attr($latitude); ?>" required>
            
            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" value="<?php echo esc_attr($longitude); ?>" required>
            
            <label for="zoom">Default Zoom Level:</label>
            <input type="number" id="zoom" name="zoom" value="<?php echo esc_attr($zoom); ?>" required>
            
            <input type="submit" name="submit" class="button button-primary" value="Save Settings">
        </form>
    </div>
    <?php

    // Enqueue script to pass API keys to JavaScript
    wp_enqueue_script('flight-tracker-admin', FLIGHT_TRACKER_PLUGIN_URL . 'js/admin-settings.js', array('jquery'), '1.0.0', true);
    wp_localize_script('flight-tracker-admin', 'flightTrackerSettings', array(
        'flightAwareApiKey' => $flightaware_key,
        'googleMapsApiKey' => $google_maps_key,
    ));
}
