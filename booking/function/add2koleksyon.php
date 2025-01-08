<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Get data from the form
$id = $_SESSION['id']; // Get the user ID from session
$service_id = $_POST['service_id'];
$service_table = $_POST['service_table']; // Make sure this matches the form name
$service_name = $_POST['service_name'];
$service_price = $_POST['service_price'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;


// Insert into the cart table
$query = "INSERT INTO cart (user_id, service_id, service_tbl, service_name, price, quantity)
          VALUES ('$id', '$service_id', '$service_table', '$service_name', '$service_price', '$quantity')";

if (mysqli_query($conn, $query)) {
    // You can optionally send back a success response
    echo 'Item successfully added to cart.';
} else {
    echo 'Error: ' . mysqli_error($conn);
}
?>