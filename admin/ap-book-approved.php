<?php
session_start();
require_once "../connection.php";

// Ensure the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    echo "<script>alert('Access denied!'); window.location.href = '../home/login-form.php';</script>";
    exit();
}

// Check if form data is submitted
if (isset($_POST['book_id']) && isset($_POST['action'])) {
    $booking_id = $_POST['book_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    } else {
        echo "<script>alert('Invalid action!'); window.history.back();</script>";
        exit();
    }

    // Prepare and execute the UPDATE statement to update booking status
    $sql_update = "UPDATE booking SET status = ? WHERE booking_id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param("si", $status, $booking_id);

        if ($stmt_update->execute()) {
            // Update successful, proceed to fetch the account_id from booking
            $sql_select = "SELECT account_id FROM booking WHERE booking_id = ?";
            $stmt_select = $conn->prepare($sql_select);
            if ($stmt_select) {
                $stmt_select->bind_param("i", $booking_id);
                $stmt_select->execute();
                $stmt_select->bind_result($client_account_id);
                $stmt_select->fetch();
                $stmt_select->close();

                // Define notification message
                if ($status == 'approved') {
                    $notification_message = "Your Booking ID $booking_id has been approved.";
                } elseif ($status == 'rejected') {
                    $notification_message = "Your Booking ID $booking_id has been rejected.";
                }

                // Insert into notification table
                $sql_insert = "INSERT INTO notification (message, created_at, booking_id, account_id) VALUES (?, NOW(), ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);

                if ($stmt_insert) {
                    $stmt_insert->bind_param("sii", $notification_message, $booking_id, $client_account_id);

                    if ($stmt_insert->execute()) {
                        // Notification inserted successfully
                        echo "<script>alert('Booking $status successfully!'); window.location.href = 'db-appointment-list.php';</script>";
                    } else {
                        // Handle insert failure
                        error_log("Failed to insert notification: " . $stmt_insert->error);
                        echo "<script>alert('Failed to insert notification!'); window.location.href = 'db-appointment-list.php';</script>";
                    }

                    $stmt_insert->close();
                } else {
                    // Handle prepare statement failure for INSERT
                    error_log("Failed to prepare notification insert statement: " . $conn->error);
                    echo "<script>alert('Database error! Please try again later.'); window.location.href = 'db-appointment-list.php';</script>";
                }
            } else {
                // Handle prepare statement failure for SELECT
                error_log("Failed to prepare select statement: " . $conn->error);
                echo "<script>alert('Database error! Please try again later.'); window.location.href = 'db-appointment-list.php';</script>";
            }
        } else {
            // Handle update statement execution failure
            error_log("Failed to execute update statement: " . $stmt_update->error);
            echo "<script>alert('Failed to update booking status! Please try again.'); window.location.href = 'db-appointment-list.php';</script>";
        }

        $stmt_update->close();
    } else {
        // Handle prepare statement failure for UPDATE
        error_log("Failed to prepare update statement: " . $conn->error);
        echo "<script>alert('Database error! Please try again later.'); window.location.href = 'db-appointment-list.php';</script>";
    }
} else {
    echo "<script>alert('Form data missing!'); window.history.back();</script>";
}

$conn->close();
?>
