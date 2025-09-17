<?php
function loginUser($conn, $email, $password) {
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password required'];
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE email=?");
    if (!$stmt) {
        return ['success' => false, 'message' => 'Database prepare failed: ' . $conn->error];
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            return ['success' => true, 'message' => 'Login successful'];
        } else {
            return ['success' => false, 'message' => 'Your password is incorrect'];
        }
    }

    // Nëse email nuk ekziston, kthe të njëjtën mesazh si password i gabuar për siguri
    return ['success' => false, 'message' => 'Your password is incorrect'];
}
?>
