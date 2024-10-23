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
    <style>
    /* General body styling */
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    /* Chat container styling */
    .container {
        max-width: 600px;
        margin: 80px auto;
        background-color: #fff;
        padding: 30px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    /* Title styling */
    h2 {
        font-size: 28px;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Chat box area */
    #chat-box {
        border: 1px solid #ccc;
        padding: 10px;
        height: 300px;
        overflow-y: auto;
        background-color: #f9f9f9;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* Chat messages */
    #chat-box div {
        margin-bottom: 10px;
        font-size: 14px;
    }

    /* Sender name styling */
    #chat-box strong {
        color: #007bff;
    }

    /* Message content styling */
    #chat-box small {
        color: #999;
        font-size: 12px;
    }

    /* Message form styling */
    textarea.form-control {
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        width: 100%;
    }

    /* Send button styling */
    .btn-primary {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        border: none;
        padding: 10px;
        width: 100%;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .container {
            max-width: 100%;
            margin: 40px 15px;
            padding: 20px;
        }

        h2 {
            font-size: 24px;
        }

        #chat-box {
            height: 250px;
        }

        textarea.form-control {
            font-size: 14px;
            padding: 8px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        h2 {
            font-size: 22px;
        }

        textarea.form-control {
            font-size: 14px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 20px;
        }

        .container {
            padding: 15px;
            margin: 30px 10px;
        }

        #chat-box {
            height: 200px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }

    @media (max-width: 360px) {
        .container {
            padding: 10px;
        }

        h2 {
            font-size: 18px;
        }

        #chat-box {
            height: 180px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }
    </style>
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
                <textarea class="form-control" id="message" name="message" maxlength="100"
                    placeholder="Type your message (max 100 characters)" required></textarea>
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
                        var sender = message.sender == <?php echo $admin_id; ?> ? 'You (Admin)' :
                            '<?php echo $username; ?>';
                        $('#chat-box').append('<div><strong>' + sender + ':</strong> ' + message
                            .message + ' <small>[' + message.created_at + ']</small></div>');
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
                        var now = new Date().toISOString().slice(0, 19).replace('T',
                            ' '); // Current timestamp
                        $('#chat-box').append('<div><strong>' + sender + ':</strong> ' + message +
                            ' <small>[' + now + ']</small></div>');
                        $('#chat-box').scrollTop($('#chat-box')[0]
                            .scrollHeight); // Auto-scroll to the bottom
                        $('#message').val(''); // Clear the input after sending
                    }
                }
            });
        }
    });
    </script>
</body>

</html>