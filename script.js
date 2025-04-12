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
            // Optionally redirect:
            // window.location.href = "dashboard.html";
        } else {
            showToast("Error: " + data.message);
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

  