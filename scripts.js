// show gig worker related fields, if gig worker account type is selected
function show_account_type_fields(account_type) 
    {
    document.getElementById('gig_worker_fields').style.display = account_type === 'gig_worker' ? 'block' : 'none';
    }

document.getElementById('sign-up-form').addEventListener('submit', function(event) 
    {
    // prevent form submission without entering data    
    event.preventDefault(); 

    const form = event.target;
    const formData = new FormData(form);

    fetch('signup.php', 
        {
        method: 'POST',
        body: formData
        })
    .then(response => response.text())
    .then(data => {
        if (data.includes("successful")) 
            {
            showToast("Sign up successful");
            form.reset(); // clear the form
            document.getElementById('gig_worker_fields').style.display = 'none';
            } 
        else 
            {
            showToast("Error: " + data);
            }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast("Something went wrong");
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

