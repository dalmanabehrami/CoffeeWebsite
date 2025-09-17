<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

// Siguro që admin është kyqur
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../no-access.php");
    exit();
}

// Kontrollojmë ID-në
if (!isset($_GET['id'])) {
    die("User ID is missing.");
}

$id = intval($_GET['id']); // siguro ID si numër
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");

if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found!");
}

$user = mysqli_fetch_assoc($result);
$error = '';

// POST: Update user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if(!$name || !$email || !$role){
        $error = "Please fill all fields correctly!";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name=?, email=?, role=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $id);

        if(mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php?page=users");
            exit();
        } else {
            $error = "Error: ".mysqli_error($conn);
        }
    }
}
?>

<h2>Edit User</h2>

<?php if($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form action="" method="POST">
    <p>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" 
               value="<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>" required>
    </p>

    <p>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" 
               value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>" required>
    </p>

    <p>
        <label for="role">Role:</label><br>
        <select id="role" name="role" required>
            <option value="user" <?= $user['role']=='user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </p>

    <button type="submit">Update User</button>
</form>

<style>
form input, form select, form textarea { padding: 8px; width: 100%; margin-bottom: 10px; }
form button { padding: 8px 15px; background-color:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer;}
form button:hover { background-color:#2980b9; }
</style>

