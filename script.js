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
        
            // Check if we were redirected here with a return path
            const params = new URLSearchParams(window.location.search);
            const returnPage = params.get('return');
        
            setTimeout(() => {
                if (returnPage === 'post_job') {
                    window.location.href = '../post_job/post_job.html';
                } else {
                    window.location.href = 'dashboard.html'; // or some default
                }
            }, 2000);
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

  