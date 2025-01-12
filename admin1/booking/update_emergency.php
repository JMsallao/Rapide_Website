<?php
include('../../connection.php');
include('../../sessioncheck.php');

// Ensure the admin is logged in
if (!isset($_SESSION['id'])) {
    die("Admin not logged in.");
}

$admin_id = $_SESSION['id'];

// Check if the logged-in user is an admin
$query = "SELECT is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if ($user['is_admin'] != 1) {
        die("Access denied. You are not authorized to perform this action.");
    }
} else {
    die("User not found.");
}
$stmt->close();

// Validate and process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['emergency_ID']) || !is_numeric($_POST['emergency_ID'])) {
        die("Invalid Emergency ID.");
    }

    $emergency_id = $_POST['emergency_ID'];
    $new_status = $_POST['status'];

    // Validate the new status
    $allowed_statuses = ['pending', 'confirmed', 'rejected'];
    if (!in_array($new_status, $allowed_statuses)) {
        die("Invalid status value.");
    }

    // Update the emergency status in the database
    $update_query = "UPDATE emergencies SET status = ? WHERE emergency_ID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $emergency_id);

    if ($stmt->execute()) {
        // If the status is confirmed, transfer details to the bookings table
        if ($new_status === 'confirmed') {
            // Transfer emergency details to bookings
            $transfer_query = "
                INSERT INTO bookings (user_id, branch_id, booking_date, status, total_price, created_at, service_type)
                SELECT e.user_id, b.id AS branch_id, NOW(), 'pending', 0, NOW(), 'emergency'
                FROM emergencies e
                LEFT JOIN branches b ON e.location = b.branch_name
                WHERE e.emergency_ID = ?";
            $transfer_stmt = $conn->prepare($transfer_query);
            $transfer_stmt->bind_param("i", $emergency_id);

            if ($transfer_stmt->execute()) {
                // Redirect back with success
                header("Location: emergency_details.php?emergency_ID=" . $emergency_id . "&status=success");
                exit();
            } else {
                echo "Error transferring to bookings: " . $transfer_stmt->error;
            }
            $transfer_stmt->close();
        }
    } else {
        echo "Error updating emergency status: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("Invalid request method.");
}

$conn->close();
?>
