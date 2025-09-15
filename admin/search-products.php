<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../database/db_connection.php');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$search = '%' . trim($_GET['q']) . '%';

$stmt = $conn->prepare("SELECT DISTINCT name, price FROM products WHERE name LIKE ?");
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'name' => $row['name'],
        'price' => $row['price']
    ];
}

echo json_encode($products);
?>