<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleeeeee.css">
</head>

<body>
    <div class="container">
        <?php
            include('../../sessioncheck.php');
            include('../../connection.php');

            // Check if branch_id is set in GET parameter
            if (!isset($_GET['branch_id'])) {
                die("No branch selected.");
            }

            $branch_id = $_GET['branch_id'];
            $user_id = $_SESSION['id'];
            $_SESSION['recipient_id'] = $branch_id; // Store branch ID in session for later use

            // Fetch branch details (name and profile picture)
            $query = "SELECT fname, lname, pic FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $branch_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $branch = $result->fetch_assoc();

            if (!$branch) {
                die("Branch not found.");
            }

            $branch_name = $branch['fname'] . ' ' . $branch['lname'];
            $branch_pic = !empty($branch['pic']) ? '../../' . htmlspecialchars($branch['pic'], ENT_QUOTES, 'UTF-8') : '../../images/profile-user.png';

            // Handle message sending
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
                    $message = trim($_POST['message']);
                    $sendQuery = "INSERT INTO message (sender, recipient, message, status, created_at, updated_at) 
                                VALUES (?, ?, ?, 'sent', NOW(), NOW())";
                    $sendStmt = $conn->prepare($sendQuery);
                    $sendStmt->bind_param("iis", $user_id, $branch_id, $message);
                    if ($sendStmt->execute()) {
                        echo "<script>console.log('Message sent successfully.');</script>";
                    } else {
                        echo "<script>console.error('Failed to send message.');</script>";
                    }
                    $sendStmt->close();
                }
            }

        ?>
        <div class="d-flex align-items-center p-3" style="width: 100%;">
            <!-- Back Button -->
            <button class="btn btn-light me-3" onclick="history.back()">
                <div class="d-flex justify-content-center align-items-center" style="height: 1rem; width: 1rem;">
                    <img src="../../images/arrow.png" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </button>

            <!-- Admin Info -->
            <div class="d-flex align-items-center">
                <img src="<?php echo htmlspecialchars($branch_pic, ENT_QUOTES, 'UTF-8'); ?>" 
                     alt="Profile Picture" 
                     class="rounded-circle me-2" 
                     style="width: 40px; height: 40px; object-fit: cover;">
                <div>
                    <h6 class="mb-0"><?php echo htmlspecialchars($branch_name, ENT_QUOTES, 'UTF-8'); ?></h6>
                    <span class="text-muted small">Active Now</span>
                </div>
            </div>
        </div>

        <!-- Chat messages area -->
        <div id="chat-box" class="chat-box">
            <?php
            // Update any "Sent" messages to "Delivered" for the current conversation
            $updateDeliveredQuery = "UPDATE message SET status = 'Delivered' WHERE sender = ? AND recipient = ? AND status = 'Sent'";
            $stmtDelivered = $conn->prepare($updateDeliveredQuery);
            $stmtDelivered->bind_param("ii", $branch_id, $user_id);
            $stmtDelivered->execute();
            $stmtDelivered->close();

            // Fetch messages for the selected branch
            $queryMessages = "SELECT * FROM message WHERE 
                              (sender = ? AND recipient = ?) OR 
                              (sender = ? AND recipient = ?) 
                              ORDER BY created_at";
            $stmtMessages = $conn->prepare($queryMessages);
            $stmtMessages->bind_param("iiii", $user_id, $branch_id, $branch_id, $user_id);
            $stmtMessages->execute();
            $resultMessages = $stmtMessages->get_result();

            // Display messages
            while ($row = $resultMessages->fetch_assoc()) {
                $isUser = $row['sender'] == $user_id;
                $sender = $isUser ? "user" : "admin";
                $timestamp = date("H:i", strtotime($row['created_at']));
                $status = $row['status']; // Fetch the status of the message
            ?>
            <div class="chat-message <?= $sender ?>" onclick="toggleTimestamp(this)">
                <div class="message-content <?= htmlspecialchars($sender, ENT_QUOTES, 'UTF-8') ?>">
                    <p class="m-0 text-wrap"><?= htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8') ?></p>
                    <span class="chat-timestamp" style="display: none;">
                        <?= htmlspecialchars($timestamp, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <!-- Displaying message status -->
                    <small class="chat-status text-muted" style="font-size: 0.75rem;">
                         <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>
                    </small>
                </div>
            </div>
            <?php
            }

            $stmtMessages->close();
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
    // Function to toggle timestamp visibility
    function toggleTimestamp(element) {
        const timestamp = element.querySelector('.chat-timestamp');
        if (timestamp) {
            timestamp.style.display = timestamp.style.display === 'none' ? 'block' : 'none';
        }
    }

    // Automatically scroll to the bottom of the chat box on load
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
