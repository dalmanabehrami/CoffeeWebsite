<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['reset_email'])) {
    $_SESSION['error'] = "This action is not allowed directly.";
    header("Location: forgot-password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include '../includes/header.php';
    ?>
    
    <div class="login-page">
        <div class="login-box">
            <h2>Reset Your Password</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <form method="POST" action="reset-password.php">
                <div class="form-group">
                    <input type="text" name="reset_code" placeholder="Enter the 4-digit code" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="New Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="login-btn">Reset Password</button>
            </form>
        </div>
    </div>

    <?php
    include '../includes/footer.php';
    exit();
}

$reset_code = trim($_POST['reset_code']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND reset_token_hash = ? AND reset_token_expires_at > NOW()");
$stmt->bind_param("ss", $email, $reset_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "The reset code is incorrect or has expired.";
    header("Location: reset-password.php");
    exit();
}

$user = $result->fetch_assoc();
$user_id = $user['id'];

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: reset-password.php");
    exit();
}

if (strlen($password) < 6) {
    $_SESSION['error'] = "Password must be at least 6 characters long.";
    header("Location: reset-password.php");
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?");
$stmt->bind_param("si", $hashed_password, $user_id);
$stmt->execute();

unset($_SESSION['reset_email']);
$_SESSION['message'] = "Your password has been reset successfully. You can now log in.";
header("Location: login.php");
exit();
?>