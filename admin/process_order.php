<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function processOrderData(string &$name, string &$email, string &$address): void {
    $name = trim($name);
    $email = trim($email);
    $address = trim($address);

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email address."]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $paymentMethod = $_POST['payment-method'] ?? '';
    $acceptTerms = $_POST['accept-terms'] ?? '';
    $cartData = $_POST['cart-json'] ?? '[]'; // nga JS duhet të dërgohet si hidden input

    if (!$name || !$email || !$address || !$paymentMethod || $acceptTerms !== 'on') {
        echo json_encode(["status" => "error", "message" => "Please fill all required fields and accept terms."]);
        exit;
    }

    processOrderData($name, $email, $address);

    include '../database/db_connection.php';

    $cartItems = json_decode($cartData, true);

    if (empty($cartItems)) {
        echo json_encode(["status" => "error", "message" => "Your cart is empty!"]);
        exit;
    }

    // Ruaj porosinë në tabelën orders
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, payment_method) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("ssss", $name, $email, $address, $paymentMethod);

    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;

        $total = 0;
        $orderDetails = "";

        // Ruaj çdo produkt në order_items
        foreach ($cartItems as $item) {
            $productId = $item['id'];
            $productName = htmlspecialchars($item['name']);
            $price = floatval($item['price']);
            $quantity = intval($item['quantity']);
            $subtotal = $price * $quantity;
            $total += $subtotal;
            $orderDetails .= "$productName x $quantity = $" . number_format($subtotal, 2) . "\n";

            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmtItem->bind_param("iiid", $orderId, $productId, $quantity, $price);
            $stmtItem->execute();
            $stmtItem->close();
        }

        $orderDetails .= "\nTotal: $" . number_format($total, 2);

        // Dërgo email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'coffeeshopborcelle@gmail.com';
            $mail->Password = 'yxuw dygq clos osne';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('coffeeshopborcelle@gmail.com', 'Coffee Shop');
            $mail->addAddress('coffeeshopborcelle@gmail.com', 'Coffee Shop Admin');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(false);
            $mail->Subject = "New Order from $name";
            $mail->Body    = "Name: $name\nEmail: $email\nAddress: $address\nPayment Method: $paymentMethod\n\nOrder Details:\n$orderDetails";

            $mail->send();

            unset($_SESSION['cart']);

            echo json_encode(["status" => "success", "message" => "Order placed successfully! Email sent!"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Order placed, but failed to send email. Mailer Error: {$mail->ErrorInfo}"]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "Error placing order: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
