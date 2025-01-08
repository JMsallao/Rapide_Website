<?php
// Database connection
$servername = "localhost"; // Your database server, e.g., 'localhost'
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "rapide_map_test"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $lat = $lng = "";

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Get the submitted form data
    $name = $_POST['name'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $location_check_sql = "SELECT * FROM map WHERE location='$name'";
    $location_check_result = $conn->query($location_check_sql);

    if ($location_check_result->num_rows > 0)
    {
        echo '<script>alert("Location already exists.");</script>';
        echo '<script>window.location.href = "google_map_test.php";</script>';
    }
    
    else
    {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO map (location, lat, lng) VALUES (?, ?, ?)");
        $stmt->bind_param("sdd", $name, $lat, $lng); // "s" for string, "d" for double

        // Execute the query
        if ($stmt->execute()) 
        {
            echo '<script>alert("Franchise Location Successfull Added.");</script>';
            echo '<script>window.location.href = "google_map_test.php";</script>';
        }

        else
        {
            echo "Error: " . $stmt->error;
        }
        // Close the statement and connection
        $stmt->close();
    }
}

$conn->close();
?>