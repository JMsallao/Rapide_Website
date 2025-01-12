<?php
session_start();
include('../connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs to prevent SQL injection
    $user_id = $_POST['user_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $emergencyType = $_POST['emergencyType'] ?? '';
    $carType = $_POST['carType'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $location = $_POST['location'] ?? '';
    $userLat = $_POST['userLat'] ?? null;
    $userLng = $_POST['userLng'] ?? null;
    $withinRadius = $_POST['withinRadius'] ?? 'No';
    $branch_id = $_POST['branch_id'] ?? null; // Capture branch_id

    // If "Other" is selected, replace emergencyType with the user-provided detail
    if ($emergencyType === 'other') {
        $emergencyType = $_POST['otherEmergencyDetail'] ?? 'Unknown';
    }

    // Validate required fields
    if (empty($name) || empty($emergencyType) || empty($carType) || empty($contact) || empty($location) || empty($branch_id)) {
        echo "<script>alert('All fields are required. Please complete the form.');</script>";
        echo '<script>window.location.href = "emergency_form.php";</script>';
        exit;
    }

    try {
        // Prepare SQL query to insert form data into the database
        $stmt = $conn->prepare("
            INSERT INTO emergencies 
            (user_id, name, emergency_type, car_type, contact, location, userLat, userLng, withinRadius, branch_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("issssssssi", $user_id, $name, $emergencyType, $carType, $contact, $location, $userLat, $userLng, $withinRadius, $branch_id);

        // Execute query
        if ($stmt->execute()) {
            echo "<script>alert('Request submitted successfully!');</script>";
            echo '<script>window.location.href = "../users/Homepage.php";</script>';
        } else {
            echo "<script>alert('Error submitting request. Please try again later.');</script>";
            echo '<script>window.location.href = "emergency_form.php";</script>';
        }

        // Close the statement
        $stmt->close();
    } catch (Exception $e) {
        // Handle exceptions
        error_log("Error submitting emergency request: " . $e->getMessage());
        echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
        echo '<script>window.location.href = "emergency_form.php";</script>';
    }
}

// Close the database connection
$conn->close();
?>
