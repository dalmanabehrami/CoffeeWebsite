<?php
include '../database/db_connection.php';

// DELETE cart item
if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM cart_items WHERE id=$del_id");
    header("Location: dashboard.php?page=cart_items");
    exit();
}

// FETCH all cart items
$result = mysqli_query($conn, "SELECT * FROM cart_items ORDER BY id DESC");
?>

<h2>Manage Cart Items</h2>
<a href="addCartItem.php" class="btn">+ Add Cart Item</a>

<table border="1" width="100%" style="margin-top:15px; border-collapse: collapse;">
    <tr style="background-color:#3498db; color:#fff;">
        <th>ID</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { 
        $itemName = htmlspecialchars($row['product_name'] ?? 'Unknown', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $itemQuantity = intval($row['quantity']);
        $itemPrice = number_format(floatval($row['price']), 2);
        $imagePath = $row['image'] ?? '';

        // Kontrollo imazhin dhe vendos default nëse nuk ekziston
        $imageUrl = "../uploads/default-product.png";
        if(!empty($imagePath)){
            $fullPath = "../uploads/" . $imagePath;
            if(file_exists($fullPath)) $imageUrl = $fullPath;
        }
    ?>
    <tr style="background-color:#fff; border-bottom:1px solid #ddd;">
        <td><?= $row['id'] ?></td>
        <td><?= $itemName ?></td>
        <td><?= $itemQuantity ?></td>
        <td>$<?= $itemPrice ?></td>
        <td>
            <img src="<?= $imageUrl ?>" width="50" alt="<?= $itemName ?>">
        </td>
        <td>
            <a href="editCartItem.php?id=<?= $row['id'] ?>" style="margin-right:10px;">Edit</a>
            <a href="dashboard.php?page=cart_items&delete=<?= $row['id'] ?>" 
               onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<style>
table th, table td { padding: 12px; text-align: left; font-family: Arial, sans-serif; }
table tr:hover { background-color: #f1f1f1; }
.btn {
    display: inline-block;
    padding: 8px 15px;
    background-color: #2ecc71;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 10px;
}
.btn:hover { background-color: #27ae60; }
img { border-radius: 5px; }
</style>



