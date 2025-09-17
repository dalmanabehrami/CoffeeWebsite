<?php
<<<<<<< HEAD
// Fshirja e produktit
function deleteProduct($conn, $id) {
=======
include '../database/db_connection.php';

function deleteProduct($id) {
    global $conn;
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
<<<<<<< HEAD

// Kërkimi i produkteve
function searchProduct($conn, $productName) {
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
=======
?>
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
