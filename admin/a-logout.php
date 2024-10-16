<?php
session_start();
// Unset or destroy relevant session variables
unset($_SESSION['account_id']);

session_destroy(); // Optionally destroy the session completely
// Redirect to login or homepage
header("Location: ../home/login-form.php");
exit();
?>