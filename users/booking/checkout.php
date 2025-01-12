<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Validate and retrieve POST data
if (
    isset($_POST['cart_ids']) && count($_POST['cart_ids']) > 0 &&
    isset($_POST['selected_date']) && isset($_POST['selected_time']) &&
    isset($_POST['branch_id'])
) {
    $selected_cart_ids = $_POST['cart_ids'];
    $selected_date = $_POST['selected_date'];
    $selected_time = $_POST['selected_time'];
    $branch_id = intval($_POST['branch_id']);
    $user_id = $_SESSION['id'];

    // Combine date and time into a single datetime value
    $booking_datetime = $selected_date . ' ' . $selected_time;

    // Calculate the total price of the selected cart items
    $cart_ids_imploded = implode(',', array_map('intval', $selected_cart_ids));
    $query = "SELECT SUM(price * quantity) AS total_price FROM cart WHERE id IN ($cart_ids_imploded) AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_price_data = $result->fetch_assoc();
    $total_price = $total_price_data['total_price'] ?? 0;

    if ($total_price > 0) {
        // Insert a new booking into the `bookings` table
        $status = 'pending';
        $insert_booking_query = "INSERT INTO bookings (user_id, branch_id, booking_date, status, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_booking_query);
        $stmt->bind_param("iissd", $user_id, $branch_id, $booking_datetime, $status, $total_price);
        $stmt->execute();

        // Get the last inserted booking ID
        $booking_id = $stmt->insert_id;

        if ($booking_id) {
            // Update the cart items with the booking ID and mark them as 'booked'
            $update_cart_query = "UPDATE cart SET booking_id = ?, status = 'booked' WHERE id IN ($cart_ids_imploded) AND user_id = ?";
            $stmt = $conn->prepare($update_cart_query);
            $stmt->bind_param("ii", $booking_id, $user_id);
            $stmt->execute();

            // Redirect to confirmation page
            header("Location: confirmation.php?success=1&booking_id=$booking_id");
            exit();
        } else {
            echo "Error: Unable to create booking.";
        }
    } else {
        echo "Error: Invalid cart total. Please try again.";
    }
} else {
    echo "Error: Missing required booking data. Please ensure you selected items, date, time, and branch.";
}
?>
