<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Debugging: Check if datetime and cart IDs are received
if (isset($_POST['datetime'])) {
    echo 'Received datetime: ' . $_POST['datetime'] . '<br>';
} else {
    echo 'Datetime not set.<br>';
}

if (isset($_POST['cart_ids']) && count($_POST['cart_ids']) > 0) {
    echo 'Cart IDs received: ' . implode(',', $_POST['cart_ids']) . '<br>';
} else {
    echo 'No cart IDs received.<br>';
}

if (isset($_POST['branch_id'])) {
    echo 'Branch ID received: ' . $_POST['branch_id'] . '<br>';
} else {
    echo 'Branch ID not set.<br>';
}

// Check for selected cart items, booking datetime, and branch ID
if (
    isset($_POST['cart_ids']) && count($_POST['cart_ids']) > 0 &&
    isset($_POST['datetime']) && isset($_POST['branch_id'])
) {
    $selected_cart_ids = $_POST['cart_ids'];
    $booking_date = $_POST['datetime']; // Booking date and time from Flatpickr
    $branch_id = intval($_POST['branch_id']); // Branch ID
    $user_id = $_SESSION['id'];

    // Step 1: Calculate total price for selected cart items
    $cart_ids_imploded = implode(',', array_map('intval', $selected_cart_ids));
    $query = "SELECT SUM(price * quantity) AS total_price FROM cart WHERE id IN ($cart_ids_imploded) AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_price_data = $result->fetch_assoc();
    $total_price = $total_price_data['total_price'];

    // Step 2: Insert a new booking into the `bookings` table
    $booking_status = 'pending';
    $booking_query = "INSERT INTO bookings (user_id, branch_id, booking_date, status, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($booking_query);
    $stmt->bind_param("iissd", $user_id, $branch_id, $booking_date, $booking_status, $total_price);
    $stmt->execute();
    $booking_id = $stmt->insert_id; // Get the generated booking ID

    if ($booking_id) {
        // Step 3: Update each selected item in the cart with `booking_id` and set `status` to 'booked'
        $update_cart_query = "UPDATE cart SET booking_id = ?, status = 'booked' WHERE id IN ($cart_ids_imploded) AND user_id = ?";
        $stmt = $conn->prepare($update_cart_query);
        $stmt->bind_param("ii", $booking_id, $user_id);
        $stmt->execute();

        // Redirect to confirmation page with success message
        header("Location: ../customerAlwaysRight/bookingKaba.php?success=1&booking_id=$booking_id");
        exit();
    } else {
        echo 'Error: Unable to create booking.';
    }
} else {
    echo 'Error: No items selected for booking, booking date not set, or branch not selected.';
}
?>
