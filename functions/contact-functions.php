<?php
// contact-functions.php
function sendContactMessage($conn, $form) {
    $name = htmlspecialchars($form['name'] ?? '');
    $email = htmlspecialchars($form['email'] ?? '');
    $subject = htmlspecialchars($form['subject'] ?? 'No Subject');
    $message = htmlspecialchars($form['message'] ?? '');
    $age = htmlspecialchars($form['age'] ?? '');
    $phone = htmlspecialchars($form['phone'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
        return ['success' => false, 'error' => 'Please fill in all fields'];
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid email address'];
    }

    // Fut mesazhin në databazë
    $stmt = $conn->prepare("INSERT INTO contact_messages (name,email,subject,message,age,phone) VALUES (?,?,?,?,?,?)");
    if(!$stmt) return ['success'=>false,'error'=>'Database prepare failed: '.$conn->error];
    $stmt->bind_param("ssssss", $name, $email, $subject, $message, $age, $phone);

    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Message saved successfully!'];
    } else {
        return ['success' => false, 'error' => 'Database error: '.$stmt->error];
    }
}
?>
