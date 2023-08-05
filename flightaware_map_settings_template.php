<form method="post" class="flightaware-map-settings-form">
    <?php
    settings_fields('flightaware_map_options');
    do_settings_sections('flightaware-map-settings');
    ?>

    <h2>Customize Area to Track Planes</h2>
    <p>Enter latitude and longitude coordinates for the area you want to track:</p>
    <label for="custom_track_area_lat">Latitude:</label>
    <input type="text" name="flightaware_map_settings[custom_track_area_lat]" id="custom_track_area_lat" value="<?php echo esc_attr($options['custom_track_area_lat'] ?? ''); ?>" />

    <label for="custom_track_area_lng">Longitude:</label>
    <input type="text" name="flightaware_map_settings[custom_track_area_lng]" id="custom_track_area_lng" value="<?php echo esc_attr($options['custom_track_area_lng'] ?? ''); ?>" />

    <?php submit_button(); ?>
</form>