<?php
session_start();
include('../connection.php');

// Check if user_id is set in the session
if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "message" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['id']; // Retrieve user_id from session

// Get JSON data sent from JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// Check if lat and lon are provided for saving/updating location
if (isset($data['lat']) && isset($data['lon'])) {
    // Round lat and lon as specified
    $lat = round($data['lat'], 8);
    $lon = round($data['lon'], 10);

    // Insert or update the user location in the database
    $query = "INSERT INTO user_loc (user_id, lat, lon, last_update) 
              VALUES ($user_id, '$lat', '$lon', NOW())
              ON DUPLICATE KEY UPDATE 
              lat='$lat', lon='$lon', last_update=NOW()";

    // Execute the query and output the result
    if ($conn->query($query) === TRUE) {
        echo json_encode(["success" => true, "message" => "Location saved or updated"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database Error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}

$conn->close();
