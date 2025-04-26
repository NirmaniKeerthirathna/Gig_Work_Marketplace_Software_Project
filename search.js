document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop form from reloading page
    console.log("Form submitted"); // ✅ Debug: check if this shows

    const keyword = document.getElementById('keyword').value;
    console.log("Keyword:", keyword); // ✅ Debug: check this value

    fetch('search_jobs.php?keyword=' + encodeURIComponent(keyword))
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then(data => {
            console.log("Fetched data:", data); // ✅ Debug: see the result

            const results = document.getElementById('results');
            results.innerHTML = '';
            if (data.length === 0) {
                results.innerHTML = '<p>No jobs found.</p>';
                return;
            }

            data.forEach(job => {
                const div = document.createElement('div');
                div.classList.add('job');
                div.innerHTML = `
                    <h3>${job.title}</h3>
                    <p><strong>Category:</strong> ${job.job_category}</p>
                    <p>${job.job_description}</p>
                    <form onsubmit="applyJob(event, ${job.id})">
                    <button type="button" onclick="toggleMessageBox(${job.id}, this)">💬 Message</button>
                        <button type="submit">Apply</button>
                    </form>

                    <div class="message-box hidden" id="message-box-${job.id}">
                <textarea placeholder="Type your message here..." id="message-input-${job.id}"></textarea>
                <button onclick="sendMessage(${job.id})">Send</button>
                </div>

                    <hr>
                `;
                results.appendChild(div);
            });
        })
        .catch(err => {
            console.error("Fetch error:", err); // ✅ Debug
            document.getElementById('results').innerHTML = '<p style="color:red;">Failed to fetch jobs.</p>';
        });
});

function applyJob(event, taskId) {
    event.preventDefault(); // <-- This is important!
    
    const formData = new FormData();
    formData.append('task_id', taskId);

    console.log("Applying for task:", taskId);

    fetch('../search_jobs/apply_job.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        console.log("Apply response:", response); 
        showToast(response.message);

        if (response.status === 'success') {
            setTimeout(() => {
                window.location.href = '../index/index.php'; 
            }, 2000); 
        }
    })
    .catch(error => {
        console.error("Failed to parse response:", error);
        alert("Something went wrong. Please try again.");
    });
}


document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const applyTaskId = params.get('apply');

    if (applyTaskId) {
        fetch('apply_job.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `task_id=${applyTaskId}`
        }).then(res => res.text())
          .then(response => {
              alert(response);
              // Remove the "apply" from the URL so it doesn't apply again
              params.delete('apply');
              window.history.replaceState({}, '', `${location.pathname}?${params}`);
          });
    }
});

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');

    // Hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

let currentTaskId = null;

function openMessageBox(taskId) {
    currentTaskId = taskId;
    document.getElementById('messageModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

function sendMessage() {
    const message = document.getElementById('messageInput').value;

    if (!message.trim()) {
        showToast("Please enter a message.");
        return;
    }

    const formData = new FormData();
    formData.append('task_id', currentTaskId);
    formData.append('message', message);

    fetch('../message/send_message.php', {
        method: 'POST',
        body: formData
    }).then(res => res.text())
      .then(response => {
          showToast(response);
          document.getElementById('messageInput').value = '';
          closeModal();
      })
      .catch(err => {
          console.error("Error sending message:", err);
          showToast("Failed to send message.");
      });
}

function toggleMessageBox(taskId, button) {
    // Hide all other message boxes
    document.querySelectorAll('.message-box').forEach(box => box.classList.add('hidden'));
    // Show only the selected one
    document.getElementById(`message-box-${taskId}`).classList.toggle('hidden');
}

function sendMessage(taskId) {
    const message = document.getElementById(`message-input-${taskId}`).value.trim();

    if (!message) {
        showToast("Please enter a message.");
        return;
    }

    const formData = new FormData();
    formData.append('task_id', taskId);
    formData.append('message', message);

    fetch('../message/send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        showToast(response);
        document.getElementById(`message-input-${taskId}`).value = '';
        document.getElementById(`message-box-${taskId}`).classList.add('hidden');
    })
    .catch(err => {
        console.error("Error sending message:", err);
        showToast("Failed to send message.");
    });
}