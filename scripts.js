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
    .then(response => response.text())
    .then(data => {
        showToast('Task posted successfully!');
        // Optionally clear form or reset
        document.getElementById('taskForm').reset();
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error posting task.');
    });
});

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show';
    setTimeout(() => {
        toast.className = toast.className.replace('show', '');
    }, 3000);
}