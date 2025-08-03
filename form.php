<?php
header("Access-Control-Allow-Origin: https://kaush-r.github.io"); 
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit(0);
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

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
      http_response_code(400);
      echo "Invalid input. Please fill out all fields correctly.";
      exit;
  }

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kaushrox36@gmail.com';
    $mail->Password = 'zmnlfdapuhvnzjtd';  
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

 
    $mail->setFrom('kaushrox36@gmail.com', 'NetMind Contact Form'); 
    

    $mail->addReplyTo($email, $name);
    
 
    $mail->addAddress('kaushrox36@gmail.com');
    
    $mail->Subject = "New Message from NetMind Website by: $name";
    $mail->Body = "You have received a new message from your website contact form.\n\n" .
                  "Here are the details:\n\n" .
                  "Name: $name\n\n" .
                  "Email: $email\n\n" .
                  "Message:\n$message";

    $mail->send();
    http_response_code(200);
    echo "Message sent successfully!";
  } catch (Exception $e) {
    http_response_code(500);
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
} else {
  http_response_code(405);
  echo "Method Not Allowed";
}
?>