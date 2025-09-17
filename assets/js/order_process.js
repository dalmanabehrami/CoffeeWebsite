document.addEventListener("DOMContentLoaded", function () {
    const productSelect = document.getElementById("product");
    const addButton = document.getElementById("btn-add-to-cart");
    const quantityInput = document.getElementById("product-quantity");
    const messageBox = document.getElementById("message-box");
    const taxInfo = document.getElementById("tax-info");
    const orderForm = document.getElementById("order-form");

    // Array për të mbajtur produktet në form JSON
    let cartArray = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCartUI() {
        const cartContainer = document.getElementById("cart-items");
        if (!cartContainer) return;

        if (cartArray.length === 0) {
            cartContainer.innerHTML = "<p>Your cart is empty.</p>";
        } else {
            cartContainer.innerHTML = "";
            cartArray.forEach((item, index) => {
                const itemDiv = document.createElement("div");
                itemDiv.className = "cart-item";
                itemDiv.dataset.index = index;
                const imgPath = item.image.startsWith('assets/images/') || item.image.startsWith('http') 
                                ? item.image 
                                : 'assets/images/' + item.image;

                itemDiv.innerHTML = `
                    <div class="cart-item-info">
                        <img src="${imgPath}" alt="${item.name}" width="60" height="60">
                        <div>
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                        </div>
                    </div>
                    <input type="number" min="1" class="cart-item-quantity" value="${item.quantity}" onchange="updateQuantity(${index}, this.value)">
                    <button type="button" class="btn-remove" onclick="removeFromCart(${index})">Remove</button>
                `;
                cartContainer.appendChild(itemDiv);
            });
        }

        calculateTotal();
        localStorage.setItem('cart', JSON.stringify(cartArray));
    }

    if (productSelect && taxInfo) {
        productSelect.addEventListener("change", () => {
            if (productSelect.value) {
                taxInfo.style.display = "block";
            } else {
                taxInfo.style.display = "none";
            }

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

    if (addButton) {
        addButton.addEventListener('click', function () {
            if (addButton.disabled) return;
            addButton.disabled = true;

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productId = selectedOption.value;
            const productName = selectedOption.getAttribute('data-name');
            let productPrice = parseFloat(selectedOption.getAttribute('data-price'));
            const productImage = selectedOption.getAttribute('data-image') || '/assets/images/products/default-product.png';
            let productQuantity = parseInt(quantityInput.value);

            if (isNaN(productQuantity) || productQuantity < 1) {
                productQuantity = 1;
            }

            const tax = productPrice * 0.10;
            productPrice += tax;

            // Shto produktin në array
            const existingIndex = cartArray.findIndex(item => item.id == productId);
            if (existingIndex >= 0) {
                cartArray[existingIndex].quantity += productQuantity;
            } else {
                cartArray.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: productQuantity,
                    image: productImage
                });
            }

            updateCartUI();
            addButton.disabled = false;
            showMessage("Product added to cart!", "success");
        });
    }

    if (orderForm) {
        // krijo hidden input për cart JSON
        let cartInput = document.createElement("input");
        cartInput.type = "hidden";
        cartInput.name = "cart-json";
        cartInput.id = "cart-json";
        orderForm.appendChild(cartInput);

        orderForm.addEventListener("submit", function (e) {
            e.preventDefault();

            cartInput.value = JSON.stringify(cartArray);
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
                    cartArray = [];
                    updateCartUI();
                    document.getElementById("total-price").textContent = "Total price: $0.00";
                }
            })
            .catch(err => {
                console.error(err);
                showOrderMessage("Failed to place order. Please try again.", "error");
            });
        });
    }

    window.calculateTotal = function () {
        let total = 0;
        cartArray.forEach(item => {
            total += item.price * item.quantity;
        });
        document.getElementById('total-price').textContent = 'Total price: $' + total.toFixed(2);
    }

    updateCartUI(); // ngarkon cart kur rifreskohet faqja
});

window.updateQuantity = function (index, newQuantity) {
    const qty = parseInt(newQuantity) || 1;
    const cartArray = JSON.parse(localStorage.getItem('cart')) || [];
    cartArray[index].quantity = qty;
    localStorage.setItem('cart', JSON.stringify(cartArray));
    document.querySelectorAll('.cart-item-quantity')[index].value = qty;
    document.querySelector('#total-price').textContent = 'Total price: $' + cartArray.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
}

window.removeFromCart = function (index) {
    let cartArray = JSON.parse(localStorage.getItem('cart')) || [];
    cartArray.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cartArray));
    const cartContainer = document.getElementById("cart-items");
    if (cartContainer) {
        cartContainer.innerHTML = "";
        cartArray.forEach((item, i) => {
            const itemDiv = document.createElement("div");
            itemDiv.className = "cart-item";
            itemDiv.dataset.index = i;
            itemDiv.innerHTML = `
                <div class="cart-item-info">
                    <img src="${item.image}" alt="${item.name}" width="60" height="60">
                    <div>
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                    </div>
                </div>
                <input type="number" min="1" class="cart-item-quantity" value="${item.quantity}" onchange="updateQuantity(${i}, this.value)">
                <button type="button" class="btn-remove" onclick="removeFromCart(${i})">Remove</button>
            `;
            cartContainer.appendChild(itemDiv);
        });
    }
    document.querySelector('#total-price').textContent = 'Total price: $' + cartArray.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
    showMessage("Product removed!", "success");
}

function showMessage(msg, type = "success") {
    const box = document.getElementById("message-box") || document.createElement('div');
    box.id = "message-box";
    box.className = type;
    box.textContent = msg;
    document.querySelector(".container").prepend(box);

    setTimeout(() => {
        box.remove();
    }, 3000);
}

function showOrderMessage(msg, type = "success") {
    let bgColor, textColor;

    if (type === "success") {
        bgColor = "#d1e7dd";
        textColor = "#0f5132";
    } else if (type === "error") {
        bgColor = "#f8d7da";
        textColor = "#842029";
    } else {
        bgColor = "#cff4fc";
        textColor = "#055160";
    }

    let messageBox = document.getElementById("message-box");
    if (!messageBox) {
        messageBox = document.createElement("div");
        messageBox.id = "message-box";
        document.querySelector(".container").prepend(messageBox);
    }

    messageBox.innerHTML = `
        <div style="background:${bgColor}; padding:12px; border-radius:6px; color:${textColor}; font-weight:bold; margin-top:10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); text-align:center;">
            ${msg}
        </div>
    `;

    if (type !== "info") {
        setTimeout(() => {
            messageBox.innerHTML = "";
        }, 4000);
    }
}
