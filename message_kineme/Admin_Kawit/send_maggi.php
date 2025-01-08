<?php
session_start();
include('../../connection.php');

// Check if the POST request contains the necessary data
if (isset($_POST['message']) && isset($_POST['recipient_id']) && isset($_POST['sender_id'])) {
    // Sanitize the input to prevent SQL injection and XSS
    $message = htmlspecialchars(trim($_POST['message']));
    $recipient_id = (int) $_POST['recipient_id'];
    $sender_id = (int) $_POST['sender_id'];

    // Check if the message is not empty and within the 100-character limit
    if (!empty($message) && strlen($message) <= 100) {
        // Insert the message into the database
        $query = "INSERT INTO message (sender, recipient, message, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $sender_id, $recipient_id, $message); // Bind parameters

        if ($stmt->execute()) {
            // Message successfully inserted
            echo json_encode(['status' => 'success']);
        } else {
            // Error while inserting the message
            echo json_encode(['status' => 'error', 'message' => 'Failed to send the message.']);
        }

        $stmt->close();
    } else {
        // Invalid message length
        echo json_encode(['status' => 'error', 'message' => 'Message is too long or empty.']);
    }
} else {
    // Missing required data
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

// Close the database connection
$conn->close();
?>
