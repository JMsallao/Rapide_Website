<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map Example</title>

    <!-- Include Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style>
        /* Set the size of the map */
        #map {
            height: 900px;
            width: 50%;
        }
    </style>
</head>
<body>

    <h1>Test Map for Rapide</h1>
    
    <!-- The div where the map will be rendered -->
    <div id="map"></div>

    <!-- Include Leaflet.js library -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        // Initialize the map and set the view to the desired starting latitude and longitude
        var map = L.map('map').setView([14.403428905167619, 120.86599597337366], 14.5);  // [lat, lng], zoom level

        // Add a tile layer to the map (this example uses OpenStreetMap tiles)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add markers for the different locations
        var carmona = L.marker([14.323431497363746, 121.06282953853577]).addTo(map);
        var kawit = L.marker([14.427241007732793, 120.89226668844714]).addTo(map);
        var amadeo = L.marker([14.171149206138656, 120.9242933700748]).addTo(map);

        // Bind popups to the markers (but don't automatically open them)
        carmona.bindPopup("<b>Rapide, Carmona</b><br>Test Marker.");
        kawit.bindPopup("<b>Rapide, Kawit</b><br>Test Marker.");
        amadeo.bindPopup("<b>Rapide, Amadeo</b><br>Test Marker.");

        // Add a 5 km radius circle around each marker
        var circleOptions = {
            color: 'blue',
            fillColor: 'blue',
            fillOpacity: 0.2,
            radius: 5000  // 5 kilometers
        };

        var carmonaCircle = L.circle([14.323431497363746, 121.06282953853577], circleOptions).addTo(map);
        var kawitCircle = L.circle([14.427241007732793, 120.89226668844714], circleOptions).addTo(map);
        var amadeoCircle = L.circle([14.171149206138656, 120.9242933700748], circleOptions).addTo(map);
    </script>

</body>
</html>
