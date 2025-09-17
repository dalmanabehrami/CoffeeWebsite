document.addEventListener("DOMContentLoaded", function () {
    const productSelect = document.getElementById("product");
    const addButton = document.getElementById("btn-add-to-cart");
    const quantityInput = document.getElementById("product-quantity");
    const messageBox = document.getElementById("message-box");
    const taxInfo = document.getElementById("tax-info");
    const orderForm = document.getElementById("order-form");
    const cartJsonInput = document.getElementById("cart-json");

    // Show tax info when product is selected
    if (productSelect && taxInfo) {
        productSelect.addEventListener("change", () => {
            if (productSelect.value) taxInfo.style.display = "block";
            else taxInfo.style.display = "none";

            const selectedOption = productSelect.selectedOptions[0];
            const basePrice = parseFloat(selectedOption.getAttribute("data-price")) || 0;
            const tax = basePrice * 0.10;
            const totalWithTax = (basePrice + tax).toFixed(2);

            messageBox.innerHTML = `
                <div style="background:#fff3cd; padding:10px; border-radius:6px; font-weight:bold; color:#856404; margin-top:10px;">
                    Tax included: 10% – Final price: $${totalWithTax}
                </div>
            `;
        });
    }

    // Add product to cart
    if (addButton) {
        addButton.addEventListener('click', function () {
            if (addButton.disabled) return;
            addButton.disabled = true;

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productId = selectedOption.value;
            const productName = selectedOption.getAttribute('data-name');
            let productPrice = parseFloat(selectedOption.getAttribute('data-price'));
            const productImage = selectedOption.getAttribute('data-image') || 'assets/images/default-product.png';
            let productQuantity = parseInt(quantityInput.value);
            if (isNaN(productQuantity) || productQuantity < 1) productQuantity = 1;

            const tax = productPrice * 0.10;
            productPrice += tax;

            fetch('admin/cart_actions.php?action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: productQuantity,
                    image: productImage
                })
            })
            .then(res => res.json())
            .then(data => {
                addButton.disabled = false;
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    document.getElementById("cart-items").innerHTML = data.cart_html;
                    calculateTotal();
                } else {
                    showMessage(data.message, "error");
                }
            })
            .catch(err => {
                addButton.disabled = false;
                console.error('Error:', err);
                showMessage("Could not add product to cart.", "error");
            });
        });
    }

    // Submit order form
    if (orderForm) {
        orderForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // Fill hidden cart JSON field
            const cartItems = [];
            document.querySelectorAll('.cart-item').forEach(item => {
                const index = item.dataset.index;
                const name = item.querySelector('.cart-item-name').textContent;
                const price = parseFloat(item.querySelector('.cart-item-price').textContent.replace('$',''));
                const quantity = parseInt(item.querySelector('.cart-item-quantity').value) || 1;
                const img = item.querySelector('img').src;
                cartItems.push({ index, name, price, quantity, image: img });
            });
            cartJsonInput.value = JSON.stringify(cartItems);

            const formData = new FormData(orderForm);
            showOrderMessage('<span class="spinner"></span> Sending order...', "info");

            fetch("admin/process_order.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                showOrderMessage(data.message, data.status);

                if (data.status === "success") {
                    orderForm.reset();
                    document.getElementById("cart-items").innerHTML = "<p>Your cart is empty.</p>";
                    document.getElementById("total-price").textContent = "Total price: $0.00";
                }
            })
            .catch(err => {
                console.error(err);
                showOrderMessage("Failed to place order. Please try again.", "error");
            });
        });
    }

    // Utility functions
    window.calculateTotal = function () {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            let price = parseFloat(item.querySelector('.cart-item-price').textContent.replace('$','')) || 0;
            let quantity = parseInt(item.querySelector('.cart-item-quantity').value) || 1;
            total += price * quantity;
        });
        document.getElementById('total-price').textContent = 'Total price: $' + total.toFixed(2);
    }

    window.updateQuantity = function (index, newQuantity) {
        fetch('admin/cart_actions.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ index: index, quantity: parseInt(newQuantity) })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById("cart-items").innerHTML = data.cart_html;
                calculateTotal();
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Could not update quantity.');
        });
    }

    window.removeFromCart = function (index) {
        fetch('admin/cart_actions.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ index: index })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                showMessage("Product removed!", "success");
                document.getElementById("cart-items").innerHTML = data.cart_html;
                calculateTotal();
            } else {
                showMessage(data.message, "error");
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showMessage("Could not remove product.", "error");
        });
    }

    function showMessage(msg, type = "success") {
        const box = document.getElementById("message-box") || document.createElement('div');
        box.id = "message-box";
        box.className = type;
        box.textContent = msg;
        document.querySelector(".container").prepend(box);

        setTimeout(() => box.remove(), 3000);
    }

    function showOrderMessage(msg, type = "success") {
        let bgColor, textColor;
        if (type === "success") { bgColor = "#d1e7dd"; textColor = "#0f5132"; }
        else if (type === "error") { bgColor = "#f8d7da"; textColor = "#842029"; }
        else { bgColor = "#cff4fc"; textColor = "#055160"; }

        let messageBox = document.getElementById("message-box");
        if (!messageBox) {
            messageBox = document.createElement("div");
            messageBox.id = "message-box";
            document.querySelector(".container").prepend(messageBox);
        }

        messageBox.innerHTML = `
            <div style="background:${bgColor}; padding:12px; border-radius:6px; color:${textColor}; font-weight:bold; margin-top:10px; box-shadow:0 0 8px rgba(0,0,0,0.1); text-align:center;">
                ${msg}
            </div>
        `;
        if (type !== "info") setTimeout(() => messageBox.innerHTML = "", 4000);
    }
});
