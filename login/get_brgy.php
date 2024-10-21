<?php
include('../connection.php');

if (isset($_GET['city_id'])) {
    $city_id = $_GET['city_id'];

    $query = "SELECT id, brgy_name FROM brgy_list WHERE city_id = '$city_id'";
    $result = mysqli_query($conn, $query);

    $barangays = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $barangays[] = $row;
        }
    }

    echo json_encode($barangays);
}
?>
