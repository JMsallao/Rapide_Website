<?php
session_start();
include('../../connection.php');

// Admin ID
$admin_id = $_SESSION['id'];

// Query to fetch distinct users that the admin has chatted with
$query = "SELECT DISTINCT u.id, u.username 
          FROM users u 
          JOIN message m ON u.id = m.sender OR u.id = m.recipient 
          WHERE (m.sender = ? OR m.recipient = ?) AND u.is_admin = 0"; // Exclude admin

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $admin_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f6fa;
        margin: 0;
        padding: 0;
    }

    .container {
        margin-top: 20px;
        max-width: 500px;
    }

    .chat-list {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f1f1;
        transition: background-color 0.2s ease-in-out;
    }

    .chat-item:last-child {
        border-bottom: none;
    }

    .chat-item:hover {
        background-color: #f0f0f5;
        cursor: pointer;
    }

    .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
        border: 2px solid #ccc;
    }

    .chat-details {
        flex: 1;
        overflow: hidden;
    }

    .chat-username {
        font-weight: bold;
        font-size: 16px;
        color: #333;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-preview {
        font-size: 14px;
        color: #888;
        margin: 5px 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-time {
        font-size: 12px;
        color: #999;
    }

    .no-users {
        text-align: center;
        padding: 20px;
        font-size: 16px;
        color: #888;
    }

    .chat-link {
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">Chat Conversations</h2>
        <div class="chat-list">
            <?php
            // Display users who have chatted with the admin
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $userId = $row['id'];
                    $username = htmlspecialchars($row['username']);

                    // Fetch the latest message for this user
                    $latestMessageQuery = "SELECT message, created_at FROM message 
                                           WHERE (sender = ? AND recipient = ?) OR (sender = ? AND recipient = ?) 
                                           ORDER BY created_at DESC LIMIT 1";
                    $stmtMessage = $conn->prepare($latestMessageQuery);
                    $stmtMessage->bind_param("iiii", $admin_id, $userId, $userId, $admin_id);
                    $stmtMessage->execute();
                    $latestMessageResult = $stmtMessage->get_result();
                    $latestMessage = $latestMessageResult->fetch_assoc();

                    $messagePreview = $latestMessage ? htmlspecialchars($latestMessage['message']) : "No messages yet.";
                    $messageTime = $latestMessage ? date("H:i", strtotime($latestMessage['created_at'])) : "";

                    echo "
                    <a href='eto_again.php?user_id={$userId}' class='chat-link'>
                        <div class='chat-item'>
                            <img src='../../images/default-user.png' alt='Profile' class='profile-img'>
                            <div class='chat-details'>
                                <p class='chat-username'>{$username}</p>
                                <p class='chat-preview'>{$messagePreview}</p>
                            </div>
                            <span class='chat-time'>{$messageTime}</span>
                        </div>
                    </a>
                    ";

                    $stmtMessage->close();
                }
            } else {
                echo "<div class='no-users'>No users to display.</div>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>