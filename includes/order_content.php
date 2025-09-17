<link rel="stylesheet" href="assets/css/order.css">

<section id="order-now">
    <div class="order-image"></div>

    <div style="position: absolute; top: 120px; left: 50px; z-index: 10;">
        <?php include 'admin/coffee_api.php'; ?>
    </div>

    <div class="container">
        <h1>Order Now!</h1>

        <form id="order-form">
<<<<<<< HEAD
            <!-- Hidden field to send cart JSON -->
            <input type="hidden" name="cart" id="cart-json">

=======
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
            <div id="message-box" style="margin: 10px auto; max-width: 480px;"></div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter your address" required>
            </div>
            <div class="form-group">
                <label for="payment-method">Payment Method</label>
                <select id="payment-method" name="payment-method" required>
                    <option value="" disabled selected>Select payment method</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>

            <h2>Add Product to Cart</h2>
            <div class="form-group">
                <label for="product">Product</label>
                <select id="product" name="product" required>
                    <option value="" disabled selected>Select a product</option>
                    <?php
                    $sql = "SELECT * FROM products";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '" data-price="' . $row['price'] . '" data-name="' . htmlspecialchars($row['name']) . '" data-image="' . htmlspecialchars($row['image'] ?? 'assets/img/default-product.png') . '">' . htmlspecialchars($row['name']) . ' - $' . number_format($row['price'], 2) . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No products available</option>';
                    }
                    ?>
                </select>
                <div id="tax-info" style="display: none; color: #8B6B3E; font-size: 0.9rem; margin-top: 8px;">
                    Note: Price includes 10% tax.
                </div>
            </div>
            <div class="form-group">
                <label for="product-quantity">Quantity</label>
                <input type="number" id="product-quantity" name="product-quantity" value="1" min="1" required>
            </div>
            <div style="margin-bottom: 30px;">
                <button type="button" id="btn-add-to-cart">Add Selected Product to Cart</button>
            </div>

            <h2>Your Cart</h2>
            <div id="cart-items">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $index => $item): 
                        $price = floatval($item['price']);
                        $quantity = $item['quantity'] ?? 1;
                    ?>
                    <div class="cart-item" data-index="<?= $index ?>">
                        <div class="cart-item-info">
                            <?php
                                $imagePath = $item['image'];
                                if (!str_starts_with($imagePath, 'assets/images/') && !str_starts_with($imagePath, 'http')) {
                                    $imagePath = 'assets/images/' . ltrim($imagePath, '/');
                                }
                            ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="60" height="60">
                            <div>
                                <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="cart-item-price">$<?= number_format($price, 2) ?></div>
                            </div>
                        </div>
                        <input type="number" min="1" class="cart-item-quantity" value="<?= $quantity ?>" onchange="updateQuantity(<?= $index ?>, this.value)">
                        <button type="button" class="btn-remove" onclick="removeFromCart(<?= $index ?>)">Remove</button>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <p id="total-price">Total price: $<?= number_format($total, 2) ?></p>

            <div class="form-group" style="margin-top: 30px;">
                <label>
                    <input type="checkbox" id="accept-terms" name="accept-terms" required>
                    I accept the terms and conditions
                </label>
                <button type="submit">Place Order</button>
            </div>
        </form>
    </div>
<<<<<<< HEAD
</section>
=======
</section>
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
