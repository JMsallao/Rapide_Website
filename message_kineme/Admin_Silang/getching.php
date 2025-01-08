<?php
session_start();
include('../../connection.php');

// Get the last message ID received from the AJAX request
$last_message_id = isset($_GET['last_message_id']) ? intval($_GET['last_message_id']) : 0;

// Get the admin and user IDs from the AJAX request
$admin_id = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($admin_id > 0 && $user_id > 0) {
    // Fetch messages where the ID is greater than the last message ID
    $query = "
        SELECT id, sender, recipient, message, created_at
        FROM message
        WHERE ((sender = ? AND recipient = ?) OR (sender = ? AND recipient = ?))
          AND id > ?
        ORDER BY created_at ASC
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiii", $user_id, $admin_id, $admin_id, $user_id, $last_message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Collect messages into an array
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    // Return messages as JSON
    echo json_encode($messages);

    $stmt->close();
} else {
    echo json_encode([]);
}

$conn->close();
?>
