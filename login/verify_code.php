<?php
session_start();
// Include your database connection
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredCode = $_POST['verification_code'];

    // Get the email from session
    $email = $_SESSION['email'];

    // Check if the verification code matches
    $query = "SELECT * FROM temp_users WHERE email = '$email' AND verif_code = '$enteredCode'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Verification successful - Move user to main table
        $user = mysqli_fetch_assoc($result);
        $first_name = $user['fname'];
        $last_name = $user['lname'];
        $username = $user['username'];
        $phone = $user['phone'];
        $password = $user['password'];

        // Insert into the main users table
        $insertQuery = "INSERT INTO users (fname, lname, email, username, phone, password) 
                        VALUES ('$first_name', '$last_name', '$email', '$username', '$phone', '$password')";
        
        if (mysqli_query($conn, $insertQuery)) {
            // Delete user from temp_users
            $deleteQuery = "DELETE FROM temp_users WHERE email = '$email'";
            mysqli_query($conn, $deleteQuery);

            echo '<script>alert("Verification successful! You can now log in."); window.location = "login.php";</script>';
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Verification failed
        echo '<script>alert("Invalid verification code. Please try again.");</script>';
    }
}
?>
