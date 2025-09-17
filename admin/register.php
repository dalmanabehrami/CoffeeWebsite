<?php
<<<<<<< HEAD
session_start();
=======
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
include '../includes/header.php';
include '../database/db_connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
<<<<<<< HEAD
    $role = $_POST['role']; // Fusha e re për zgjedhjen e rolit
=======
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all fields!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "This email is already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
<<<<<<< HEAD
            $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $hashed_password, $role);
            
            if (mysqli_stmt_execute($stmt)) {
                // Ruaj session dhe redirect bazuar në rol
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = $role;
                $_SESSION['logged_in'] = true;

                if ($role === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
=======
            $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Registration completed successfully! You can now log in.";
                header("Location: login.php");
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
                exit();
            } else {
                $error = "Registration error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="login-page">
    <div class="login-box">
        <h2>Create Account</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
<<<<<<< HEAD
=======
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Your Name..." required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Your Email..." required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password..." required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password..." required>
            </div>

<<<<<<< HEAD
            <div class="form-group">
                <label for="role">Select Role</label>
                <select name="role" id="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

=======
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
            <button type="submit" class="login-btn">Register</button>
            
            <div class="login-links">
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </div>
        </form>
    </div>
</div>

<<<<<<< HEAD
<?php include '../includes/footer.php'; ?>
=======
<?php include '../includes/footer.php'; ?>
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
