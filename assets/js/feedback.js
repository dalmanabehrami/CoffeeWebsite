if (!window.feedbackFormScriptInitialized) {
    window.feedbackFormScriptInitialized = true;

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("feedback-form");
        const responseBox = document.getElementById("feedback-response");

        if (!form || !responseBox) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const submitButton = form.querySelector("button[type='submit']");
            if (submitButton.disabled) return;

            submitButton.disabled = true;

            responseBox.innerHTML = "<em>Sending...</em> <span class='spinner-border spinner-border-sm'></span>";
            responseBox.style.color = "blue";

            const formData = new FormData(form);

            fetch("admin/feedback-handler.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                responseBox.textContent = data.message;
                responseBox.style.color = data.success ? "green" : "red";
                if (data.success) form.reset();
            })
            .catch(() => {
                responseBox.textContent = "Something went wrong while sending feedback.";
                responseBox.style.color = "red";
            })
            .finally(() => {
                submitButton.disabled = false;
            });
        });
    });
}