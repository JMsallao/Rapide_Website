<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Location</title>
</head>
<body>
    <h2>Your Real-Time Location</h2>
    <p id="location-status">Fetching location...</p>
    <p>Latitude: <span id="latitude"></span></p>
    <p>Longitude: <span id="longitude"></span></p>

    <script>
        // Check if the browser supports geolocation
        if (navigator.geolocation) {
            // Fetch the user's location
            navigator.geolocation.getCurrentPosition(
                position => {
                    // Get latitude and longitude
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Display location on the page
                    document.getElementById('location-status').textContent = "Location found!";
                    document.getElementById('latitude').textContent = lat;
                    document.getElementById('longitude').textContent = lon;
                },
                error => {
                    document.getElementById('location-status').textContent = "Unable to retrieve location.";
                    console.error("Error fetching location:", error);
                }
            );
        } else {
            document.getElementById('location-status').textContent = "Geolocation is not supported by this browser.";
        }
    </script>
</body>
</html>
