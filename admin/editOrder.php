<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

// Siguro që admin është kyqur
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../no-access.php");
    exit();
}

// Kontrollojmë ID-në e porosisë
if (!isset($_GET['id'])) {
    die("Order ID is missing.");
}

$id = intval($_GET['id']); 
$result = mysqli_query($conn, "SELECT * FROM orders WHERE id=$id");
if (!$result || mysqli_num_rows($result) == 0) {
    die("Order not found!");
}
$order = mysqli_fetch_assoc($result);
$error = '';

// Merr të gjithë user-at dhe produktet për dropdown
$users = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");
$products = mysqli_query($conn, "SELECT id, name FROM products ORDER BY name ASC");

// POST: Update Order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if($user_id <= 0 || $product_id <= 0 || $quantity <= 0){
        $error = "Please select a valid user, product and quantity!";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET user_id=?, product_id=?, quantity=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "iiii", $user_id, $product_id, $quantity, $id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: dashboard.php?page=orders");
            exit();
        } else {
            $error = "Error: ".mysqli_error($conn);
        }
    }
}
?>

<h2>Edit Order</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

<form action="" method="POST">
    <p>
        <label>User:</label><br>
        <select name="user_id" required>
            <option value="">--Select User--</option>
            <?php while($u = mysqli_fetch_assoc($users)) { ?>
                <option value="<?= $u['id'] ?>" <?= ($u['id']==$order['user_id'])?'selected':'' ?>>
                    <?= htmlspecialchars($u['name'], ENT_QUOTES) ?>
                </option>
            <?php } ?>
        </select>
    </p>

    <p>
        <label>Product:</label><br>
        <select name="product_id" required>
            <option value="">--Select Product--</option>
            <?php while($p = mysqli_fetch_assoc($products)) { ?>
                <option value="<?= $p['id'] ?>" <?= ($p['id']==$order['product_id'])?'selected':'' ?>>
                    <?= htmlspecialchars($p['name'], ENT_QUOTES) ?>
                </option>
            <?php } ?>
        </select>
    </p>

    <p>
        <label>Quantity:</label><br>
        <input type="number" name="quantity" min="1" value="<?= $order['quantity'] ?>" required>
    </p>

    <button type="submit">Update Order</button>
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
