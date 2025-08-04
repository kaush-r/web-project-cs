<?php

// Allow requests from your GitHub Pages site
header("Access-Control-Allow-Origin: https://kaush-r.github.io");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Browsers send a pre-flight "OPTIONS" request first. We just need to acknowledge it.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Optional: Clear server cache. You can remove this after everything is working.
if (function_exists('opcache_reset')) {
    opcache_reset();
}

/**
 * --------------------------------------------------------------------
 * SCRIPT CONFIGURATION
 * --------------------------------------------------------------------
 */
$recipient_email = "kaushrox36@gmail.com";
$smtp_username = "kaushrox36@gmail.com";
$smtp_password = "gxndagwmrpiigzid"; // Your Gmail App Password

/**
 * --------------------------------------------------------------------
 * ERROR LOGGING
 * --------------------------------------------------------------------
 */
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

/**
 * --------------------------------------------------------------------
 * DO NOT EDIT BELOW THIS LINE
 * --------------------------------------------------------------------
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo "Error: Invalid input.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($smtp_username, 'NetMind Contact Form');
        $mail->addAddress($recipient_email);
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "New message from NetMind by: $name";
        $mail->Body    = "<h2>New Message from Website Contact Form</h2>" .
                         "<p><b>Name:</b> " . $name . "</p>" .
                         "<p><b>Email:</b> " . $email . "</p>" .
                         "<h3>Message:</h3>" .
                         "<p>" . nl2br($message) . "</p>";

        $mail->send();
        echo 'Message sent successfully!';

    } catch (Exception $e) {
        echo "Message could not be sent. Please try again later.";
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
} else {
    echo "Error: Invalid access method.";
}
?>