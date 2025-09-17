<?php
include '../database/db_connection.php';

// Fshirja e produktit
function deleteProduct($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Kërkimi i produkteve
function searchProduct($productName) {
    global $conn;
    $productName = '%' . trim($productName) . '%';
    $stmt = $conn->prepare("SELECT DISTINCT name FROM products WHERE name LIKE ?");
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row['name'];
    }

    return $products;
}
?>
