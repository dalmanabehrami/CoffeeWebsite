<?php
$showBanner = true;

if (isset($_COOKIE['user_cookies'])) {
    $cookieData = json_decode($_COOKIE['user_cookies'], true);
    if ($cookieData && isset($cookieData['accepted']) && $cookieData['accepted'] === true) {
        $showBanner = false; 
    }
}
?>

<?php if ($showBanner): ?>
<div id="cookie-banner" style="
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: rgba(34, 34, 34, 0.85);
    color: white;
    text-align: center;
    padding: 15px;
    z-index: 9999;
">
    <p>We use cookies to ensure you get the best experience on our website.</p>
    <div class="d-flex justify-content-center gap-2 mt-2">
        <button id="accept-cookies-btn" class="btn btn-success">Accept All</button>
        <button id="reject-cookies-btn" class="btn btn-danger">Reject All</button>
    </div>
</div>
<?php endif; ?>
