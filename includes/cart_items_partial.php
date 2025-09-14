<?php if (!empty($_SESSION['cart'])): ?>
    <?php foreach ($_SESSION['cart'] as $index => $item): 
        $price = floatval($item['price']);
        $quantity = $item['quantity'] ?? 1;
        $imagePath = $item['image'];
        if (!str_starts_with($imagePath, 'assets/images/') && !str_starts_with($imagePath, 'http')) {
            $imagePath = 'assets/images/' . ltrim($imagePath, '/');
        }
    ?>
    <div class="cart-item" data-index="<?= $index ?>">
        <div class="cart-item-info">
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