<?php
session_start();
include '../database/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

function getResetRequestCount($conn, $email) {
    $stmt = $conn->prepare("SELECT request_count FROM forgot_password_requests WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $count = $row['request_count'] + 1;
        $stmt = $conn->prepare("UPDATE forgot_password_requests SET request_count = ?, last_request = NOW() WHERE email = ?");
        $stmt->bind_param("is", $count, $email);
        $stmt->execute();
    } else {
        $count = 1;
        $stmt = $conn->prepare("INSERT INTO forgot_password_requests (email, request_count, last_request) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $email, $count);
        $stmt->execute();
    }

    return $count;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email!";
        header("Location: forgot-password.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "No account exists with this email!";
        header("Location: forgot-password.php");
        exit();
    }

    $count = getResetRequestCount($conn, $email);

    $token = str_pad(random_int(0, 9999), 4, "0", STR_PAD_LEFT);
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $stmt = $conn->prepare("UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'coffeeshopborcelle@gmail.com';
        $mail->Password   = 'yxuw dygq clos osne';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('coffeeshopborcelle@gmail.com', 'Coffee Shop');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code';
        $mail->Body    = "<p>Your password reset code is: <strong>$token</strong></p><p>This code will expire in 1 hour.</p>";

        $mail->send();

        $_SESSION['reset_email'] = $email;
        $_SESSION['message'] = "We have sent a 4-digit code to your email. This is your #$count request.";
        header("Location: reset-password.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: forgot-password.php");
        exit();
    }
}
?>