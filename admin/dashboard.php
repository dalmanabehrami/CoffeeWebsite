<<<<<<< HEAD
<?php 
session_start();
include '../includes/header.php';
include '../database/db_connection.php';

// Kontrolli: vetëm admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../no-access.php");
    exit();
}

// Merr statistikat nga DB
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$cart_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM cart_items"))['total'];

// Cila faqe të shfaqet
$page = $_GET['page'] ?? 'stats';
?>

<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php?page=stats" <?= $page==='stats'?'class="active"':'' ?>>Dashboard</a></li>
            <li><a href="dashboard.php?page=users" <?= $page==='users'?'class="active"':'' ?>>Users</a></li>
            <li><a href="dashboard.php?page=products" <?= $page==='products'?'class="active"':'' ?>>Products</a></li>
            <li><a href="dashboard.php?page=cart_items" <?= $page==='cart_items'?'class="active"':'' ?>>Cart Items</a></li>
        </ul>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <?php 
        if ($page === 'stats') { 
        ?>
            <h2>Dashboard Statistics</h2>
            <div class="stats-cards">
                <div class="stat-card users-card">
                    <h3>Users</h3>
                    <p><?= $user_count ?></p>
                </div>
                <div class="stat-card products-card">
                    <h3>Products</h3>
                    <p><?= $product_count ?></p>
                </div>
                <div class="stat-card cart-card">
                    <h3>Cart Items</h3>
                    <p><?= $cart_count ?></p>
                </div>
            </div>
        <?php
        } elseif ($page === 'users') {
            include 'users.php';
        } elseif ($page === 'products') {
            include 'products.php';
        } elseif ($page === 'cart_items') {
            include 'cart_items.php'; // file ku do shfaqen te dhënat e cart
        } else {
            echo "<h3>Page not found!</h3>";
        }
        ?>
        
    </div>
</div>

<!-- CSS Modern dhe Responsive -->
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f6f9;
}

.admin-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 220px;
    background-color: #2c3e50;
    color: #fff;
    padding: 20px;
}
.sidebar h2 { text-align: center; margin-bottom: 30px; }
.sidebar ul { list-style: none; padding: 0; }
.sidebar ul li { margin-bottom: 15px; }
.sidebar ul li a {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 10px 12px;
    border-radius: 6px;
    transition: 0.3s;
}
.sidebar ul li a:hover, .sidebar ul li a.active {
    background-color: #34495e;
}
.logout-btn {
    display: block;
    margin-top: 30px;
    text-align: center;
    background-color: #e74c3c;
    padding: 10px;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
}

/* Main Content */
.main-content {
    flex: 1;
    padding: 30px;
    background-color: #ecf0f1;
}

/* Stats Cards */
.stats-cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}
.stat-card {
    flex: 1 1 200px;
    background-color: #3498db;
    color: #fff;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
.stat-card h3 { margin: 0; font-size: 1.2rem; }
.stat-card p { font-size: 2rem; margin-top: 10px; font-weight: bold; }
.users-card { background-color: #1abc9c; }
.products-card { background-color: #9b59b6; }
.cart-card { background-color: #e67e22; }

/* Responsive */
@media (max-width: 768px) {
    .admin-container { flex-direction: column; }
    .sidebar { width: 100%; min-height: auto; display: flex; justify-content: space-between; align-items: center; }
    .sidebar ul { display: flex; gap: 10px; }
    .stats-cards { flex-direction: column; }
}
</style>

<?php include '../includes/footer.php'; ?>

=======
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
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
