<?php
include '../database/db_connection.php';

$result = $conn->query("SELECT * FROM cart");

echo "<h2>Shporta</h2>";
echo "<div class='cart-items'>";
while ($row = $result->fetch_assoc()) {
    echo "<div class='cart-item'>";
    echo "<img src='{$row['image']}' alt='{$row['name']}' width='50'>";
    echo "<span>{$row['name']}</span> - <span>{$row['price']}€</span>";
    echo "</div>";
}
echo "</div>";

$conn->close();
?>
