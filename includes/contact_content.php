<section id="contact">
    <div class="PlaceOfCoffee">
        <video autoplay loop muted src="assets/images/PlaceOfCoffee.mp4"></video>
    </div>

    <form id="contact-form" method="POST" action="/UEB25_CoffeeWebsite_/admin/contact_email.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required placeholder="Enter your name">

        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email address">

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required placeholder="Message subject">

        <label for="message">Message:</label>
        <textarea id="message" name="message" required placeholder="Write your message"></textarea>

        <label for="age">Age (optional):</label>
        <input type="number" id="age" name="age" min="1" max="99">

        <label for="phone">Phone (optional):</label>
        <input type="text" id="phone" name="phone" placeholder="Phone number">

        <button type="submit">Send Message</button>
    </form>

    <address>
        <ul>
            <li>✉️: <a href="mailto:info@shembull.com">coffeeshopborcelle@gmail.com</a></li>
            <li>📞: +123 456 789</li>
        </ul>
    </address>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <address style="text-align: center; margin-top: 40px;">
            <form id="feedback-form" style="display: inline-block;">
                <textarea name="custom_message" rows="4" cols="70" placeholder="Give your feedback for our shop!" style="color: black;" required></textarea><br>
                <button type="submit" class="btn btn-primary">Send your feedback</button>
            </form>
            <div id="feedback-response" style="margin-top: 10px; text-align: center;"></div>
        </address>
    <?php else: ?>
        <address style="text-align: center; margin-top: 20px;">
            <p style="color: red; font-weight: bold;">
                You must be logged in to send feedback.
            </p>
            <a href="admin/login.php" class="btn btn-outline-primary">Login here</a>
        </address>
    <?php endif; ?>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/contact_form.js"></script>