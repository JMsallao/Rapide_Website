<?php
include('../../sessioncheck.php');
include('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    $new_status = $_POST['status'];
    $new_date = $_POST['new_booking_date'] ?? null; // Optional, for pending bookings only

    // Prepare the base SQL query to update the booking status
    $query = "UPDATE bookings SET status = ?";
    $params = [$new_status, $booking_id];

    // If status is pending and a new date is provided, update the booking date
    if ($new_status == 'pending' && !empty($new_date)) {
        $query .= ", booking_date = ?";
        $params = [$new_status, $new_date, $booking_id];
    }

    $query .= " WHERE booking_id = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($query)) {
        if ($new_status == 'pending' && !empty($new_date)) {
            $stmt->bind_param("ssi", $new_status, $new_date, $booking_id);
        } else {
            $stmt->bind_param("si", $new_status, $booking_id);
        }

        if ($stmt->execute()) {
            if ($new_status == 'confirmed') {
                // Check if a checklist entry exists; if not, create it
                $checklist_query = "INSERT IGNORE INTO checklist (booking_id) VALUES (?)";
                $checklist_stmt = $conn->prepare($checklist_query);
                $checklist_stmt->bind_param("i", $booking_id);
                $checklist_stmt->execute();
                $checklist_stmt->close();
            }

            // Redirect with a success message
            header("Location: bukingdets.php?booking_id=$booking_id&status=updated");
            exit();
        } else {
            echo "Error updating booking: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>