<?php
session_start();
include('../connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $emergencyType = $_POST['emergencyType'];
    // Change the Emergency type to User Input if "Other" is selected.
    if ($emergencyType == 'other') {
        $emergencyType = $_POST['otherEmergencyDetail']; 
    }
    $carType = $_POST['carType'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];
    $userLat = $_POST['userLat']; 
    $userLng = $_POST['userLng']; 
    $withinRadius = $_POST['withinRadius'];

    // Prepare SQL query to insert form data into the database
    $stmt = $conn->prepare("INSERT INTO emergencies (user_id, name, emergency_type, car_type, contact, location, userLat, userLng, withinRadius) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $user_id, $name, $emergencyType, $carType, $contact, $location, $userLat, $userLng, $withinRadius);

    // Execute query
    if ($stmt->execute()) {
        echo "<script>alert('Request submitted successfully!');</script>";
        echo '<script>window.location.href = "../users/Homepage.php";</script>';
    } else {
        echo "<script>alert('Error submitting request.');</script>";
        echo '<script>window.location.href = "emergency_form.php";</script>';
    }
    $stmt->close();
}
$conn->close();
?>
