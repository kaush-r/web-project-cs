<?php

if (function_exists('opcache_reset')) {
    opcache_reset();
}
/**
 * --------------------------------------------------------------------
 * SCRIPT CONFIGURATION
 * --------------------------------------------------------------------
 */

// --- Recipient Email ---
// The email address where you want to receive the messages.
$recipient_email = "kaushrox36@gmail.com";

// --- Gmail SMTP Credentials ---
// Your full Gmail address.
$smtp_username = "kaushrox36@gmail.com";
// Your Gmail App Password (NOT your regular password).
$smtp_password = "gxndagwmrpiigzid";


/**
 * --------------------------------------------------------------------
 * ERROR LOGGING
 * --------------------------------------------------------------------
 * This will save any errors to a file named `error_log.txt`
 * in the same directory as this script. It will not show errors
 * to the user, which is safer.
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

// Load the PHPMailer library.
// Make sure the 'PHPMailer' folder is in the same directory as this script.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize user input to prevent security issues.
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Basic validation to ensure fields are not empty and email is valid.
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo "Error: Invalid input. Please go back and fill out all fields correctly.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // --- Server settings ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Email Content ---
        // Set the "From" address to your own email. This is crucial for deliverability.
        $mail->setFrom($smtp_username, 'NetMind Contact Form');
        
        // Set the recipient's address.
        $mail->addAddress($recipient_email);
        
        // Add the visitor's email as a "Reply-To" address so you can reply directly to them.
        $mail->addReplyTo($email, $name);

        // Set email format to HTML to allow for better formatting.
        $mail->isHTML(true);
        $mail->Subject = "New message from NetMind by: $name";
        $mail->Body    = "<h2>New Message from Website Contact Form</h2>" .
                         "<p><b>Name:</b> " . $name . "</p>" .
                         "<p><b>Email:</b> " . $email . "</p>" .
                         "<h3>Message:</h3>" .
                         "<p>" . nl2br($message) . "</p>"; // nl2br converts new lines to <br> tags.

        // Attempt to send the email.
        $mail->send();
        echo 'Message sent successfully!';

    } catch (Exception $e) {
        // If sending fails, provide a generic error message to the user.
        echo "Message could not be sent. Please try again later.";
        
        // Log the detailed, actual error to our private error_log.txt file.
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
} else {
    // If the script is accessed directly without a POST request, show an error.
    echo "Error: Invalid access method.";
}
?>