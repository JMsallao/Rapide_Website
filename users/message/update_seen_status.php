<?php
include('../../connection.php');
session_start();

$user_id = $_POST['user_id'];
$admin_id = $_POST['admin_id'];

// Update message status to "Seen" for messages sent by the admin to the user
$query = "UPDATE message SET status = 'Seen' WHERE sender = ? AND recipient = ? AND status = 'Delivered'";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $admin_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Messages marked as Seen";
} else {
    echo "No new messages to update";
}
$stmt->close();
$conn->close();
?>