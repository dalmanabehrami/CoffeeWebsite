<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Merr edhe fushën role nga DB
    $query = "SELECT id, name, password, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // Ruaj të dhënat në session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role'] = $row['role']; // <--- Roli i përdoruesit
            $_SESSION['logged_in'] = true;

            // Cookie për herën e parë
            $cookieName = 'first_login_' . date('Y-m-d') . '_' . $row['id'];
            if (!isset($_COOKIE[$cookieName])) {
                setcookie($cookieName, '1', strtotime('tomorrow 00:00:00'), "/");
                $_SESSION['show_welcome'] = true;
            } else {
                $_SESSION['show_welcome'] = false;
            }

            // Redirect bazuar në rol
            if ($row['role'] === 'admin') {
                header("Location: ../admin/dashboard.php"); // faqja kryesore e adminit
            } else {
                header("Location: ../index.php"); // faqja kryesore për user
            }
            exit();
        } else {
            $error = "Password incorrect!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<div class="login-page">
    <div class="login-box">
        <h2>Login Now</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Your Email..." required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password..." required>
            </div>
            <button type="submit" class="login-btn">Login</button>
            <div class="login-links">
                <a href="forgot-password.php">Forgot Password?</a>
                <a href="register.php">Create Account</a>
                <a href="help.php">Help</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
