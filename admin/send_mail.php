<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer(true);

function configureMailer(PHPMailer &$mail, string &$body): void {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'coffeeshopborcelle@gmail.com';
    $mail->Password = 'yxuw dygq clos osne';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('coffeeshopborcelle@gmail.com', 'UEB25 Coffee Shop');
    $mail->addAddress('coffeeshopborcelle@gmail.com');
    $mail->Subject = 'Feedback from Customer';
    $mail->Body = $body;
}

try {
    $body = isset($_POST['custom_message']) && !empty(trim($_POST['custom_message']))
        ? trim($_POST['custom_message'])
        : 'No message';

    configureMailer($mail, $body);
    $mail->send();

    echo '<div style="text-align: center; color: green; font-weight: bold; margin-top: 20px;">Email was sent successfully.</div>';
} catch (Exception $e) {
    echo "Email sending failed. Error: {$mail->ErrorInfo}";
}
?>