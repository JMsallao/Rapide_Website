<?php
include('../../sessioncheck.php');
include('../../connection.php');

if (isset($_GET['id']) && isset($_GET['table'])) {
    $service_id = (int) $_GET['id'];
    $table_name = $_GET['table'];

    // Prepare delete query
    $query = "DELETE FROM $table_name WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: ../adminMoMamaMo/service_list.php?deleted=1");
        exit();
    } else {
        echo "Error deleting service: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
