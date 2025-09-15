<?php
include 'includes/header.php';

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: admin/login.php");
    exit();
}
?>

<div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
    
    <div class="dashboard-links">
        <a href="admin/products.php" class="btn">Shop Now</a>
        <a href="admin/logout.php" class="btn">Logout</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>