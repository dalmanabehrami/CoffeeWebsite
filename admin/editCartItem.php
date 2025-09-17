<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

// Siguro që admin është kyqur
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../no-access.php");
    exit();
}

// Kontrollojmë ID-në
if (!isset($_GET['id'])) {
    die("Cart Item ID is missing.");
}

$id = intval($_GET['id']); // siguro ID si numër
$result = mysqli_query($conn, "SELECT * FROM cart_items WHERE id=$id");

if (!$result || mysqli_num_rows($result) == 0) {
    die("Cart Item not found!");
}

$item = mysqli_fetch_assoc($result);
$error = '';

// Merr të gjithë produktet për dropdown
$products = mysqli_query($conn, "SELECT id, name, price FROM products ORDER BY name ASC");

// POST: Update cart item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']) ?: 1;

    if ($product_id <= 0 || $quantity <= 0) {
        $error = "Please select a product and enter a valid quantity!";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE cart_items SET product_id=?, quantity=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "iii", $product_id, $quantity, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php?page=cart_items");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<h2>Edit Cart Item</h2>

<?php if($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form action="" method="POST">
    <p>
        <label for="product">Product:</label><br>
        <select name="product_id" id="product" required>
            <option value="">-- Select Product --</option>
            <?php while($row = mysqli_fetch_assoc($products)): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id']==$item['product_id']?'selected':'' ?>>
                    <?= htmlspecialchars($row['name']) ?> - $<?= number_format($row['price'],2) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" value="<?= $item['quantity'] ?>" min="1" required>
    </p>

    <button type="submit">Update Cart Item</button>
</form>

<style>
form input, form select { padding: 8px; width: 100%; margin-bottom: 10px; }
form button { padding: 8px 15px; background-color:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer;}
form button:hover { background-color:#2980b9; }
</style>

