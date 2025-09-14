document.addEventListener("DOMContentLoaded", function () {
    const acceptBtn = document.getElementById("accept-cookies-btn");
    const rejectBtn = document.getElementById("reject-cookies-btn");
    const banner = document.getElementById("cookie-banner");

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function getCookieData() {
        const cookieValue = getCookie('user_cookies');
        if (!cookieValue) return null;
        try {
            return JSON.parse(decodeURIComponent(cookieValue));
        } catch (e) {
            return null;
        }
    }

    const cookieData = getCookieData();
    if (cookieData && cookieData.accepted) {
        document.body.style.backgroundColor = "#e8f5e9";
    } else if (cookieData && cookieData.accepted === false) {
        document.body.style.backgroundColor = "#ffebee";
    }

    function changeBannerBackground(color) {
        if (!banner) return;
        const originalBackground = banner.style.backgroundColor;
        banner.style.transition = "background-color 0.5s ease";
        banner.style.backgroundColor = color;
        setTimeout(() => {
            banner.style.backgroundColor = originalBackground || "rgba(34, 34, 34, 0.85)";
        }, 2000);
    }

    if (acceptBtn) {
        acceptBtn.addEventListener("click", function () {
            fetch("includes/set_cookie.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "accept_cookies=1"
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    changeBannerBackground("#28a745");
                    document.body.style.backgroundColor = "#e8f5e9";
                    setTimeout(() => {
                        if (banner) banner.style.display = "none";
                    }, 2500);
                }
            });
        });
    }

    if (rejectBtn) {
        rejectBtn.addEventListener("click", function () {
            fetch("includes/set_cookie.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "reject_cookies=1"
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    changeBannerBackground("#dc3545");
                    document.body.style.backgroundColor = "#ffebee";
                    setTimeout(() => {
                        if (banner) banner.style.display = "none";
                    }, 2500);
                }
            });
        });
    }
});
