<?php 
session_start();
include '../includes/header.php'; 
?>

<div class="login-page">
    <div class="login-box">
        <h2>Reset Password</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo "<div class='success'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']);
        }
        ?>

        <form action="process-forgot-password.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email..." required>
            </div>
            <button type="submit" class="login-btn">Send Reset Link</button>
            <div class="login-links">
                <a href="login.php">Remember your password? Return to Login</a>
                <a href="register.php">Don't have an account? Sign Up</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
