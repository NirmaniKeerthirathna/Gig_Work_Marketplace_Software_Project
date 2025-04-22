let selectedAppId = null;
let selectedForm = null;

// Handle Withdraw button click
document.querySelectorAll('.withdraw-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    selectedAppId = btn.getAttribute('data-app-id');
    selectedForm = btn.closest('form'); // Store the correct form
    document.getElementById('confirmModal').classList.remove('hidden');
  });
});

// Confirm yes = submit form
document.getElementById('confirmYes').addEventListener('click', () => {
  if (selectedForm) {
    selectedForm.submit();
  }
});

// Cancel = close modal
document.getElementById('confirmNo').addEventListener('click', () => {
  document.getElementById('confirmModal').classList.add('hidden');
  selectedAppId = null;
  selectedForm = null;
});

// Show toast if redirected after withdrawal
const params = new URLSearchParams(window.location.search);
if (params.get('success') === 'withdrawn') {
  const toast = document.getElementById('toast');
  toast.textContent = "Application successfully withdrawn.";
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}

