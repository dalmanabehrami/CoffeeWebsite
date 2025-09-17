<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "coffee_shop";
$email = "testuser@example.com";
$password_plain = "123456";
$reset_code = "1234";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1️⃣ Kontrollo nëse useri ekziston, nëse jo krijo
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $hashed_pass = password_hash($password_plain, PASSWORD_DEFAULT);
    $stmt_insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $name = "Test User";
    $stmt_insert->bind_param("sss", $name, $email, $hashed_pass);
    $stmt_insert->execute();
    $stmt_insert->close();
    echo "✅ User u krijua: $email\n";
} else {
    echo "ℹ️ Useri ekziston: $email\n";
}

// 2️⃣ Vendos kodin 4-shifror dhe kohën e skadimit
$hashed_token = password_hash($reset_code, PASSWORD_DEFAULT);
$stmt_update = $conn->prepare("UPDATE users SET reset_token_hash=?, reset_token_expires_at=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?");
$stmt_update->bind_param("ss", $hashed_token, $email);
$stmt_update->execute();
$stmt_update->close();
$conn->close();
echo "✅ Kodi 4-shifror u vendos në DB: $reset_code\n";

// 3️⃣ Vendos session për reset-password.php
$_SESSION['reset_email'] = $email;
echo "✅ Session për reset-password.php u vendos: $email\n";

?>
