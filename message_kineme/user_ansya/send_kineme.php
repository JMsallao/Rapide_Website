<?php
session_start();
include('../../connection.php');

// Check if the user is logged in and message is set
if (isset($_POST['message']) && isset($_SESSION['id'])) {
    $sender_id = $_SESSION['id']; // Logged-in user ID (user sending the message)
    $recipient_id = $_POST['recipient_id']; // The recipient (admin)
    $message = trim($_POST['message']);

    // Validate message length (should not exceed 100 characters)
    if (strlen($message) > 100) {
        echo '<script>alert("Message cannot exceed 100 characters."); window.history.back();</script>';
        exit();
    }

    // Insert the message into the database
    $query = "INSERT INTO message (sender, recipient, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $sender_id, $recipient_id, $message);

    if ($stmt->execute()) {
        // Redirect back to the chat page after sending
        header("Location: chat_kineme.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo '<script>alert("Failed to send message. Please try again."); window.history.back();</script>';
}
?>
