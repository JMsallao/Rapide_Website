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
</head>

<body>
    <div class="container mt-5">
        <h2>Users You Have Chatted With</h2>
        <ul class="list-group">
            <?php
            // Display users who have chatted with the admin
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="list-group-item">';
                    echo '<a href="eto_again.php?user_id=' . $row['id'] . '">' . htmlspecialchars($row['username']) . '</a>';
                    echo '</li>';
                }
            } else {
                echo '<li class="list-group-item">No users to display.</li>';
            }
            ?>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>