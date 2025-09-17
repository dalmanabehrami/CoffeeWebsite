<?php
include '../database/db_connection.php';

// Delete product
if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id=$del_id");
    header("Location: dashboard.php?page=products"); // redirect brenda dashboard
    exit();
}

// Merr të gjithë produktet
$result = mysqli_query($conn, "SELECT id, name, description, price, image FROM products ORDER BY id DESC");
?>

<h2>Manage Products</h2>
<a href="addProduct.php" class="btn">+ Add Product</a>

<table border="1" width="100%" style="margin-top:15px; border-collapse: collapse;">
    <tr style="background-color:#3498db; color:#fff;">
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr style="background-color:#fff; border-bottom:1px solid #ddd;">
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
        <td><?= htmlspecialchars($row['description'], ENT_QUOTES) ?></td>
        <td><?= $row['price'] ?></td>
        <td>
            <?php if($row['image'] && file_exists("../uploads/".$row['image'])): ?>
                <img src="../uploads/<?= $row['image'] ?>" width="50" alt="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </td>
        <td>
            <a href="editProduct.php?id=<?= $row['id'] ?>" style="margin-right:10px;">Edit</a>
            <a href="dashboard.php?page=products&delete=<?= $row['id'] ?>" 
               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- CSS identik si tabela për Users -->
<style>
table th, table td { padding: 12px; text-align: left; }
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



