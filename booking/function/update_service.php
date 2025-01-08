<?php
include('../../sessioncheck.php');
include('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['table'])) {
    $service_id = (int)$_POST['id'];
    $table = $_POST['table'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Ensure the table name is valid by matching it to allowed tables
    $allowed_tables = ['package_list', 'service_list', 'brake_service', 'ac_service'];
    if (!in_array($table, $allowed_tables)) {
        die("Invalid table specified.");
    }

    // Prepare the update query
    $query = "UPDATE $table SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssdi", $name, $description, $price, $service_id);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            header("Location: ../adminMoMamaMo/service_list.php?updated=1");
            exit();
        } else {
            echo "Error updating service: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error in prepared statement: " . $conn->error;
    }
} else {
    echo "Error: Required fields missing or invalid request.";
}

$conn->close();
?>