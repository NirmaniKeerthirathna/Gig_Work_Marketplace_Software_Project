let selectedAppId = null;
let selectedForm = null;

document.querySelectorAll('.withdraw-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    selectedAppId = btn.getAttribute('data-app-id');
    selectedForm = btn.closest('form'); 
    document.getElementById('confirmModal').classList.remove('hidden');
  });
});

document.getElementById('confirmYes').addEventListener('click', () => {
  if (selectedForm) 
    {
    selectedForm.submit();
    }
});

document.getElementById('confirmNo').addEventListener('click', () => {
  document.getElementById('confirmModal').classList.add('hidden');
  selectedAppId = null;
  selectedForm = null;
});

const params = new URLSearchParams(window.location.search);
if (params.get('success') === 'withdrawn') 
  {
  const toast = document.getElementById('toast');
  toast.textContent = "Application successfully withdrawn.";
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
  }