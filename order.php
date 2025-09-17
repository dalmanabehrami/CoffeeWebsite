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

// Shto logjikën për të ruajtur porositë në DB kur dorëzohet forma
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $paymentMethod = $_POST['payment-method'] ?? '';
    $cartItems = json_decode($_POST['cart'], true);

    if ($name && $email && $address && $paymentMethod && !empty($cartItems)) {
        // ruaj çdo produkt nga cart në tabelën orders
        foreach ($cartItems as $item) {
            $stmt = $conn->prepare("INSERT INTO orders (name, email, address, product_id, payment_method, quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param(
                    "sssiidd",
                    $name,
                    $email,
                    $address,
                    $item['id'],
                    $paymentMethod,
                    $item['quantity'],
                    $item['price']
                );
                $stmt->execute();
                $stmt->close();
            }
        }
        // Pastro cart-in
        unset($_SESSION['cart']);
        $total = 0;
        $orderSuccess = true;
    }
}
?>
<?php include 'includes/order_content.php'; ?>
<script src="assets/js/order_process.js"></script>
<?php include 'includes/footer.php'; ?>

