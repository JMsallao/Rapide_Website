<?php
include('../connection.php');
include('../sessioncheck.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type'], $_GET['id'])) {
    $type = $_GET['type'];
    $id = (int) $_GET['id'];

    if ($type === 'emergency') {
        // Delete emergency record
        $query = "DELETE FROM emergencies WHERE emergency_ID = ?";
    } elseif ($type === 'booking') {
        // Start transaction for booking and related cart records
        $conn->begin_transaction();

        try {
            // Delete associated cart items
            $cart_query = "DELETE FROM cart WHERE booking_id = ?";
            $cart_stmt = $conn->prepare($cart_query);
            $cart_stmt->bind_param("i", $id);
            $cart_stmt->execute();

            // Delete the booking
            $query = "DELETE FROM bookings WHERE booking_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();
            header('Location: Act.php?success=1');
        } catch (Exception $e) {
            $conn->rollback();
            die("Error deleting booking: " . $e->getMessage());
        }
    } else {
        die("Invalid type.");
    }

    // Execute the deletion for emergencies
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: Act.php?success=1');
    } else {
        die("Error deleting record.");
    }
} else {
    die("Invalid request.");
}
?>
