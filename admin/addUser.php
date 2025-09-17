<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!$name || !$email || !$password) {
        $error = "Please fill all fields!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php?page=users");
            exit();
        } else {
            $error = "Error: ".mysqli_error($conn);
        }
    }
}
?>

<h2>Add New User</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<form action="addUser.php" method="POST">
    <p>
        <label>Name:</label><br>
        <input type="text" name="name" required>
    </p>
    <p>
        <label>Email:</label><br>
        <input type="email" name="email" required>
    </p>
    <p>
        <label>Password:</label><br>
        <input type="password" name="password" required>
    </p>
    <p>
        <label>Role:</label><br>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </p>
    <button type="submit">Add User</button>
</form>

<style>
form input, form select { padding: 8px; width: 100%; margin-bottom: 10px; }
form button { padding: 8px 15px; background-color:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer;}
form button:hover { background-color:#2980b9; }
</style>
