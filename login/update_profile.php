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

    // Define a separate upload directory (outside login folder)
    $upload_dir = '../users/uploads/profile_pics';  // Go up one level and into uploads/profile_pics/

    // Handle profile picture upload if a file is provided
    $profile_pic = '';

    if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        // Check if the uploads directory exists, create it if not
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                die("Failed to create upload directory: $upload_dir");
            }
        }
    
        // Generate a unique filename based on user ID and current timestamp
        $filename = $user_id . '_' . time() . '_' . basename($_FILES['pic']['name']);
        $target_file = $upload_dir . '/' . $filename;
    
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $target_file)) {
            $profile_pic = 'uploads/profile_pics/' . $filename; // Save relative path for the user profile
        } else {
            die("Failed to move uploaded file to $target_file. Check directory permissions.");
        }
    } else {
        if ($_FILES['pic']['error'] !== UPLOAD_ERR_NO_FILE) {
            die("Error with file upload: " . $_FILES['pic']['error']);
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
                brgy = '$barangay', 
                bday = '$bday'";

    // Append the profile picture path if it was uploaded
    if (!empty($profile_pic)) {
        $query .= ", pic = '$profile_pic'";
    }

    // Finalize the query with the user ID condition
    $query .= " WHERE id = '$user_id'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Profile updated successfully."); window.location.href = "../users/Homepage.php";</script>';
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>