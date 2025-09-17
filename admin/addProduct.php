<?php
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        $target_dir = "../uploads/";
        $image = time().'_'.basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir.$image);
    }

    if(!$name || !$description || $price <= 0){
        $error = "Please fill all fields correctly!";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $image);
        if(mysqli_stmt_execute($stmt)){
            header("Location: dashboard.php?page=products");
            exit();
        } else {
            $error = "Error: ".mysqli_error($conn);
        }
    }
}
?>

<h2>Add New Product</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<form action="addProduct.php" method="POST" enctype="multipart/form-data">
    <p>
        <label>Name:</label><br>
        <input type="text" name="name" required>
    </p>
    <p>
        <label>Description:</label><br>
        <textarea name="description" required></textarea>
    </p>
    <p>
        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" required>
    </p>
    <p>
        <label>Image:</label><br>
        <input type="file" name="image" accept="image/*">
    </p>
    <button type="submit">Add Product</button>
</form>

<style>
form input, form select, form textarea { 
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


