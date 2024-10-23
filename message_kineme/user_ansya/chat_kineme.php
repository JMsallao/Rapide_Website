<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
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

        <!-- Form to send messages -->
        <form action="send_kineme.php" method="POST" class="mt-3">
            <div class="mb-3">
                <textarea class="form-control" id="message" name="message" maxlength="100"
                    placeholder="Type your message (max 100 characters)" required></textarea>
            </div>
            <!-- The recipient is always the admin -->
            <input type="hidden" name="recipient_id" value="<?php echo $admin_id; ?>">
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>