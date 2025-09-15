<?php
session_start();
header('Content-Type: application/json');

include '../database/db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['id'] ?? null;

if (!$productId) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID missing']);
    exit;
}

$stmtOrders = $conn->prepare("DELETE FROM orders WHERE product_id = ?");
$stmtOrders->bind_param("i", $productId);
$stmtOrders->execute();
$stmtOrders->close();

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete from database']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$index]);
        }
    }
}

echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
$conn->close();
?>