<?php 
session_start();
include 'includes/header.php'; 
include 'database/db_connection.php'; 

$total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $price = floatval($item['price']);
        $quantity = $item['quantity'] ?? 1;
        $subtotal = $price * $quantity;
        $total += $subtotal;
    }
}
?>
<?php include 'includes/order_content.php';?>
<script src="assets/js/order_process.js"></script>
<?php include 'includes/footer.php'; ?>
