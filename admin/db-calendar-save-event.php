<?php
require_once "../connection.php"; // Include your database connection file

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $eventDate = $_POST['eventDate'];
    $eventDescription = $_POST['eventDescription'];

    // Prepare SQL statement to insert into 'events' table
    $sql = "INSERT INTO event (eventDate, eventDescription) VALUES (?, ?)";
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $eventDate, $eventDescription);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Success message
            echo json_encode(array("status" => "success", "message" => "Event added successfully"));
        } else {
            // Error message
            echo json_encode(array("status" => "error", "message" => "Failed to add event"));
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to prepare statement"));
    }

    // Close connection
    mysqli_close($conn);
}
?>
