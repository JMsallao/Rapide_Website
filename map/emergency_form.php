<?php
session_start();
include('../connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['id'];

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

   
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .back-button {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px;
            color: black;
            font-size: 1em;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.3s ease;
        }

      

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
            font-size: 2.5em;
            font-weight: 600;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            position: relative;
        }


        label {
            font-size: 1em;
            font-weight: 500;
            color: #444;
            margin-bottom: 5px;
            display: block;
        }
        input, select, button {
            width: 100%;
            padding: 10px 40px; /* Leave space for the icon */
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            box-sizing: border-box;
        }


        input:focus, select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-group i {
            position: absolute;
            top: 70%;
            left: 10px;
            transform: translateY(-50%);
            color: #888;
            font-size: 1.2em;
        }


        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
            border-radius: 10px;
            display: none;
        }

        #otherEmergencyType {
            display: none;
        }

           /* Responsive Design */
           @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 15px;
            }

            h1 {
                font-size: 2em;
            }

            input, select, button {
                font-size: 0.9em;
            }

            .form-group i {
                font-size: 1em;
            }
            .back-button {
                font-size: 0.9em;
            }
        }




    </style>
</head>
<body>



<div class="container">
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>
<h1>Emergency Request Form</h1>
    <form id="emergencyForm" method="POST" action="emergency_submit.php">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <div class="form-group">
            <label for="name">Your Name:</label>
            <i class="fas fa-user"></i>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
            <label for="emergencyType">Type of Emergency:</label>
            <i class="fas fa-exclamation-circle"></i>
            <select id="emergencyType" name="emergencyType" required>
                <option value="">Select an emergency type</option>
                <option value="Dead Battery">Dead Battery</option>
                <option value="Engine Failure">Engine Failure</option>
                <option value="Flat Tire">Flat Tire</option>
                <option value="Leakage">Leakage</option>
                <option value="other">Other (Please specify)</option>
            </select>
        </div>

        <div id="otherEmergencyType" class="form-group">
            <label for="otherEmergencyDetail">Please specify:</label>
            <i class="fas fa-pencil-alt"></i>
            <input type="text" id="otherEmergencyDetail" name="otherEmergencyDetail" placeholder="Specify your emergency">
        </div>

        <div class="form-group">
            <label for="carType">Type of Car:</label>
            <i class="fas fa-car"></i>
            <input type="text" id="carType" name="carType" placeholder="e.g., Sedan, SUV, Truck" required>
        </div>

        <div class="form-group">
            <label for="contact">Contact Number:</label>
            <i class="fas fa-phone-alt"></i>
            <input type="text" id="contact" name="contact" placeholder="Enter your contact number" required>
        </div>

        <div class="form-group">
            <label for="location">Selected Location:</label>
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" id="location" name="location" placeholder="Auto-detected location" required readonly>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" id="userLat" name="userLat">
        <input type="hidden" id="userLng" name="userLng">
        <input type="hidden" id="withinRadius" name="withinRadius" value="No">

        <div id="map"></div>
        
        <button type="button" id="findNearestLocationBtn"><i class="fas fa-map"></i> Find Nearest Location</button>
        <button type="submit"><i class="fas fa-paper-plane"></i> Submit Request</button>
    </form>
</div>



<script>

    // Checks if Emergency Type is set to Other.
    document.getElementById('emergencyType').addEventListener('change', function () {
        const emergencyType = document.getElementById('emergencyType').value;
        const otherField = document.getElementById('otherEmergencyType');
        otherField.style.display = emergencyType === 'other' ? 'block' : 'none';
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
                                    radius: 8000, // 8 kilometers
                                    fillColor: '#0000FF',  // Blue color
                                    fillOpacity: 0.2,      // Light opacity for the fill
                                    strokeColor: '#0000FF', // Blue border color
                                    strokeOpacity: 0.6,    // Border opacity
                                    strokeWeight: 2        // Border thickness
                                });

                                // Check if the user is within 5 kilometers. Yes if within, No if not.
                                if (routeDistanceInKm <= 8) 
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
