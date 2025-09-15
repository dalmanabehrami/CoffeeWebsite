<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to send feedback.']);
    exit();
}

$response = ['success' => false, 'message' => 'Something went wrong.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_message'])) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'coffeeshopborcelle@gmail.com';
        $mail->Password   = 'yxuw dygq clos osne';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('coffeeshopborcelle@gmail.com', 'UEB25 Coffee Shop');
        $mail->addAddress('coffeeshopborcelle@gmail.com');
        $mail->Subject = 'Feedback from Customer';

        $userMessage = trim($_POST['custom_message']) ?: 'No message';

        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Unknown user';

        $mail->Body = "From: $username\n\n" . $userMessage;

        $mail->send();
        $response['success'] = true;
        $response['message'] = "Feedback sent successfully!";
    } catch (Exception $e) {
        $response['message'] = "Mailer Error: " . $mail->ErrorInfo;
    }
}

echo json_encode($response);
?>