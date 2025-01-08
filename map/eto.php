<?php
// Database connection for syncing the map markers
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rapide_map_test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch marker locations from the map database
$sql = "SELECT location_id, location, lat, lng FROM map"; 
$result = $conn->query($sql);

$locations = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = [
            'location_id' => $row['location_id'],
            'location' => $row['location'],
            'lat' => $row['lat'],
            'lng' => $row['lng']
        ];
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Request Form</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqV1Tf4ZH_FZ4EWldoeMoiLI_kCwxfR7U&libraries=geometry&callback=initMap" async defer></script>

    <style>
        #map {
            height: 700px;
            width: 700px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            display: none; 
        }

        form {
            width: 60%;
            margin: 20px auto;
        }

        input, select, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            box-sizing: border-box; 
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }

        #otherEmergencyType {
            display: none;
        }
    </style>
</head>
<body>

<h1>Emergency Request Form</h1>

<form id="emergencyForm" method="POST" action="emergency_submit.php">
    <label for="name">Your Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="emergencyType">Type of Emergency:</label>
    <select id="emergencyType" name="emergencyType" required>
        <option value="">Select an emergency type</option>
        <option value="Dead Battery">Dead Battery</option>
        <option value="Engine Failure">Engine Failure</option>
        <option value="Flat Tire">Flat Tire</option>
        <option value="Leakage">Leakage</option>
        <option value="other">Other (Please specify)</option>
    </select>

    <div id="otherEmergencyType" style="display: none;">
        <label for="otherEmergencyDetail">Please specify:</label>
        <input type="text" id="otherEmergencyDetail" name="otherEmergencyDetail">
    </div>

    <label for="carType">Type of Car:</label>
    <input type="text" id="carType" name="carType" required>

    <label for="contact">Contact Number:</label>
    <input type="text" id="contact" name="contact" required>

    <label for="location">Selected Location:</label>
    <input type="text" id="location" name="location" required readonly>

    <!-- Hidden fields to store latitude and longitude of the user -->
    <input type="hidden" id="userLat" name="userLat">
    <input type="hidden" id="userLng" name="userLng">

    <!-- Hidden field to store "Yes" or "No" based on distance -->
    <input type="hidden" id="withinRadius" name="withinRadius" value="No">
    <button type="button" id="findNearestLocationBtn">Find Nearest Location</button>
    <button type="submit">Submit Request</button>
</form>

<!-- Google Map for Directions -->
<div id="map"></div>

<script>

    // Checks if Emergency Type is set to Other.
    document.getElementById('emergencyType').addEventListener('change', function() {
        var emergencyType = document.getElementById('emergencyType').value;
        
        // Show or hide the "otherEmergencyType" field based on the selection
        if (emergencyType === 'other')
        {
            document.getElementById('otherEmergencyType').style.display = 'block';
        } 
        else 
        {
            document.getElementById('otherEmergencyType').style.display = 'none';
        }
    });


    var map;
    var directionsService;
    var directionsRenderer;
    var userMarker;
    var nearestMarker;
    var nearestCircle;  // To store the circle around the nearest marker
    var locationMarkers = [];  // Array to hold the location markers
    var locations = <?php echo json_encode($locations); ?>;  // Converts Marker locations from PHP to JavaScript

    // Haversine formula to calculate distance in kilometers
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371
        const dLat = (lat2 - lat1) * (Math.PI / 180);
        const dLon = (lon2 - lon1) * (Math.PI / 180);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c; 
        return distance;
    }

    // Initialize the map and the Directions services
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 14.403428905167619, lng: 120.86599597337366 },  // Default coordinates
            zoom: 14.5
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);

        userMarker = new google.maps.Marker({
            map: map,
            title: "User Location",
            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'  // Blue marker for user
        });

        nearestMarker = new google.maps.Marker({
            map: map,
            title: "Nearest Location",
            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'  // Red marker for nearest location
        });

        // Place markers for all locations from the database
        locations.forEach(function(location) {
            var locationLatLng = new google.maps.LatLng(location.lat, location.lng);
            var marker = new google.maps.Marker({
                position: locationLatLng,
                map: map,
                title: location.location,
                icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'  // Green marker for locations
            });
            
            // Add marker to the locationMarkers array
            locationMarkers.push(marker);
        });
    }

    // Event listener to find the nearest location based on user's geolocation
    document.getElementById('findNearestLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // Set the origin to the user's location
                const userLocation = new google.maps.LatLng(userLat, userLng);

                // Show the map and center it on the user's location
                document.getElementById('map').style.display = 'block'; // Show the map container
                map.setCenter(userLocation);
                map.setZoom(14);

                // Place a marker at the user's location
                userMarker.setPosition(userLocation);

                // Update the hidden input fields with the user's latitude and longitude
                document.getElementById('userLat').value = userLat;
                document.getElementById('userLng').value = userLng;

                // Initialize variables to track the nearest location
                let nearestLocation = null;
                let shortestDistance = Infinity;

                // Track if all directions have been processed
                let directionsProcessed = 0;

                // Loop through all the locations to find the nearest one based on driving distance
                locations.forEach(function(location) {
                    const destination = new google.maps.LatLng(location.lat, location.lng);

                    // Request directions to each location
                    const request = {
                        origin: userLocation,
                        destination: destination,
                        travelMode: google.maps.TravelMode.DRIVING
                    };

                    // Calculate the driving distance between the user and each location
                    directionsService.route(request, function(result, status) {
                        directionsProcessed++;

                        if (status === google.maps.DirectionsStatus.OK)
                        {
                            // Get the travel distance from the response
                            const routeDistance = result.routes[0].legs[0].distance.value; // in meters
                            const routeDistanceInKm = routeDistance / 1000; // Convert to kilometers

                            // Compare the distance to find the nearest location
                            if (routeDistanceInKm < shortestDistance) 
                            {
                                shortestDistance = routeDistanceInKm;
                                nearestLocation = location;

                                // Update the 'location' field with the nearest location name
                                document.getElementById('location').value = nearestLocation.location; // Set the name of the nearest location

                                // Show directions to the nearest location
                                directionsRenderer.setDirections(result);

                                // Place a marker at the nearest location
                                nearestMarker.setPosition(new google.maps.LatLng(nearestLocation.lat, nearestLocation.lng));
                                map.setCenter(nearestMarker.getPosition());
                                map.setZoom(14);

                                // Remove the previous circle if it exists.
                                if (nearestCircle)
                                {
                                    nearestCircle.setMap(null);
                                }

                                // Draw a circle with a 5km radius around the nearest location
                                nearestCircle = new google.maps.Circle({
                                    map: map,
                                    center: nearestMarker.getPosition(),
                                    radius: 5000, // 5 kilometers
                                    fillColor: '#0000FF',  // Blue color
                                    fillOpacity: 0.2,      // Light opacity for the fill
                                    strokeColor: '#0000FF', // Blue border color
                                    strokeOpacity: 0.6,    // Border opacity
                                    strokeWeight: 2        // Border thickness
                                });

                                // Check if the user is within 5 kilometers. Yes if within, No if not.
                                if (routeDistanceInKm <= 5) 
                                {
                                    document.getElementById('withinRadius').value = "Yes";
                                }
                                else 
                                {
                                    document.getElementById('withinRadius').value = "No";
                                }
                            }
                        }
                        if (directionsProcessed === locations.length) 
                        {
                        }
                    });
                });
            }, function() {
                alert('Geolocation failed or is not supported by this browser.');
            });
        } else 
        {
            alert('Geolocation is not supported by this browser.');
        }
    });
</script>

</body>
</html>
