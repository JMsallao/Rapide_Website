<?php
session_start();
require_once "../connection.php";

// Check if account_id is provided via GET and is numeric
if (isset($_GET['account_id']) && is_numeric($_GET['account_id'])) {
    $account_id = $_GET['account_id'];

    // Prepare SQL statement to update is_deleted flag
    $sql_delete = "UPDATE account SET is_deleted = 1 WHERE account_id = $account_id";
    if ($conn->query($sql_delete) === TRUE) {
        // Redirect back to users management page after deletion
        header("Location: db-users-manage.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
