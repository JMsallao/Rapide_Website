<?php
include('../connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST['uname'];
    $password = $_POST['password'];

    // Prepare SQL query to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $is_admin = $row['is_admin'];
        $stored_password = $row['password']; // Can be hashed or plain text depending on user type

        // Check if the user is an admin
        if ($is_admin == 1) {
            // Admins use plain text password comparison
            if ($password == $stored_password) {
                $_SESSION["uname"] = $uname;
                $_SESSION["id"] = $row['id'];
                header('Location: ../admin/homepage.php');
                exit();
            } else {
                echo '<script type="text/javascript">
                alert("Invalid admin password");
                window.location = "login.php";
                </script>';
                exit();
            }
        } else {
            // Regular users use hashed password verification
            if (password_verify($password, $stored_password)) {
                $_SESSION["uname"] = $uname;
                $_SESSION["id"] = $row['id'];
                echo '<script type="text/javascript">
                alert("Welcome");
                window.location = "../user/u-homepage.php";
                </script>';
                exit();
            } else {
                echo '<script type="text/javascript">
                alert("Invalid user password");
                window.location = "login.php";
                </script>';
                exit();
            }
        }
    } else {
        echo '<script type="text/javascript">
        alert("Invalid username or password");
        window.location = "login.php";
        </script>';
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>