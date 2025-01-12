<?php
    session_start();
    include('../../connection.php');

    // Ensure admin is logged in
    if (!isset($_SESSION['id'])) {
        die("Admin not logged in.");
    }

    $admin_id = $_SESSION['id']; // Get the admin's ID

    // Fetch admin details
    $query = "SELECT fname, lname, pic FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if (!$admin) {
        die("Admin not found.");
    }

    $admin_name = $admin['fname'] . ' ' . $admin['lname'];
    $admin_pic = !empty($admin['pic']) ? '../../' . htmlspecialchars($admin['pic'], ENT_QUOTES, 'UTF-8') : '../../images/profile-user.png';

    $user_id = $_GET['user_id']; // The user ID of the person the admin is chatting with

    // Fetch user details (including the profile picture)
    $query_user = "SELECT * FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }

    $user_name = $user['fname'] . ' ' . $user['lname'];
    $user_pic = !empty($user['pic']) ? '../../users/' . htmlspecialchars($user['pic'], ENT_QUOTES, 'UTF-8') : '../../images/default-user.png'; // Use default if no pic


    // Handle message sending
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
            $message = trim($_POST['message']);
            $sendQuery = "INSERT INTO message (sender, recipient, message, status, created_at, updated_at) 
                        VALUES (?, ?, ?, 'sent', NOW(), NOW())";
            $sendStmt = $conn->prepare($sendQuery);
            $sendStmt->bind_param("iis", $admin_id, $user_id, $message);
            if ($sendStmt->execute()) {
                echo "<script>console.log('Message sent successfully.');</script>";
            } else {
                echo "<script>console.error('Failed to send message.');</script>";
            }
            $sendStmt->close();
        }
    }

    // Update message status to 'Seen' when the admin opens the chat
    $updateStatusQuery = "UPDATE message SET status = 'Seen' WHERE sender = ? AND recipient = ? AND status = 'Delivered'";
    $stmtUpdateStatus = $conn->prepare($updateStatusQuery);
    $stmtUpdateStatus->bind_param("ii", $admin_id, $user_id);
    $stmtUpdateStatus->execute();
    $stmtUpdateStatus->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($username); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .message-status {
        font-size: 0.6rem; /* Make the text smaller */
        color: #888;
        margin-top: 2px;  /* Reduce space between the message and status */
        padding-left: 5px; /* Add a little left padding for spacing */
    }

    /* Chat Header Styles */
    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background-color: #ffffff;
        border-bottom: 2px solid #eaeaea;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .chat-header .back-button {
        background: none;
        border: none;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .chat-header .back-button img {
        width: 20px;
        height: 20px;
        object-fit: cover;
    }

    .chat-header .admin-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .chat-header .admin-info img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .chat-header .admin-info .details {
        display: flex;
        flex-direction: column;
    }

    .chat-header .admin-info .details h4 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
        font-weight: bold;
    }

    .chat-header .admin-info .details span {
        font-size: 0.9rem;
        color: #888;
    }

    /* General styles */
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 600px;
        width: 100%;
        background-color: #ffffff;
        margin: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Chat box */
    .chat-box {
        height: 70vh;
        overflow-y: auto;
        background-color: rgb(255, 255, 255);
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
        scroll-behavior: smooth;
    }

    .chat-message {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 5px;
        word-wrap: break-word;
    }

    .chat-message.user {
        justify-content: flex-start;
    }

    .chat-message.admin {
        justify-content: flex-end;
    }

    .chat-message.admin .profile-icon {
        order: 1;
    }

    .profile-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .message-content {
        word-break: break-word;
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 25px;
        font-size: 0.9rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .message-content.user {
        background-color: #fffd6d;
        color: rgb(0, 0, 0);
        border-radius: 15px 15px 15px 0;
        text-align: start;
    }

    .message-content.admin {
        background-color: #a3a3a3;
        color: #ffffff;
        border-radius: 15px 15px 0 15px;
        text-align: end;
    }

    .chat-timestamp {
        font-size: 0.75rem;
        color: #999;
        margin-top: 5px;
        display: none;
    }

    .chat-message:hover .chat-timestamp {
        display: block;
    }

    /* Footer for input */
    .chat-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background-color: #ffffff;
        border-top: 2px solid #797979;
        position: sticky;
        bottom: 0;
        width: 100%;
    }

    .chat-footer input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 2px solid #8a8a8a;
        border-radius: 25px;
        outline: none;
        font-size: 0.9rem;
    }

    .chat-footer button {
        background-color: #ffffff;
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .chat-footer button img {
        width: 20px;
        height: 20px;
        object-fit: cover;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .container {
            max-width: 100%;
            margin: 0;
            border-radius: 0;
        }

        .chat-box {
            height: 65vh;
            padding: 5px;
            gap: 8px;
        }

        .chat-footer input[type="text"] {
            padding: 8px;
            font-size: 0.85rem;
        }

        .chat-footer button {
            width: 35px;
            height: 35px;
        }

        .profile-icon {
            width: 35px;
            height: 35px;
        }

        .message-content {
            font-size: 0.85rem;
            padding: 8px 10px;
        }

        .chat-timestamp {
            font-size: 0.7rem;
        }
    }

    @media (max-width: 480px) {
        .chat-box {
            height: 75vh;
        }

        .chat-footer input[type="text"] {
            font-size: 0.8rem;
        }

        .message-content {
            font-size: 0.8rem;
        }
    }
</style>
</head>

<body>
    <div class="container">
        <div class="chat-header">
            <button class="back-button" onclick="history.back()">
                <img src="../../images/arrow.png" alt="Back">
            </button>
            <div class="admin-info">
                <img src="<?php echo  $user_pic; ?>" alt="User Profile Picture">
                <div class="details">
                    <h4><?php echo htmlspecialchars($user_name); ?></h4>
                    <span>Active Now</span>
                </div>
            </div>
        </div>

        <!-- Chat messages area -->
        <div id="chat-box" class="chat-box">
            <?php
            // Fetch chat messages between the admin and the selected user
            $query = "SELECT * FROM message WHERE (sender = ? AND recipient = ?) OR (sender = ? AND recipient = ?) ORDER BY created_at";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiii", $admin_id, $user_id, $user_id, $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Display messages
            while ($row = $result->fetch_assoc()) {
                $isAdmin = $row['sender'] == $admin_id;
                $sender = $isAdmin ? "admin" : "user";
                $status = $row['status']; // Only display status on the sender's messages
                $timestamp = date("H:i", strtotime($row['created_at']));

                echo "<div class='chat-message {$sender}'>";
                
                // Message content
                echo "<div class='message-content {$sender}'>";
                echo "<div>" . htmlspecialchars($row['message']) . "</div>";
                echo "<span class='chat-timestamp'>{$timestamp}</span>";
                
                // Show status only for the sender
                if ($sender === "admin") {
                    echo "<div class='message-status'>" . strtoupper($status) . "</div>";
                }
                echo "</div>";

                echo "</div>";
            }

            $stmt->close();
            ?>
        </div>

        <!-- Form to send messages -->
        <form id="chat-form" method="POST" class="chat-footer">
            <input type="text" id="message" name="message" maxlength="100" placeholder="Write a message..." required />
            <button type="submit">
                <img src="../../images/send.png" alt="Send">
            </button>
        </form>
    </div>

    <script>
        // Scroll to the bottom of the chat box on load
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;

        // Automatically handle form submission
        document.getElementById("chat-form").addEventListener("submit", function(e) {
            e.preventDefault();

            const message = document.getElementById("message").value;

            // Submit the message
            this.submit(); // This will submit the form to itself and reload the page
        });
    </script>
</body>
</html>