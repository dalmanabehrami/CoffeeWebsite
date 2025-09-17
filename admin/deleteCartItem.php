<?php
session_start();
include '../database/db_connection.php';

// Siguro që admin është kyqur
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../no-access.php");
    exit();
}

// Kontrollo ID
if (!isset($_GET['id'])) {
    die("Cart Item ID is missing.");
}

$id = intval($_GET['id']);

// Merr emrin e imazhit për fshirje
$result = mysqli_query($conn, "SELECT image FROM cart_items WHERE id=$id");
if ($result && mysqli_num_rows($result) > 0) {
    $item = mysqli_fetch_assoc($result);
    if($item['image'] && file_exists("../uploads/".$item['image'])){
        unlink("../uploads/".$item['image']);
    }
}

// Fshi nga databaza
mysqli_query($conn, "DELETE FROM cart_items WHERE id=$id");

// Redirect pas fshirjes
header("Location: dashboard.php?page=cart_items");
exit();
