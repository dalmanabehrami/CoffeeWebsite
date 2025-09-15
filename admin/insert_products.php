<?php
include '../database/db_connection.php';

$products = [
    ['Americano', 25, 'menu-1.png'],
    ['Espresso', 20, 'menu-2.png'],
    ['Cappuccino', 25, 'menu-3.png'],
    ['Latte', 30, 'menu-4.png'],
    ['Macchiato', 25, 'menu-5.png'],
    ['Mocha', 15, 'menu-6.png'],
    ['Cortado', 20, 'menu-7.png'],
    ['Ristretto', 20, 'menu-8.png'],
    ['Affogato', 30, 'menu-9.png'],
    ['Turkish Coffee', 35, 'cart-item1.png'],
    ['Coffee!', 25, 'cart-item2.png'],
    ['Coffee#', 25, 'cart-item3.png']
];

$stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
foreach ($products as $product) {
    $stmt->bind_param("sis", $product[0], $product[1], $product[2]);
    $stmt->execute();
}

echo "Produktet u shtuan me sukses!";
$stmt->close();
$conn->close();
?>