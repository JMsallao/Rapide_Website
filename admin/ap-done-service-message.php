<?php
session_start();
require_once "../connection.php";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpMailer/src/Exception.php';
require '../phpMailer/src/PHPMailer.php';
require '../phpMailer/src/SMTP.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    echo "<script>alert('Access denied!'); window.location.href = '../home/login-form.php';</script>";
    exit();
}

if (isset($_POST['book_id'])) {
    $booking_id = $_POST['book_id'];

    // Fetch client email from booking table
    $sql_select = "SELECT name, email, service FROM booking WHERE booking_id = ?";
    $stmt_select = $conn->prepare($sql_select);

    if ($stmt_select) {
        $stmt_select->bind_param("i", $booking_id);
        $stmt_select->execute();
        $stmt_select->bind_result($client_name, $client_email, $service);

        if ($stmt_select->fetch()) {
            $stmt_select->close();

            // Validate email address
            if (filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
                // Create a new PHPMailer instance
                $mail = new PHPMailer(true);

                try {
                    // Enable verbose debug output
                    $mail->SMTPDebug = 0; // Change to 0 to disable debugging output
                    $mail->Debugoutput = 'html'; // Output format for debugging

                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;
                    $mail->Username   = 'actech.atservice@gmail.com';
                    $mail->Password   = 'dzzsdpebibvcavwb';      
                    $mail->SMTPSecure = 'ssl';                 // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                         // TCP port to connect to

                    // Recipients
                    $mail->setFrom('actech.atservice@gmail.com', 'A.C Tech');
                    $mail->addAddress($client_email, $client_name); // Add a recipient

                    // Content
                    $mail->isHTML(true);                       // Set email format to HTML
                    $mail->Subject = 'Booking Successfully Completed';
                    $mail->Body    = "Dear " . htmlspecialchars($client_name) . ",<br><br>Your booking for " . htmlspecialchars($service) . " has been successfully completed.<br><br>We hope you are satisfied with our service. Please do not hesitate to contact us for further assistance.<br><br>Best regards,<br>A.C Tech";
                    $mail->AltBody = "Dear " . htmlspecialchars($client_name) . ",\n\nYour booking for " . htmlspecialchars($service) . " has been successfully completed.\n\nWe hope you are satisfied with our service. Please do not hesitate to contact us for further assistance.\n\nBest regards,\nA.C Tech";

                    if($mail->send()) {
                        // Update notification_sent status in the database
                        $sql_update = "UPDATE booking SET notification_sent = 1 WHERE booking_id = ?";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bind_param("i", $booking_id);
                        $stmt_update->execute();
                        $stmt_update->close();

                        echo "<script>alert('Notification sent successfully!'); window.location.href = 'db-ap-rejected.php';</script>";
                    } else {
                        echo "<script>alert('Failed to send email. Mailer Error: {$mail->ErrorInfo}'); window.location.href = 'db-ap-rejected.php';</script>";
                    }
                } catch (Exception $e) {
                    echo "<script>alert('Failed to send email. Mailer Error: {$mail->ErrorInfo}'); window.location.href = 'db-ap-rejected.php';</script>";
                }
            } else {
                echo "Invalid email address: $client_email.<br>";
            }
        } else {
            echo "Failed to fetch client details.<br>";
        }
    } else {
        echo "Failed to prepare SQL statement: " . $conn->error . "<br>";
    }
} else {
    echo "Invalid request.<br>";
}

$conn->close();
?>
