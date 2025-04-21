document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showToast("Login successful");

            // Get return URL from query string (if any)
            const params = new URLSearchParams(window.location.search);
            const returnPage = params.get('return');

            setTimeout(() => {
                if (returnPage) {
                    // Redirect back to the original page
                    window.location.href = decodeURIComponent(returnPage);
                } else {
                    // Fallback redirect (e.g., homepage or explore page)
                    window.location.href = '../index/index.php';
                }
            }, 2000);
        } else {
            showToast(data.message || "Login failed");
        }
    })
    .catch(error => {
        console.error(error);
        showToast("An unexpected error occurred.");
    });
});

function showToast(message) {
    const toast = document.getElementById('toast-message');
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
