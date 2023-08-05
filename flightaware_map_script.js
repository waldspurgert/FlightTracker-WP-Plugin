// Your JavaScript code for initializing and customizing the Google Map goes here.
// The code here should handle the rendering of the map and the display of airplane markers based on the data from the FlightAware API.

// Function to initialize the Google Map
function initializeMap() {
  const mapOptions = {
    center: { lat: YOUR_INITIAL_LATITUDE, lng: YOUR_INITIAL_LONGITUDE }, // Set your initial map center coordinates here
    zoom: 8, // Set the initial zoom level here
  };

  const map = new google.maps.Map(document.getElementById("flightaware-map"), mapOptions);

  // Fetch flight data from the FlightAware API
  fetchFlightData().then((data) => {
    if (data) {
      // Display airplane markers on the map
      displayAirplaneMarkers(data, map);
    } else {
      alert("Error: Unable to fetch flight data from FlightAware API.");
    }
  });
}

// Function to fetch flight data from the FlightAware API
function fetchFlightData() {
  return new Promise((resolve) => {
    // Use WordPress REST API to get the data from the server
    fetch(YOUR_API_ENDPOINT_URL)
      .then((response) => response.json())
      .then((data) => resolve(data))
      .catch(() => resolve(false));
  });
}

// Function to display airplane markers on the Google Map
function displayAirplaneMarkers(data, map) {
  // Process the data to extract the airplane coordinates
  const airplaneCoordinates = data.map((flight) => ({
    lat: parseFloat(flight.latitude),
    lng: parseFloat(flight.longitude),
  }));

  // Create a marker for each airplane and add it to the map
  airplaneCoordinates.forEach((coordinate) => {
    const marker = new google.maps.Marker({
      position: coordinate,
      map: map,
      // Customize the marker icon as needed
      // icon: 'path/to/marker-icon.png',
    });
  });
}

// Callback function to load the Google Map
function loadMapScript() {
  const googleMapScript = document.createElement("script");
  googleMapScript.src = "https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY";
  googleMapScript.onload = initializeMap;
  document.head.appendChild(googleMapScript);
}

// Load the Google Map script
loadMapScript();
