<?php
// Përfshij database e testimit
include __DIR__ . '/../database/db-connectionTest.php';

// Përfshij funksionet
include __DIR__ . '/../functions/register-functions.php';
include __DIR__ . '/../functions/login-functions.php';
include __DIR__ . '/../functions/cart-functions.php';
include __DIR__ . '/../functions/password-functions.php';
include __DIR__ . '/../functions/contact-functions.php';
include __DIR__ . '/../functions/order-functions.php';
include __DIR__ . '/../functions/funfact-functions.php';

// Përfshij funksionet admin nëse nevojiten
include __DIR__ . '/../admin/product_functions.php';
?>
