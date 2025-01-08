<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Get the cart ID and new quantity from the POST request
if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = (int)$_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];

    // Update the quantity in the cart
    $query = "UPDATE cart SET quantity = '$quantity' WHERE id = '$cart_id' AND user_id = '{$_SESSION['id']}'";

    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
