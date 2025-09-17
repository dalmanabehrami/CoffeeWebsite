<?php
include '../database/db_connection.php';

// Delete user
if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$del_id");
    header("Location: dashboard.php?page=users");
    exit();
}

// Merr të gjithë users
$result = mysqli_query($conn, "SELECT id, name, email, password, role FROM users ORDER BY id DESC");
?>

<h2>Manage Users</h2>
<a href="addUser.php" class="btn">+ Add User</a>

<table border="1" width="100%" style="margin-top:15px; border-collapse: collapse; table-layout: fixed;">
    <tr style="background-color:#3498db; color:#fff;">
        <th style="width:5%;">ID</th>
        <th style="width:20%;">Name</th>
        <th style="width:25%;">Email</th>
        <th style="width:10%;">Password</th>
        <th style="width:15%;">Role</th>
        <th style="width:25%;">Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr style="background-color:#fff; border-bottom:1px solid #ddd;">
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
        <td><?= htmlspecialchars($row['email'], ENT_QUOTES) ?></td>
        <td><?= str_repeat('*', 8) ?></td> <!-- Password i fshehur -->
        <td><?= htmlspecialchars($row['role'], ENT_QUOTES) ?></td>
        <td>
            <a href="editUser.php?id=<?= $row['id'] ?>" style="margin-right:10px;">Edit</a>
            <a href="dashboard.php?page=users&delete=<?= $row['id'] ?>" 
               onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<style>
table th, table td { padding: 8px; text-align: left; word-wrap: break-word; }
table tr:hover { background-color: #f1f1f1; }
.btn {
    display: inline-block;
    padding: 6px 12px;
    background-color: #2ecc71;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 10px;
    font-size: 14px;
}
.btn:hover { background-color: #27ae60; }
</style>
