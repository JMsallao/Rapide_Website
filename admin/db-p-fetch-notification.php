<?php
session_start();
require_once "../connection.php";

$userId = $_SESSION['account_id'];
$markAsRead = isset($_GET['markAsRead']) ? $_GET['markAsRead'] : false;

// Fetch notifications
$sql = "SELECT notif_id, message, created_at, is_read FROM notification WHERE account_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
$hasNotifications = false;

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
    if (!$row['is_read']) {
        $hasNotifications = true;
    }
}

// Mark notifications as read if requested
if ($markAsRead) {
    $updateSql = "UPDATE notification SET is_read = 1 WHERE account_id = ? AND is_read = 0";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $userId);
    $updateStmt->execute();
}

$response = [
    'hasNotifications' => $hasNotifications,
    'notifications' => $notifications
];

echo json_encode($response);

$stmt->close();
$conn->close();
?>
