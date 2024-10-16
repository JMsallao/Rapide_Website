<?php
require_once '../connection.php';

header('Content-Type: application/json');

$sql = "SELECT date, time FROM booking WHERE status = 'approved'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$booking = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $booking[] = $row;
    }
}

echo json_encode($booking);
mysqli_close($conn);
?>
