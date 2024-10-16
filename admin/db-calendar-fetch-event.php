<?php
require_once "../connection.php"; // Include your database connection file

header('Content-Type: application/json');

$sql = "SELECT eventDate, eventDescription FROM event";
$result = mysqli_query($conn, $sql);

$events = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
}

echo json_encode($events);

mysqli_close($conn);
?>
