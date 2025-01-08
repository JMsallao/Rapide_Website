<?php
session_start();
// Include your database connection file
include('../connection.php');

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Step 1: Capture form data
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $username = $_POST['uname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Step 2: Password validation using regular expressions
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?\":{}|<>])[A-Za-z\d!@#$%^&*(),.?\":{}|<>]{8,10}$/";

    if (!preg_match($pattern, $password)) {
        echo '<script type="text/javascript">
        alert("Password must be 8-15 characters long, include at least one uppercase and lowercase letter, one numeric character, and one special character.");
        window.location = "login.php";
        </script>';
        exit();  // Stop execution if password is invalid
    }

    // Step 3: If the password is valid, hash it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate a unique verification code
    $verificationCode = rand(100000, 999999);  // Generate a 6-digit code

    // Save the user data into temp_users table along with the verification code
    $query = "INSERT INTO temp_users (fname, lname, email, username, phone, password, verif_code) 
              VALUES ('$first_name', '$last_name', '$email','$username', '$phone', '$hashed_password', '$verificationCode')";
    
    if (mysqli_query($conn, $query)) {
        // Get the inserted user's ID
        $user_id = mysqli_insert_id($conn);  // This function returns the ID of the last inserted row

        // Store the user's ID in a session variable
        $_SESSION['id'] = $user_id;

        // Send verification email with the code using PHPMailer
        $mail = new PHPMailer(true);  // Enable exceptions  

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jessicaresultan8@gmail.com';   // Full Gmail address
            $mail->Password   = 'rtpndepckbzcqplh';  // App-specific password
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('jessicaresultan8@gmail.com', 'Rapide cavite');
            $mail->addAddress($email);  // The user's email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body    = "Your verification code is: <b>$verificationCode</b>";
            $mail->AltBody = "Your verification code is: $verificationCode";  // Fallback for non-HTML clients

            $mail->send();
            echo '<script type="text/javascript">
            alert("Registration successful. Please check your email for the verification code.");
            window.location = "verify.php";
            </script>';
        
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}