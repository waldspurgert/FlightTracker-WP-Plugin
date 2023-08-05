<?php
/**
 * Plugin Name: Flight Tracker
 * Plugin URI: www.flykphl.com
 * Description: Track flights from a certain area and display them on a map.
 * Version: 1.0.0
 * Author: Fly KPHL
 * Author URI: www.flykphl.com
 * Text Domain: flight-tracker
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FLIGHT_TRACKER_VERSION', '1.0.0');
define('FLIGHT_TRACKER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FLIGHT_TRACKER_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include necessary files
require_once FLIGHT_TRACKER_PLUGIN_PATH . 'admin-settings.php';
require_once FLIGHT_TRACKER_PLUGIN_PATH . 'map-shortcode.php';

// Enqueue scripts for the settings page
function flight_tracker_admin_enqueue_scripts() {
    if (isset($_GET['page']) && $_GET['page'] === 'flight-tracker') {
        wp_enqueue_script('flight-tracker-admin', FLIGHT_TRACKER_PLUGIN_URL . 'js/admin-settings.js', array('jquery'), '1.0.0', true);
    }
}
add_action('admin_enqueue_scripts', 'flight_tracker_admin_enqueue_scripts');
