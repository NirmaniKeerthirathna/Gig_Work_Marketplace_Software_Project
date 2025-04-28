document.getElementById('login-form').addEventListener('submit', function(event) 
    {
    event.preventDefault();

    const log_in_form = event.target;
    const log_in_form_data = new FormData(log_in_form);

    fetch('login.php', {
        method: 'POST',
        body: log_in_form_data
        })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') 
            {
            show_toast_message("Login successful");

            const params = new URLSearchParams(window.location.search);
            const returnPage = params.get('return');

            setTimeout(() => {
                if (returnPage) 
                    {
                    window.location.href = decodeURIComponent(returnPage);
                    } 
                else 
                    {
                    window.location.href = '../index/index.php';
                    }
            }, 2000);
            } 
        else 
            {
            show_toast_message(data.message || "Login failed");
            }
        })
    .catch(error => {
        console.error(error);
        show_toast_message("An unexpected error occurred.");
    });
});

function show_toast_message(message) 
    {
    const toast = document.getElementById('toast-message');
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
        }, 3000);
    }