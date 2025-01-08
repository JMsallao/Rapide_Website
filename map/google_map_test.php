<?php
    // Database connection and checking
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rapide_map_test";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch locations from the database
    $sql = "SELECT location, lat, lng FROM map"; 
    $result = $conn->query($sql);

    $locations = [];

    if ($result->num_rows > 0)
    {
        // Output data of each row
        while($row = $result->fetch_assoc())
        {
            $locations[] = [
                'location' => $row['location'],
                'lat' => $row['lat'],
                'lng' => $row['lng']
            ];
        }
    }
    else 
    {
        echo "0 results";
    }
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Google Map Example</title>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqV1Tf4ZH_FZ4EWldoeMoiLI_kCwxfR7U&callback=initMap" async defer></script>
        <style>
            /* Set the size of the map */
            #map 
            {
                height: 900px;
                width: 75%;
                float: left;
            }

            /* Style for the form container */
            #form-container 
            {
                width: 20%;
                float: left;
                padding: 20px;
                background-color: #f4f4f4;
                box-sizing: border-box;
                margin-left: 10px;
                margin-right: 10px;
            }

            #form-container input,
            #form-container button 
            {
                width: 90%;
                margin: 10px;
                padding: 8px;
            }

            h2 
            {
                text-align: center;
            }
        </style>
    </head>
    <body>

        <h1>Admin Map Form for Rapide</h1>
        
        <!-- The div where the map will be rendered -->
        <div id="map"></div>

        <div id="form-container">
            <h2>Add a New Franchise Location</h2>
            <form id="location-form" action="save_location.php" method="POST">
                <label for="name">Location Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter Franchise Location (e.g. : Rapide, Habay I)" required>

                <label for="lat">Latitude:</label>
                <input type="text" id="lat" name="lat" placeholder="Enter Latitude" required>

                <label for="lng">Longitude:</label>
                <input type="text" id="lng" name="lng" placeholder="Enter Longitude" required>

                <button type="submit">Add Location</button>
            </form>
        </div>

        <script>
            var map;
            var markers = [];
            var circles = [];

        // Initialize the map
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 14.403428905167619, lng: 120.86599597337366 },  // Default center coordinates
            zoom: 14.5
        });

        // Get the locations from PHP (embedded as a JavaScript variable)
        var locations = <?php echo json_encode($locations); ?>;
        // Debugging: check if locations are passed properly
        console.log("Locations fetched from PHP:", locations);
        // Loop through locations and add markers and circles with fixed radius
        locations.forEach(function(location) {
            // Ensure lat and lng are valid numbers
            if (!isNaN(location.lat) && !isNaN(location.lng)) 
            {
                console.log("Adding marker for:", location.location); // Debug log
                addMarker(parseFloat(location.lat), parseFloat(location.lng), location.location, 5000);  // Fixed 5000 meters radius
            } 
            else 
            {
                console.error("Invalid location data:", location);
            }
        });
    }

        // Add a marker and circle on the map
        function addMarker(lat, lng, name, radius) {

        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: name
        });

        var circle = new google.maps.Circle({
            strokeColor: '#0000FF',
            strokeOpacity: 0.5,
            strokeWeight: 1,
            fillColor: '#0000FF',
            fillOpacity: 0.2,
            map: map,
            radius: radius 
        });
        circle.setCenter({ lat: lat, lng: lng });

        var infowindow = new google.maps.InfoWindow({
            content: `<b>${name}</b><br>Location Waypoint.`
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

        markers.push(marker);
        circles.push(circle);
    }
        </script>

</body>
</html>
