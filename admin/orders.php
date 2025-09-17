<?php
include '../database/db_connection.php';

// Delete order
if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM orders WHERE id=$del_id");
    header("Location: dashboard.php?page=orders"); // redirect brenda dashboard
    exit();
}

// Merr të gjitha porositë
$result = mysqli_query($conn, "
    SELECT o.id, u.name as user_name, p.name as product_name, o.quantity, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN products p ON o.product_id = p.id
    ORDER BY o.id DESC
");
?>

<h2>Manage Orders</h2>

<table border="1" width="100%" style="margin-top:15px; border-collapse: collapse;">
    <tr style="background-color:#3498db; color:#fff;">
        <th>ID</th>
        <th>User</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr style="background-color:#fff; border-bottom:1px solid #ddd;">
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['user_name'], ENT_QUOTES) ?></td>
        <td><?= htmlspecialchars($row['product_name'], ENT_QUOTES) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
            <a href="editOrder.php?id=<?= $row['id'] ?>" style="margin-right:10px;">Edit</a>
            <a href="dashboard.php?page=orders&delete=<?= $row['id'] ?>" 
               onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- CSS identik si tabela për Users/Products -->
<style>
table th, table td { padding: 12px; text-align: left; }
table tr:hover { background-color: #f1f1f1; }
a { text-decoration: none; color: #2980b9; }
a:hover { text-decoration: underline; }
</style>
