function addToCart(button) {
    const product = button.closest('.box');
    const data = {
        id: product.getAttribute('data-id'),
        name: product.getAttribute('data-name'),
        price: parseFloat(product.getAttribute('data-price')),
        quantity: 1,
        image: product.getAttribute('data-image')
    };

    fetch('admin/cart_actions.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === "success") {
            showMessage("Product added to cart!", "success");
        } else {
            showMessage(response.message || "Error adding product.", "error");
        }
    })
    .catch(err => {
        console.error('Gabim:', err);
        showMessage("Something went wrong!", "error");
    });
}

function removeFromCart(productId) {
    fetch('admin/delete_product_full.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            showMessage("Product deleted from cart!", "success");
            document.querySelector(`[data-id='${productId}']`)?.remove();
        } else {
            showMessage(response.message || "Error deleting product.", "error");
        }
    })
    .catch(error => {
        console.error('Gabim:', error);
        showMessage("Something went wrong!", "error");
    });
}

function deleteProduct(productId) {
    fetch('admin/delete_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            showMessage("Product deleted!", "success");
            document.querySelector(`[data-id='${productId}']`)?.remove();
        } else {
            showMessage(data.message, "error");
        }
    })
    .catch(err => {
        console.error(err);
        showMessage("Something went wrong!", "error");
    });
}

function deleteProductFromCartOnly(productId) {
    fetch('admin/delete_from_cart_only.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(data => {
        showMessage(data.message, data.status);
    })
    .catch(err => {
        console.error(err);
        showMessage("Something went wrong!", "error");
    });
}

function deleteFromCartItems(productId) {
    fetch('admin/delete_from_cart_items.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(data => {
        showMessage(data.message, data.status);

        // Vetëm nëse është sukses (do të thotë që ishte në cart), pastaj fshij prej DB
        if (data.status === "success") {
            fetch('admin/delete_from_cart_items_db.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: productId })
            })
            .then(res => res.json())
            .then(response => {
                // Mundesh me përdor këtë përgjigje nëse don
                console.log(response.message);
            });
        }
    })
    .catch(err => {
        console.error(err);
        showMessage("Something went wrong!", "error");
    });
}

function showMessage(msg, type) {
    let box = document.getElementById("message-box");
    if (!box) {
        box = document.createElement("div");
        box.id = "message-box";
        document.body.prepend(box);
    }
    box.className = type;
    box.textContent = msg;

    setTimeout(() => {
        box.remove();
    }, 3000);
}

document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get("search");

    if (searchQuery) {
        const products = document.querySelectorAll(".box");

        let found = false;
        products.forEach(product => {
            const name = product.getAttribute("data-name");
            if (name && name.toLowerCase().includes(searchQuery.toLowerCase())) {
                product.scrollIntoView({ behavior: "smooth", block: "center" });
                product.style.outline = "3px solid gold";
                product.style.borderRadius = "12px";
                product.style.transition = "outline 0.3s ease";

                setTimeout(() => {
                    product.style.outline = "none";
                }, 3000);

                found = true;
            }
        });

        if (!found) {
            console.log("Product not found.");
        }
    }
});