<?php
function registerUser($conn, $name, $email, $password, $confirm_password) {
    // Kontrollo nëse të gjitha fushat janë plotësuar
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        return ['success' => false, 'error' => 'Please fill in all fields'];
    }

    // Kontrollo nëse fjalëkalimet përputhen
    if ($password !== $confirm_password) {
        return ['success' => false, 'error' => 'Passwords do not match!'];
    }

    // Kontrollo nëse email-i është valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Please enter a valid email!'];
    }

    // Kontrollo gjatësi minimale të fjalëkalimit
    if (strlen($password) < 8) {
        return ['success' => false, 'error' => 'Password must be at least 8 characters long!'];
    }

    // Kontrollo nëse email-i ekziston
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    if(!$stmt) return ['success' => false, 'error' => 'Database prepare failed: '.$conn->error];
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return ['success' => false, 'error' => 'This email is already registered!'];
    }

    // Regjistro përdoruesin
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if(!$stmt) return ['success' => false, 'error' => 'Database prepare failed: '.$conn->error];
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => 'Database error: ' . $stmt->error];
    }
}
?>

