<?php 
session_start();
require_once "../connection.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1){
    echo "<script>alert('Access denied!'); window.location.href = '../home/login-form.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];

    // Update booking status to done
    $sql = "UPDATE booking SET status = 'done' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        // Trigger the rating popup by redirecting to the rating page
        echo "<script>
            window.location.href = 'c-ratings.php?booking_id={$book_id}';
        </script>";
    } else {
        echo "<script>alert('Failed to update booking status.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
