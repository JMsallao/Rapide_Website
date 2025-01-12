<?php
    include('../../connection.php');

    // Fetch all branches with their name, latitude, and longitude from the map table
    $sql = "SELECT branch_name, lat, lng FROM branches";
    $result = $conn->query($sql);

    // Store branch data in an array
    $branches = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $branches[] = $row;
        }
    } else {
        echo "No branches found.";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branches Map</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqV1Tf4ZH_FZ4EWldoeMoiLI_kCwxfR7U&callback=initMap" async defer></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        .btn-dashboard {
            margin: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-dashboard:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <a href="../dist/Admin-Homepage.php" class="btn-dashboard">Back to Dashboard</a>

    <h2>Branches Map</h2>
    <div id="map"></div>

    <script>
        // Initialize the map with Kawit as the center
        function initMap() {
            // Kawit coordinates (for centering the map)
            var kawit = { lat: 14.427241007732793, lng: 120.89226668844714 };

            // Initialize the map centered around Kawit
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12, // Set zoom level to see all branches
                center: kawit
            });

            // Retrieve the branch data from PHP and add markers
            var branches = <?php echo json_encode($branches); ?>;

            branches.forEach(function(branch) {
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(branch.lat), lng: parseFloat(branch.lng) },
                    map: map,
                    title: branch.branch_name
                });

                // Create an info window to show the branch name
                var infowindow = new google.maps.InfoWindow({
                    content: '<h5>' + branch.branch_name + '</h5>'
                });

                // Add a click event to open the info window when the marker is clicked
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            });
        }
    </script>
</body>

</html>
