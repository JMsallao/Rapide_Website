<?php
include '../connection.php';

// Fetch branches data
$branches = [];
$branchQuery = "SELECT * FROM branches";
$branchResult = $conn->query($branchQuery);
while ($row = $branchResult->fetch_assoc()) {
    $branches[] = $row;
}

// Fetch user locations data
$userLocations = [];
$userQuery = "SELECT * FROM user_loc";
$userResult = $conn->query($userQuery);
while ($row = $userResult->fetch_assoc()) {
    $userLocations[] = $row;
}

// Output data as JSON
header('Content-Type: application/json');
echo json_encode(['branches' => $branches, 'userLocations' => $userLocations]);

$conn->close();
?>
