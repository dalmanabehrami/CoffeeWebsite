<?php
// /functions_crud/cartitems-functions.php

function createCartItem($conn, $product_id, $quantity = 1) {
    $stmt = mysqli_prepare($conn, "INSERT INTO cart_items (product_id, quantity) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $product_id, $quantity);
    if(mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'id' => mysqli_insert_id($conn)];
    }
    return ['success' => false, 'error' => mysqli_error($conn)];
}

function getAllCartItems($conn) {
    $result = mysqli_query($conn, "SELECT id, product_id, quantity FROM cart_items ORDER BY id DESC");
    $items = [];
    while($row = mysqli_fetch_assoc($result)) $items[] = $row;
    return $items;
}

function getCartItemById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, product_id, quantity FROM cart_items WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function updateCartItem($conn, $id, $product_id, $quantity) {
    $stmt = mysqli_prepare($conn, "UPDATE cart_items SET product_id=?, quantity=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "iii", $product_id, $quantity, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteCartItem($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM cart_items WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

?>
