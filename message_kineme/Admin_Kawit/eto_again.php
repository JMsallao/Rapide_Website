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
    /* Reuse the user chat page styles */
    body {
        background-color: #f0f2f5;
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 600px;
        margin: auto;
        padding-top: 20px;
    }

    .chat-box {
        height: 400px;
        overflow-y: auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 15px;
        position: relative;
    }

    .chat-message {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .chat-message.user {
        justify-content: flex-start;
    }

    .chat-message.admin {
        justify-content: flex-end;
    }

    .profile-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #cccccc;
    }


    .profile-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 20px;
        font-size: 0.95rem;
        position: relative;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .message-content.user {
        background-color: #e4e6eb;
        color: #333;
        border-radius: 20px 20px 20px 0;
        align-items: flex-start;
    }

    .message-content.admin {
        background-color: #0084ff;
        color: white;
        border-radius: 20px 20px 0 20px;
        align-items: flex-end;
    }

    .chat-timestamp {
        font-size: 0.75rem;
        color: #666;
        margin-top: 5px;
    }

    .chat-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        background-color: #ffffff;
        border-top: 1px solid #ddd;
        position: sticky;
        bottom: 0;
        width: 100%;
        border-radius: 0 0 10px 10px;
    }

    .chat-footer input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 25px;
        outline: none;
        font-size: 0.9rem;
    }

    .chat-footer button {
        background-color: transparent;
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        padding: 0;
    }

    .chat-footer button img {
        width: 20px;
        height: 20px;
        object-fit: cover;
    }

    .chat-footer button:hover {
        background-color: white;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center text-primary mb-4">Chat with <?php echo htmlspecialchars($username); ?></h2>

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
                $timestamp = date("H:i", strtotime($row['created_at']));

                echo "<div class='chat-message {$sender}'>";
                
                // Profile icon
                if ($sender === "user") {
                    echo "<div class='profile-icon'><img src='../../images/client1.jpg' alt='User'></div>";
                }

                // Message content
                echo "<div class='message-content {$sender}'>";
                echo "<div>" . htmlspecialchars($row['message']) . "</div>";
                echo "<span class='chat-timestamp'>{$timestamp}</span>";
                echo "</div>";

                if ($sender === "admin") {
                    echo "<div class='profile-icon'><img src='../../images/client2.jpg' alt='Admin'></div>";
                }

                echo "</div>";
            }

            $stmt->close();
            ?>
        </div>

        <!-- Form to send messages -->
        <form id="chat-form" class="chat-footer">
            <input type="text" id="message" name="message" maxlength="100" placeholder="Write a message..." required />
            <button type="submit">
                <img src="../../images/send-message.png" alt="Send">
            </button>
        </form>
    </div>

    <script>
    // Scroll to the bottom of the chat box on load
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;

    // Handle form submission with AJAX
    document.getElementById("chat-form").addEventListener("submit", function(e) {
        e.preventDefault();

        const message = document.getElementById("message").value;

        // Use AJAX to send the message
        fetch("send_maggi.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `message=${encodeURIComponent(message)}&recipient_id=<?php echo $user_id; ?>`
            })
            .then(response => response.text())
            .then(data => {
                const newMessageDiv = document.createElement("div");
                newMessageDiv.classList.add("chat-message", "admin");

                newMessageDiv.innerHTML = `
                    <div class="message-content admin">
                        <div>${message}</div>
                        <span class="chat-timestamp">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                    </div>
                    <div class="profile-icon"><img src="../../images/admin.jpg" alt="Admin"></div>
                `;

                chatBox.appendChild(newMessageDiv);
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the latest message

                document.getElementById("message").value = ""; // Clear input
            })
            .catch(error => console.error("Error:", error));
    });
    </script>
</body>

</html>