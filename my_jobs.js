document.addEventListener('DOMContentLoaded', function () 
  {
  fetch('get_my_jobs.php')
    .then(response => response.json())
    .then(data => {
      if (data.status !== 'success') 
        {
        showToast(data.message || 'Error loading jobs.', true);
        return;
        }

      const jobs = data.jobs;
      const container = document.getElementById('jobsContainer');
      container.innerHTML = '';

      if (jobs.length === 0) 
        {
        container.innerHTML = '<p> You have not posted any jobs yet. </p>';
        return;
        }

      jobs.forEach(job => {
        const card = document.createElement('div');
        card.className = 'job-card';

        card.innerHTML = `
          <h3>${job.title}</h3>
          <p><strong> Date: </strong> ${job.task_date ?? 'N/A'}</p>
          <p><strong> Location: </strong> ${job.location ?? 'N/A'}</p>
          <p><strong> Payment: </strong> $${job.payment ?? 'N/A'}</p>
          <p><strong> Description: </strong> ${job.job_description}</p>
          <p><strong> Category: </strong> ${job.job_category}</p>

          <label class="status-label"> Status: </label>
          <select class="status-select" data-id="${job.id}">
            <option value="Applications Received" ${job.status === 'Applications Received' ? 'selected' : ''}> Applications Received </option>
            <option value="Applicant Selected" ${job.status === 'Applicant Selected' ? 'selected' : ''}> Applicant Selected </option>
            <option value="In Progress" ${job.status === 'In Progress' ? 'selected' : ''}> In Progress </option>
            <option value="Completed" ${job.status === 'Completed' ? 'selected' : ''}> Completed </option>
          </select>
          <p><strong> Applications: </strong> ${job.application_count} 
            <button class="view-apps-btn" data-id="${job.id}">View</button>
          </p>
        `;
        container.appendChild(card);
      });

      document.querySelectorAll('.view-apps-btn').forEach(button => {
        button.addEventListener('click', function () 
          {
          const jobId = this.getAttribute('data-id');
          const card = this.closest('.job-card');

          let existingContainer = card.querySelector('.applications-container');
          if (existingContainer) 
            {
            existingContainer.remove(); 
            return;
            }

          const appContainer = document.createElement('div');
          appContainer.className = 'applications-container';
          appContainer.innerHTML = '<p>Loading applications...</p>';
          card.appendChild(appContainer);

          fetch(`get_applications.php?job_id=${jobId}`)
            .then(response => response.json())
            .then(data => {
              if (data.status !== 'success') 
                {
                appContainer.innerHTML = '<p> Error loading applications. </p>';
                return;
                }

              const apps = data.applications;
              if (apps.length === 0) 
                {
                appContainer.innerHTML = '<p>No applications yet.</p>';
                return;
                }

              appContainer.innerHTML = '';
              apps.forEach(app => {
                const appDiv = document.createElement('div');
                appDiv.className = 'application-card';
                appDiv.innerHTML = `
                  <p><strong> Name: </strong> ${app.first_name} ${app.last_name}</p>
                  <p><strong> Email: </strong> ${app.email}</p>
                  <p><strong> Status: </strong> ${app.status}</p>
                  <p><strong> Applied At: </strong> ${app.applied_at}</p>

                  <label for="status-${app.id}"> Status: </label>
                  <select id="status-${app.id}" data-app-id="${app.id}">
                    <option value="Submitted" ${app.status === 'Submitted' ? 'selected' : ''}> Submitted </option>
                    <option value="Under Review" ${app.status === 'Under Review' ? 'selected' : ''}> Under Review </option>
                    <option value="Selected" ${app.status === 'Selected' ? 'selected' : ''}> Selected </option>
                    <option value="Not Selected" ${app.status === 'Not Selected' ? 'selected' : ''}> Not Selected </option>
                    <option value="Finalized" ${app.status === 'Finalized' ? 'selected' : ''}> Finalized </option>
                    <option value="Withdrawn" ${app.status === 'Withdrawn' ? 'selected' : ''}> Withdrawn </option>
                  </select>
                  <hr>
                `;
                appContainer.appendChild(appDiv);
              });

              appContainer.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', function () 
                  {
                  const appId = this.getAttribute('data-app-id');
                  const newStatus = this.value;

                  fetch('update_application_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `app_id=${appId}&status=${encodeURIComponent(newStatus)}`
                  })
                    .then(response => response.json())
                    .then(data => {
                      showToast(data.message || 'Status updated!', data.status !== 'success');
                    })
                    .catch(err => {
                      console.error(err);
                      showToast('Failed to update status.', true);
                    });
                  });
              });

            })
            .catch(err => {
              appContainer.innerHTML = '<p>Error fetching applications.</p>';
              console.error(err);
            });
          });
      });

      document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () 
          {
          const jobId = this.getAttribute('data-id');
          const newStatus = this.value;

          fetch('update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${jobId}&status=${encodeURIComponent(newStatus)}`
          })
            .then(response => response.json())
            .then(data => {
              showToast(data.message || 'Status updated successfully!', data.status !== 'success');
            })
            .catch(err => {
              showToast('Error updating status.', true);
              console.error(err);
            });
          });
      });

    })
    .catch(err => {
      showToast('Failed to load jobs.', true);
      console.error(err);
    });
});

function showToast(message, isError = false) 
  {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.style.backgroundColor = isError ? '#dc3545' : '#28a745';
  toast.classList.add('show');

  setTimeout(() => {
    toast.classList.remove('show');
    }, 3000);
  }