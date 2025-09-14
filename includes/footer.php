<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="<?php echo $base_url; ?>about.php">About Us</a></li>
                    <li><a href="<?php echo $base_url; ?>products.php">Our Services</a></li>
                    <li><a href="<?php echo $base_url; ?>contact.php">Privacy Policy</a></li>
                    <li><a href="<?php echo $base_url; ?>order.php">Order Now</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Get help</h4>
                <ul>
                    <li><a href="<?php echo $base_url; ?>faq.php">FAQ</a></li>
                    <li><a href="<?php echo $base_url; ?>shipping.php" target="_blank">Shipping</a></li>
                    <li><a href="<?php echo $base_url; ?>contact.php" target="_blank">Returns</a></li>
                    <li><a href="<?php echo $base_url; ?>payment.php">Payment Options</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Follow us</h4>
                <div class="social-links">
                    <ul>
                        <li><a href="https://www.facebook.com/" aria-label="Facebook" target="_blank"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="https://www.instagram.com/" aria-label="Instagram" target="_blank"><i class='bx bxl-instagram'></i></a></li>
                        <li><a href="https://www.twitter.com/" aria-label="Twitter" target="_blank"><i class='bx bxl-twitter'></i></a></li>
                        <li><a href="https://www.youtube.com/" aria-label="Youtube" target="_blank"><i class='bx bxl-youtube'></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</footer>
<div class="copyright">
        <p>&copy; <?php echo date('Y'); ?> Coffee Shop. All Rights Reserved.</p>
    </div>
    <script src="<?php echo $base_url; ?>assets/js/script.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/ajax_cookie_banner.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/feedback.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/search.js"></script>
    <?php if (basename($_SERVER['PHP_SELF']) === 'order.php'): ?>
        <script src="<?php echo $base_url; ?>assets/js/order_process.js"></script>
    <?php endif; ?>
</body>
</html>
