<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$successMsg = '';
$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  $mail = new PHPMailer(true);
  try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kaushrox36@gmail.com'; // Your Gmail
    $mail->Password = 'zmnlfdapuhvnzjtd';   // App password from step 1
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $mail->setFrom($email, $name);
    $mail->addAddress('kaushrox36@gmail.com');  // Where you want to receive the mail
    $mail->Subject = 'New message from your website';
    $mail->Body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $mail->send();
    $successMsg = "Message sent successfully!";
  } catch (Exception $e) {
    $errorMsg = "Mailer Error: {$mail->ErrorInfo}";
  }
}
?>
