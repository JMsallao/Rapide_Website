<?php
session_start();
include('../../connection.php');

// Admin ID from session remains
$admin_id = $_SESSION['id']; // Admin session remains intact

// Get the user ID from the URL parameter
$user_id = $_GET['user_id']; // Selected user ID

// Fetch the admin ID from the database where is_admin = 1 to ensure it's correct
$admin_query = "SELECT id FROM users WHERE is_admin = 1 LIMIT 1";
$admin_result = mysqli_query($conn, $admin_query);
$admin_data = mysqli_fetch_assoc($admin_result);
$admin_id = $admin_data['id']; // Fetch the admin ID again

// Fetch the username of the selected user
$user_query = "SELECT username FROM users WHERE id = ?";
$stmt_user = $conn->prepare($user_query);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();
$username = $user_data['username']; // Get the username of the selected user

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($username); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Chat with <?php echo htmlspecialchars($username); ?></h2>

        <!-- Chat messages area -->
        <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: scroll;">
            <?php
            // Fetch chat messages between the admin and the selected user
            $query = "SELECT * FROM message WHERE (sender = ? AND recipient = ?) OR (sender = ? AND recipient = ?) ORDER BY created_at";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id); // Correctly bind the admin and user IDs
            $stmt->execute();
            $result = $stmt->get_result();

            // Display the messages
            while ($row = $result->fetch_assoc()) {
                $sender = ($row['sender'] == $admin_id) ? "You (Admin)" : htmlspecialchars($username); // Use username for user, "You (Admin)" for admin
                echo "<div><strong>{$sender}:</strong> " . htmlspecialchars($row['message']) . " <small>[" . $row['created_at'] . "]</small></div>";
            }

            $stmt->close();
            ?>
        </div>

        <!-- Form to send messages -->
        <form id="send-message-form" class="mt-3">
            <div class="mb-3">
                <textarea class="form-control" id="message" name="message" maxlength="100" placeholder="Type your message (max 100 characters)" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
            
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var lastMessageId = 0; // Store the ID of the last received message
            
        function fetchMessages() {
            $.ajax({
                url: 'fetch_admin_messages.php', // PHP file to fetch messages
                method: 'GET',
                data: {
                    last_message_id: lastMessageId,
                    user_id: <?php echo $user_id; ?>, // Use user_id from URL
                    admin_id: <?php echo $admin_id; ?> // Use admin_id fetched from the database
                },
                success: function(response) {
                    var messages = JSON.parse(response);
                    if (messages.length > 0) {
                        messages.forEach(function(message) {
                            var sender = message.sender == <?php echo $admin_id; ?> ? 'You (Admin)' : '<?php echo $username; ?>';
                            $('#chat-box').append('<div><strong>' + sender + ':</strong> ' + message.message + ' <small>[' + message.created_at + ']</small></div>');
                            lastMessageId = message.id; // Update the last message ID
                        });
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Auto-scroll to the bottom
                    }
                }
            });
        }

        // Poll for new messages every 3 seconds
        setInterval(fetchMessages, 3000);
        
        // Send message form handling
        $('#send-message-form').submit(function(event) {
            event.preventDefault(); // Prevent form submission

            var message = $('#message').val();
            if (message.trim() !== '') {
                $.ajax({
                    url: 'send_maggi.php', // PHP file for sending messages
                    method: 'POST',
                    data: {
                        message: message,
                        recipient_id: <?php echo $user_id; ?>, // User as recipient
                        sender_id: <?php echo $admin_id; ?> // Admin as sender (from session)
                    },
                    success: function(response) {
                        if (response) {
                            var sender = 'You (Admin)'; // Display message as sent by admin
                            var now = new Date().toISOString().slice(0, 19).replace('T', ' '); // Current timestamp
                            $('#chat-box').append('<div><strong>' + sender + ':</strong> ' + message + ' <small>[' + now + ']</small></div>');
                            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Auto-scroll to the bottom
                            $('#message').val(''); // Clear the input after sending
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
