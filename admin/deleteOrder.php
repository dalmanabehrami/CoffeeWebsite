<?php
include '../database/db_connection.php';

// Siguro që kemi ID të vlefshme
if (!isset($_GET['id'])) {
    die("Order ID is missing.");
}

$id = intval($_GET['id']); 

// Fshi porosinë nga tabela orders
mysqli_query($conn, "DELETE FROM orders WHERE id=$id");

// Redirect brenda dashboard tek faqja e orders
header("Location: dashboard.php?page=orders");
exit();
