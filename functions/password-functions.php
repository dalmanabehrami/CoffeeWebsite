<?php
function sendResetCode($conn, $email) {
    if (!$email) {
        return ['success' => false, 'message' => 'Email required'];
    }

    // Kontrollo nëse email ekziston
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    if(!$stmt) return ['success'=>false,'message'=>'Database prepare failed: '.$conn->error];
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Email not registered'];
    }

    // Gjenero kod reset dhe ruaje te DB
    $code = rand(100000, 999999);
    $stmt = $conn->prepare("UPDATE users SET reset_code=? WHERE email=?");
    if(!$stmt) return ['success'=>false,'message'=>'Database prepare failed: '.$conn->error];
    $stmt->bind_param("is", $code, $email);
    $stmt->execute();

    return ['success' => true, 'message' => 'Reset code sent'];
}
?>
