document.addEventListener("DOMContentLoaded", () => {
    const searchIcon = document.getElementById("search-icon");
    const searchContainer = document.querySelector(".search-container");
    const searchInput = document.getElementById("search-input");
    const searchResults = document.getElementById("search-results");

    if (searchIcon && searchContainer && searchInput && searchResults) {
        searchIcon.addEventListener("click", () => {
            searchContainer.classList.toggle("active");
            if (searchContainer.classList.contains("active")) {
                searchInput.focus();
            } else {
                searchInput.value = "";
                searchResults.innerHTML = "";
            }
        });

        searchInput.addEventListener("keyup", function () {
            const query = this.value.trim();
            if (query.length === 0) {
                searchResults.innerHTML = "";
                return;
            }

            fetch("/UEB25_CoffeeWebsite_/admin/search-products.php?q=" + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = "";
                    if (data.length === 0) {
                        searchResults.innerHTML = "<div>No products found.</div>";
                    } else {
                        data.forEach(product => {
                            const item = document.createElement("div");
                            item.innerHTML = `
                            <a href="/UEB25_CoffeeWebsite_/products.php?search=${encodeURIComponent(product.name)}" style="display: flex; justify-content: space-between; align-items: center; padding: 6px 12px; background-color: #f9f9f9; border-radius: 6px;">
                                <span style="font-weight: 500; color: #333;">${product.name}</span>
                                <span style="font-size: 0.9rem; color: #8B6B3E;">${product.price}€</span>
                            </a>
                            `;
                            item.style.padding = "5px 0";
                            searchResults.appendChild(item);
                        });
                    }
                })
                .catch(() => {
                    searchResults.innerHTML = "<div>Error fetching results.</div>";
                });
        });
    }
});