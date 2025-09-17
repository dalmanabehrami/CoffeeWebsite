<section id="audio-player">
    <audio controls autoplay loop>
        <source src="assets/images/videoplayback.weba" type="audio/mpeg">
        video of coffee
    </audio>
</section>

<section class="home" id="home">
    <div class="home-text">
        <h1><i>Start your day with coffee</i></h1>
        <?php if (isset($_SESSION['user_name'])): ?>
            <?php if (isset($_SESSION['show_welcome']) && $_SESSION['show_welcome']): ?>
                <p class="welcome-message">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
            <?php else: ?>
                <p class="welcome-message">Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
            <?php endif; ?>
        <?php endif; ?>
        <div style="display: flex; justify-content: center;">
            <a href="products.php" class="btn">Shop Now</a>
        </div>
    </div>
    <div class="home-video">
        <video autoplay loop muted src="assets/images/coffee.mp4"></video>
    </div>
</section>
