<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
<<<<<<< HEAD
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/UEB25_CoffeeWebsite_/';
=======
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/CoffeeWebsite/';
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
?> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
    <link rel="icon" href="<?php echo $base_url; ?>assets/images/logo1.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        const base_url = "<?php echo $base_url; ?>";
    </script>
    <script src="<?php echo $base_url; ?>assets/js/script.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/search.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/feedback.js"></script>
</head>
<body>
    <header>
        <a href="<?php echo $base_url; ?>index.php" class="logo">
            <img src="<?php echo $base_url; ?>assets/images/logo1.png" alt="Logo" loading="lazy">
        </a>

        <ul class="navbar">
            <li><a href="<?php echo $base_url; ?>index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?php echo $base_url; ?>order.php" class="<?= $currentPage == 'order.php' ? 'active' : '' ?>">Order Now</a></li>
            <li><a href="<?php echo $base_url; ?>about.php" class="<?= $currentPage == 'about.php' ? 'active' : '' ?>">About</a></li>
            <li><a href="<?php echo $base_url; ?>products.php" class="<?= $currentPage == 'products.php' ? 'active' : '' ?>">Products</a></li>
            <li><a href="<?php echo $base_url; ?>contact.php" class="<?= $currentPage == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <li><a href="<?php echo $base_url; ?>admin/logout.php" class="<?= $currentPage == 'logout.php' ? 'active' : '' ?>">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo $base_url; ?>admin/login.php" class="<?= $currentPage == 'login.php' ? 'active' : '' ?>">Login</a></li>
            <?php endif; ?>
        </ul>
        <div class="header-icon">
            <a href="<?php echo $base_url; ?>order.php"><i class='bx bx-cart' id="cart-icon"></i></a>
            <i class='bx bx-search' id="search-icon"></i>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="<?php echo $base_url; ?>admin/logout.php"><i class='bx bx-log-out' id="user-icon"></i></a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>admin/login.php"><i class='bx bx-user' id="user-icon"></i></a>
            <?php endif; ?>
       </div>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Search product..." autocomplete="off">
            <div id="search-results"></div>
        </div>
        
    </header> 