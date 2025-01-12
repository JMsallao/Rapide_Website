<?php
    session_start();
    include('../connection.php');

    // Ensure the user is logged in
    if (!isset($_SESSION['id'])) {
        die("User not logged in.");
    }

    $user_id = $_SESSION['id'];

    // Fetch branch data from the branches table
    $sql = "SELECT id, branch_name, lat, lng FROM branches";
    $result = $conn->query($sql);

    $branches = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $branches[] = [
                'id' => $row['id'],
                'branch_name' => $row['branch_name'],
                'lat' => $row['lat'],
                'lng' => $row['lng']
            ];
        }
    } else {
        echo "0 results";
    }

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
            <input type="hidden" id="branch_id" name="branch_id">

            <div id="map"></div>
            
            <button type="button" id="findNearestLocationBtn"><i class="fas fa-map"></i> Find Nearest Location</button>
            <button type="submit"><i class="fas fa-paper-plane"></i> Submit Request</button>
        </form>
    </div>

    <script>
        var map;
        var directionsService;
        var directionsRenderer;
        var userMarker;
        var nearestMarker;
        var nearestCircle; // For radius display
        var locations = <?php echo json_encode($branches); ?>;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 14.403428905167619, lng: 120.86599597337366 },
                zoom: 14.5
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            userMarker = new google.maps.Marker({
                map: map,
                title: "Your Location",
                icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            });

            nearestMarker = new google.maps.Marker({
                map: map,
                title: "Nearest Branch",
                icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
            });
        }

        document.getElementById('findNearestLocationBtn').addEventListener('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    const userLocation = new google.maps.LatLng(userLat, userLng);

                    document.getElementById('userLat').value = userLat;
                    document.getElementById('userLng').value = userLng;

                    userMarker.setPosition(userLocation);
                    map.setCenter(userLocation);
                    map.setZoom(14);

                    let nearestBranch = null;
                    let shortestDistance = Infinity;

                    locations.forEach(branch => {
                        const branchLocation = new google.maps.LatLng(branch.lat, branch.lng);
                        const distance = google.maps.geometry.spherical.computeDistanceBetween(userLocation, branchLocation);

                        if (distance < shortestDistance) {
                            shortestDistance = distance;
                            nearestBranch = branch;
                        }
                    });

                    if (nearestBranch) {
                        const branchLatLng = new google.maps.LatLng(nearestBranch.lat, nearestBranch.lng);

                        // Update form fields
                        document.getElementById('location').value = nearestBranch.branch_name;
                        document.getElementById('branch_id').value = nearestBranch.id;

                        // Place nearest branch marker
                        nearestMarker.setPosition(branchLatLng);
                        map.setCenter(branchLatLng);
                        map.setZoom(14);

                        // Add or update radius circle
                        if (nearestCircle) {
                            nearestCircle.setMap(null); // Remove the previous circle
                        }

                        nearestCircle = new google.maps.Circle({
                            map: map,
                            center: branchLatLng,
                            radius: 8000, // 8 kilometers
                            fillColor: '#0000FF',
                            fillOpacity: 0.2,
                            strokeColor: '#0000FF',
                            strokeOpacity: 0.6,
                            strokeWeight: 2
                        });

                        // Render directions
                        const directionsRequest = {
                            origin: userLocation,
                            destination: branchLatLng,
                            travelMode: google.maps.TravelMode.DRIVING
                        };

                        directionsService.route(directionsRequest, function (result, status) {
                            if (status === google.maps.DirectionsStatus.OK) {
                                directionsRenderer.setDirections(result);
                            } else {
                                alert('Could not fetch directions. Please try again.');
                            }
                        });
                    }
                }, function () {
                    alert('Geolocation failed or is not supported by your browser.');
                });
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        });
    </script>


</body>
</html>
