# FlightAware Map Plugin

A custom WordPress plugin to integrate the FlightAware API and plot airplanes on a Google Map or display them in a table format.

## Installation

1. Download the plugin ZIP file from the GitHub repository.
2. Go to your WordPress admin dashboard.
3. Navigate to Plugins > Add New > Upload Plugin.
4. Choose the ZIP file you downloaded and click Install Now.
5. Activate the plugin from the Plugins page.

## Configuration

1. After activating the plugin, go to Settings > FlightAware Map Settings to configure the plugin.
2. Enter your FlightAware API Key and Google Maps API Key in the provided fields.
3. Optionally, you can customize the area to track planes by entering the latitude and longitude coordinates.
4. Enable the "Show Planes in Table Format" option to display the flight data in a table format.

## Shortcodes

- Use `[flightaware_map]` shortcode to display the airplanes on a Google Map. The map will be centered at the specified latitude and longitude coordinates.
- Use `[flightaware_map format="table"]` shortcode to display the airplanes in a table format. The table will contain Flight ID, Latitude, Longitude, and Altitude of each plane.

## JavaScript Customization

To customize the JavaScript behavior in the `flightaware_map_script.js` file, update the following variables:

- `YOUR_FLIGHTAWARE_API_KEY`: Replace with your FlightAware API Key fetched from the plugin settings.
- `YOUR_CENTER_LATITUDE`: Replace with the latitude coordinate for centering the map.
- `YOUR_CENTER_LONGITUDE`: Replace with the longitude coordinate for centering the map.
- `YOUR_SHOW_TABLE_FORMAT`: Replace with the value of the "Show Planes in Table Format" option from the plugin settings.

Please ensure that the data from the settings page is correctly updated in these JavaScript variables before the script is executed. This way, you can use the data supplied on the settings page to customize the behavior of the script accordingly.

## Deactivation

Deactivating the plugin will disable the flight tracking functionality and remove all the shortcodes from your WordPress site.

## Troubleshooting

If you encounter any issues with the plugin, please check the following:

1. Make sure you have entered valid API keys for both FlightAware and Google Maps in the plugin settings.
2. Verify that the coordinates for the custom area to track planes are correct.
3. Check for any JavaScript errors in the browser console.

For any additional assistance or questions, feel free to reach out to the plugin author: Trevor Waldspurger.

## License

This plugin is released under the [MIT License](https://opensource.org/licenses/MIT).
