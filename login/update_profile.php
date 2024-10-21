<?php
session_start();
include('../connection.php');

// Ensure the session user ID is set correctly
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['id'];

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the posted data
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $bday = mysqli_real_escape_string($conn, $_POST['bday']);

    // Handle profile picture upload if a file is provided
    $profile_pic = '';

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        // Define the directory to upload to
        $upload_dir = 'uploads/';
        // Generate a unique filename based on user ID and current timestamp
        $filename = $user_id . '_' . time() . '_' . basename($_FILES['profile_pic']['name']);
        $target_file = $upload_dir . $filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            $profile_pic = $target_file; // Save the file path for updating the user profile
        } else {
            echo '<script>alert("Failed to upload profile picture.");</script>';
        }
    }

    // Prepare the SQL update query
    $query = "UPDATE users SET 
                fname = '$fname', 
                lname = '$lname', 
                username = '$username', 
                phone = '$phone', 
                province = '$province', 
                city = '$city', 
                barangay = '$barangay', 
                bday = '$bday'";

    // Append the profile picture path if it was uploaded
    if (!empty($profile_pic)) {
        $query .= ", profile_pic = '$profile_pic'";
    }

    // Finalize the query with the user ID condition
    $query .= " WHERE id = '$user_id'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Profile updated successfully."); window.location.href = "user_homepage.php";</script>';
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>
