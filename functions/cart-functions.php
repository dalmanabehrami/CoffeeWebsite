<?php
// cart-functions.php

class Cart {
    private $conn;

    // $conn ia kalon testi ose aplikacioni
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addProduct($product_id, $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO cart_items (product_id, quantity) VALUES (?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("ii", $product_id, $quantity);
        return $stmt->execute();
    }

    public function removeProduct($product_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE product_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }

    public function getItems() {
        $result = $this->conn->query("SELECT * FROM cart_items");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    public function clearCart() {
        $this->conn->query("TRUNCATE TABLE cart_items");
    }
}
?>
