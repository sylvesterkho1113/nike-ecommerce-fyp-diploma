<?php
include 'header.php';

Print_r($_SESSION);

$Customer_Email = $_SESSION["Customer_Email"];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Validate email address
$email = $Customer_Email;
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email address')</script>";
    exit; // Exit if email is invalid
}

// Construct the reset password URL with the email parameter
$reset_password_url = 'http://localhost/fyp/reset_password.php?email=' . urlencode($Customer_Email);

// Retrieve form data
$subject = 'Reset Password';
$message = "<html>
<head>
  <title>Reset Password</title>
</head>
<body>
  <p>Please click <a href='{$reset_password_url}'>here</a> to reset your password.</p>
</body>
</html>";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'tktsportshoes432@gmail.com'; // Gmail username
$mail->Password = 'yxgc lwez dznt ouga'; // Gmail app password
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

// Set email details
$mail->setFrom('tktsportshoes432@gmail.com');
$mail->addAddress($email);
$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body = $message;

// Send email
try {
    $mail->send();
    echo"<script>alert('Please check your email.'); window.location.href = 'login.php';</script>";
    exit;
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
?>
