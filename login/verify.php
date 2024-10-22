<?php
session_start();
// Include your database connection
include('../connection.php');

// Get the user ID from the session
$user_id = $_SESSION['id'] ?? ''; // Use 'id' to match the session variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredCode = $_POST['verification_code'];

    // Check if the verification code matches for the given user ID
    $query = "SELECT * FROM temp_users WHERE id = '$user_id' AND verif_code = '$enteredCode'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Verification successful - Move user to main users table
        $user = mysqli_fetch_assoc($result);
        $first_name = $user['fname'];
        $last_name = $user['lname'];
        $email = $user['email'];
        $username = $user['username'];
        $phone = $user['phone'];
        $password = $user['password'];

        // Insert into the main users table
        $insertQuery = "INSERT INTO users (fname, lname, email, username, phone, password) 
                        VALUES ('$first_name', '$last_name', '$email', '$username', '$phone', '$password')";
        
        if (mysqli_query($conn, $insertQuery)) {
            // Get the newly inserted user's ID from the users table
            $new_user_id = mysqli_insert_id($conn);

            

            // Delete the user from temp_users
            $deleteQuery = "DELETE FROM temp_users WHERE id = '$user_id'";
            mysqli_query($conn, $deleteQuery);

            // Update the session to use the new user ID from the users table
            $_SESSION['id'] = $new_user_id; // Store the new user ID in 'id'

            // Redirect to the profile setup page
            echo '<script>alert("Verification successful! Redirecting to profile setup."); window.location = "profile_setup.php";</script>';
        } else {
            echo "Error inserting user into main users table: " . mysqli_error($conn);
        }
    } else {
        // Verification failed
        echo '<script>alert("Invalid verification code. Please try again.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* General body styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    /* Center the form container */
    .container {
        max-width: 400px;
        margin: 100px auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    /* Title styling */
    h2 {
        color: #333;
        margin-bottom: 20px;
        font-size: 24px;
    }

    /* Paragraph (instruction) styling */
    p {
        color: #666;
        margin-bottom: 20px;
        font-size: 16px;
    }

    /* Form label styling */
    .form-label {
        font-weight: bold;
        color: #333;
    }

    /* Input styling */
    .form-control {
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        width: 100%;
    }

    /* Button styling */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        border-radius: 5px;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Responsive design */
    @media (max-width: 576px) {
        .container {
            margin: 50px 20px;
        }

        h2 {
            font-size: 20px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Verify Your Account</h2>
        <p class="text-center">Please enter the verification code sent to your email.</p>

        <form action="verify.php" method="POST">
            <div class="mb-3">
                <label for="verification_code" class="form-label">Enter Verification Code</label>
                <input type="text" class="form-control" id="verification_code" name="verification_code"
                    placeholder="Enter the code" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>