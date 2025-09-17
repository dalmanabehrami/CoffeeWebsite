<?php
function placeOrder($conn, $order) {
    // Kontrollo që array ka të dhënat e duhura
    if (!isset($order['product_id']) || !isset($order['quantity']) || $order['quantity'] <= 0 || !isset($order['user_id'])) {
        return ['success' => false, 'message' => 'Invalid order'];
    }

    $user_id = $order['user_id'];
    $product_id = $order['product_id'];
    $quantity = $order['quantity'];

    // Përgatit query për të futur porosinë
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)");
    if (!$stmt) return ['success' => false, 'message' => 'Database prepare failed: '.$conn->error];

    $stmt->bind_param("iii", $user_id, $product_id, $quantity);

    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Order placed successfully'];
    } else {
        return ['success' => false, 'message' => 'Database error: '.$stmt->error];
    }
}
?>
