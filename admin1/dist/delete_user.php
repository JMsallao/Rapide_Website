<?php

// Include your database connection
include('../../connection.php');
include('../../sessioncheck.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query
    $sql = "DELETE FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: users.php?msg=User deleted successfully");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
} else {
    echo "User ID not provided.";
}
?>
