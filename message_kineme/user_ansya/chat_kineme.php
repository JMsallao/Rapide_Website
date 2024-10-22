<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Chat with Admin</h2>

        <!-- Chat messages area -->
        <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: scroll;">
            <?php
            include('../../connection.php');
            session_start();

            // Assuming the user is logged in and the session holds the user ID
            $user_id = $_SESSION['id']; // Logged-in user ID (regular user)

            // Fetch the admin's ID from the users table
            $query_admin = "SELECT id FROM users WHERE is_admin = 1 LIMIT 1"; // Get the admin's ID
            $result_admin = mysqli_query($conn, $query_admin);
            $admin_row = mysqli_fetch_assoc($result_admin);
            $admin_id = $admin_row['id']; // Assuming there's only one admin

            // Fetch chat messages between the logged-in user and the admin
            $query = "SELECT * FROM message WHERE (sender = ? AND recipient = ?) OR (sender = ? AND recipient = ?) ORDER BY created_at";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Display messages
            while ($row = $result->fetch_assoc()) {
                $sender = ($row['sender'] == $user_id) ? "You" : "Admin"; // Display "You" for the user and "Admin" for the admin
                echo "<div><strong>{$sender}:</strong> " . htmlspecialchars($row['message']) . " <small>[" . $row['created_at'] . "]</small></div>";
            }

            $stmt->close();
            ?>
        </div>

        <!-- jQuery (needed for AJAX) 
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            var lastMessageId = 0; // Store the ID of the last received message

            function fetchMessages() {
                $.ajax({
                    url: 'fetch_soler.php', // PHP file to fetch messages for the user
                    method: 'GET',
                    data: {
                        last_message_id: lastMessageId
                    },
                    success: function(response) {
                        var messages = JSON.parse(response);
                        if (messages.length > 0) {
                            messages.forEach(function(message) {
                                $('#chat-box').append('<div><strong>' + message.username + ':</strong> ' + message.message + ' <small>[' + message.created_at + ']</small></div>');
                                lastMessageId = message.id; // Update the last message ID
                            });
                            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Auto-scroll to the bottom
                        }
                    }
                });
            }

            // Poll for new messages every 3 seconds
            setInterval(fetchMessages, 3000);
        </script>
        -->
        <!-- Form to send messages -->
        <form action="send_kineme.php" method="POST" class="mt-3">
            <div class="mb-3">
                <textarea class="form-control" id="message" name="message" maxlength="100" placeholder="Type your message (max 100 characters)" required></textarea>
            </div>
            <!-- The recipient is always the admin -->
            <input type="hidden" name="recipient_id" value="<?php echo $admin_id; ?>">
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
