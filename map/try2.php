<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Location on Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 100vh; width: 100%; }
    </style>
</head>
<body>
    <h2>Your Real-Time Location on Map</h2>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize the map at a default location
        const map = L.map('map').setView([0, 0], 2);

        // Add a tile layer to the map (MapTiler or OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Check if the browser supports geolocation
        if (navigator.geolocation) {
            // Fetch the user's location
            navigator.geolocation.getCurrentPosition(
                position => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Update map view to user's location
                    map.setView([lat, lon], 15);

                    // Add a marker at the user's location
                    L.marker([lat, lon]).addTo(map)
                        .bindPopup("You are here")
                        .openPopup();
                },
                error => {
                    console.error("Error fetching location:", error);
                }
            );
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    </script>
</body>
</html>
