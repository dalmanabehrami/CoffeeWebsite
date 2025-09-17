<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']) ?: 1;

    if ($product_id <= 0 || $quantity <= 0) {
        $error = "Please select a product and enter a valid quantity!";
    } else {
        // Kontrollo nëse produkti ekziston
        $result = mysqli_query($conn, "SELECT name, price, image FROM products WHERE id=$product_id");
        if (!$result || mysqli_num_rows($result) == 0) {
            $error = "Selected product does not exist!";
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO cart_items (product_id, quantity) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $product_id, $quantity);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: dashboard.php?page=cart_items");
                exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Merr të gjithë produktet për dropdown
$products = mysqli_query($conn, "SELECT id, name, price FROM products ORDER BY name ASC");
?>

<h2>Add Cart Item</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

<form action="addCartItem.php" method="POST">
    <p>
        <label>Product:</label><br>
        <select name="product_id" required>
            <option value="">-- Select Product --</option>
            <?php while($row = mysqli_fetch_assoc($products)): ?>
                <option value="<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['name']) ?> - $<?= number_format($row['price'],2) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>
    <p>
        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="1" min="1" required>
    </p>
    <button type="submit">Add Cart Item</button>
</form>

<style>
form input, form select { 
    padding: 8px; 
    width: 100%; 
    margin-bottom: 10px; 
}
form button { 
    padding: 8px 15px; 
    background-color:#3498db; 
    color:#fff; 
    border:none; 
    border-radius:5px; 
    cursor:pointer;
}
form button:hover { 
    background-color:#2980b9; 
}
</style>

