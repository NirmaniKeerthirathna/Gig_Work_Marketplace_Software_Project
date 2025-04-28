//Show gig worker fields, if gig worker opens the page
function show_account_type_fields(account_type) 
    {
    const gigWorkerFields = document.getElementById('gig_worker_fields');
    const gigWorkerNameFields = document.getElementById('gig_worker_name_fields');
    const employerNameField = document.getElementById('employer_name_field');

    gigWorkerFields.style.display = account_type === 'gig_worker' ? 'block' : 'none';
    gigWorkerNameFields.style.display = account_type === 'gig_worker' ? 'block' : 'none';
    employerNameField.style.display = account_type === 'employer' ? 'block' : 'none';

    document.querySelector('input[name="first_name"]').required = account_type === 'gig_worker';
    document.querySelector('input[name="last_name"]').required = account_type === 'gig_worker';
    document.querySelector('input[name="employer_name"]').required = account_type === 'employer';
    }

//Save user details in the users and gig_worker tables
document.getElementById('sign-up-form').addEventListener('submit', function(event) 
    {    
    event.preventDefault(); 

    const sign_up_form = event.target;
    const sign_up_form_data = new FormData(sign_up_form);

    fetch('signup.php', 
        {
        method: 'POST',
        body: sign_up_form_data
        })
    .then(response => response.text())
    .then(data => {
        if (data.includes("successful")) 
            {
            show_toast_message("Sign up successful");
            sign_up_form.reset();
            document.getElementById('gig_worker_fields').style.display = 'none';
            } 
        else 
            {
            show_toast_message("Error: " + data);
            }
        })
    .catch(error => {
        console.error('Error:', error);
        show_toast_message("Something went wrong");
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