<?php
// /functions_crud/product-functions.php

function createProduct($conn, $name, $description, $price, $image = null) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $image);
    if(mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'id' => mysqli_insert_id($conn)];
    }
    return ['success' => false, 'error' => mysqli_error($conn)];
}

function getAllProducts($conn) {
    $result = mysqli_query($conn, "SELECT id, name, description, price, image, created_at FROM products ORDER BY id DESC");
    $products = [];
    while($row = mysqli_fetch_assoc($result)) $products[] = $row;
    return $products;
}

function getProductById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, description, price, image, created_at FROM products WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function updateProduct($conn, $id, $name, $description, $price, $image = null) {
    $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssdsi", $name, $description, $price, $image, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteProduct($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

?>
