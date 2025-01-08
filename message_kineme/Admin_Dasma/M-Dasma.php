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
            <!-- Back Button -->
            <button class="back-button" onclick="history.back()">
                <img src="../../images/arrow.png" alt="Back">
            </button>

            <!-- Admin Info -->
            <div class="admin-info">
                <img src="../../images/profile-user.png" alt="Admin Profile Picture">
                <div class="details">
                    <h4>Chat with <?php echo htmlspecialchars($username); ?></h4>
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
                <img src="../../images\send.png" alt="Send">
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