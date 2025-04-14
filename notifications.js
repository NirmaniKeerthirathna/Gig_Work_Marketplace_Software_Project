document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('notifBtn');
    const modal = document.getElementById('notifModal');
    const closeBtn = document.getElementById('closeModal');
  
    if (!btn) return;
  
    btn.addEventListener('click', fetchNotifications);
  
    function fetchNotifications() {
      fetch('../notifications/get_notifications.php')
        .then(res => res.json())
        .then(data => {
          const list = document.getElementById('notifList');
          list.innerHTML = '';
  
          let unread = 0;
  
          data.forEach(n => {
            if (!n.is_read) unread++;
  
            const li = document.createElement('li');
            li.classList.add('notif-item');
            li.dataset.id = n.id;
            li.dataset.message = n.message;
            li.dataset.title = n.title;
  
            li.innerHTML = `
              <strong>${n.title}</strong>
              <p>${n.message}</p>
              <small>${n.created_at}</small>
              ${!n.is_read ? `<span class="unread-dot"></span>` : ''}
            `;
  
            li.addEventListener('click', () => {
              openModal(n.title, n.message);
              markRead(n.id);
            });
  
            list.appendChild(li);

            li.addEventListener('click', () => {
                if (n.message_id) {
                  window.location.href = `../message/view_message.php?id=${n.message_id}`;
                } else {
                  openModal(n.title, n.message);
                  markRead(n.id);
                }
              });
              
          });
  
          document.getElementById('notifCount').textContent = unread;
          document.getElementById('notifDropdown').classList.toggle('hidden');
        });
    }
  
    function markRead(id) {
      const formData = new FormData();
      formData.append('notification_id', id);
  
      fetch('../notifications/mark_notification_read.php', {
        method: 'POST',
        body: formData
      }).then(() => fetchNotifications());
    }
  
    function openModal(title, message) {
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalMessage').textContent = message;
      modal.classList.remove('hidden');
    }
  
    // Close modal
    closeBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });
  
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  });
  
  // Dropdown outside click
  document.addEventListener('click', (e) => {
    const notifDropdown = document.getElementById('notifDropdown');
    const notifBtn = document.getElementById('notifBtn');
  
    if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
      notifDropdown.classList.add('hidden');
    }
  });  

  li.addEventListener('click', () => {
    window.location.href = `../message/view_message.php?id=${n.message_id}`;
  });
  