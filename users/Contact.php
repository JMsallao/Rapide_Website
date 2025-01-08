<?php
include('../sessioncheck.php');
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int) $_POST['booking_id'];
    $user_id = (int) $_POST['user_id'];
    $stars = (int) $_POST['stars'];
    $comment = trim($_POST['comment']);

    // Validate rating
    if ($stars < 1 || $stars > 5) {
        echo "Invalid rating value.";
        exit();
    }

    // Check if rating already exists
    $check_query = "SELECT * FROM ratings WHERE booking_id = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("i", $booking_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo "Rating already submitted for this booking.";
        exit();
    }

    // Insert the new rating
    $insert_query = "INSERT INTO ratings (booking_id, stars, comment, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iis", $booking_id, $stars, $comment);
    if ($stmt->execute()) {
        // Redirect to booking history with success message
        header("Location: act.php?rating_success=1");
        exit();
    } else {
        echo "Failed to submit your rating. Please try again.";
    }
}
?>
