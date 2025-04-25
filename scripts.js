let currentSection = 0;
const sections = document.querySelectorAll('.section');
const sectionTitles = document.querySelectorAll('#sections li');

function nextSection() {
    sections[currentSection].classList.remove('active');
    sectionTitles[currentSection].classList.remove('active');
    currentSection++;
    if (currentSection < sections.length) {
        sections[currentSection].classList.add('active');
        sectionTitles[currentSection].classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    sections[currentSection].classList.add('active');
    sectionTitles[currentSection].classList.add('active');
});

document.getElementById('taskForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('post_job.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showToast(data.message, data.status === 'error');
    
        if (data.status === 'success') {
            document.getElementById('taskForm').reset();
        } else if (data.message.includes("logged in")) {
            // Redirect after showing toast
            setTimeout(() => {
                window.location.href = '../log_in/login.html?return=post_job';
            }, 3000); // wait 3 seconds before redirect
        }
    })    
    .catch(error => {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.');
    });
});

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show';

    // Set color based on type
    toast.style.backgroundColor = isError ? '#dc3545' : '#28a745'; // red for error, green for success

    setTimeout(() => {
        toast.className = toast.className.replace('show', '');
    }, 3000);
}