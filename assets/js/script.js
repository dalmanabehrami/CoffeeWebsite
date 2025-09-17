window.addEventListener('DOMContentLoaded', () => {
    const cart = document.querySelector('.cart');
    const user = document.querySelector('.user');
    const search = document.querySelector('.search-container');

    const cartIcon = document.querySelector('#cart-icon');
    const userIcon = document.querySelector('#user-icon');
    const searchIcon = document.querySelector('#search-icon');

    if (cartIcon && cart) {
        cartIcon.onclick = () => {
            cart.classList.toggle('active');
            if (user) user.classList.remove('active');
            if (search) search.classList.remove('active');
        };
    }

    if (userIcon && user) {
        userIcon.onclick = () => {
            user.classList.toggle('active');
            if (cart) cart.classList.remove('active');
            if (search) search.classList.remove('active');
        };
    }

    if (searchIcon && search) {
        searchIcon.onclick = () => {
            search.classList.toggle('active');
            if (cart) cart.classList.remove('active');
            if (user) user.classList.remove('active');
        };
    }
});
