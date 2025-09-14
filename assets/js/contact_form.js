$(document).ready(function () {
    $('#contact-form').on('submit', function (e) {
        e.preventDefault(); // prevent page refresh

        const formData = $(this).serialize();
        const $form = $(this);

        // Remove old messages
        $form.find('.response-msg').remove();

        // Show "sending" message
        const $loading = $('<p class="response-msg" style="color:blue;">📨 Sending message...</p>');
        $form.append($loading);

        $.ajax({
            type: 'POST',
            url: '/UEB25_CoffeeWebsite_/admin/contact_email.php',
            data: formData,
            dataType: 'json',
            success: function (response) {
                $loading.remove();

                const msgColor = response.success ? 'green' : 'red';
                const icon = response.success ? '✅' : '❌';
                const $msg = $('<p class="response-msg" style="color:' + msgColor + ';">' + icon + ' ' + response.message + '</p>');
                $form.append($msg);

                if (response.success) {
                    $form[0].reset();
                }
            },
            error: function () {
                $loading.remove();
                const $errorMsg = $('<p class="response-msg" style="color:red;">⚠️ An error occurred while sending your message.</p>');
                $form.append($errorMsg);
            }
        });
    });
});