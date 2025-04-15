function getStarHTML(average) {
    const rounded = Math.round(average);
    return [1, 2, 3, 4, 5].map(i =>
      `<span class="star ${i <= rounded ? 'filled' : ''}" data-value="${i}">&#9733;</span>`
    ).join('');
  }  

document.addEventListener('DOMContentLoaded', () => {
    fetch('get_profiles.php')
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('profilesContainer');
  
        data.forEach(profile => {
          const card = document.createElement('div');
          card.classList.add('profile-card');
  
          card.innerHTML = `
  <h3>${profile.name}</h3>
  <p><strong>Skills:</strong> ${profile.skills}</p>
  <p><strong>Experience:</strong> ${profile.experience}</p>
  <div class="rating" data-worker-id="${profile.user_id}">
    ${getStarHTML(profile.average_rating)}
    <small>(${parseFloat(profile.average_rating).toFixed(1)} from ${profile.total_ratings} ratings)</small>
  </div>
  <textarea class="comment-box" rows="2" placeholder="Leave a comment..."></textarea>
  <button class="submit-rating">Submit</button>
`;

  
          const stars = card.querySelectorAll('.star');
          const ratingContainer = card.querySelector('.rating');
          const commentBox = card.querySelector('.comment-box');
          const submitBtn = card.querySelector('.submit-rating');
  
          stars.forEach(star => {
            const value = parseInt(star.getAttribute('data-value'));
  
            // Hover effect
            star.addEventListener('mouseenter', () => {
              stars.forEach(s => {
                const sValue = parseInt(s.getAttribute('data-value'));
                s.classList.toggle('hovered', sValue <= value);
              });
            });
  
            // Remove hover effect
            star.addEventListener('mouseleave', () => {
              stars.forEach(s => s.classList.remove('hovered'));
            });
  
            // Click to fill stars
            star.addEventListener('click', () => {
              stars.forEach(s => {
                const sValue = parseInt(s.getAttribute('data-value'));
                s.classList.toggle('filled', sValue <= value);
              });
            });
          });
  
          // Submit rating & comment
          submitBtn.addEventListener('click', () => {
            const workerId = ratingContainer.getAttribute('data-worker-id');
            const selectedRating = [...stars].filter(s => s.classList.contains('filled')).length;
            const comment = commentBox.value.trim();
  
            if (selectedRating === 0) {
              alert("Please select a star rating.");
              return;
            }
  
            submitRating(workerId, selectedRating, comment);
          });
  
          container.appendChild(card);
        });
      });
  });
  
  function submitRating(workerId, rating, comment) {
    fetch('submit_rating.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `worker_id=${workerId}&rating=${rating}&comment=${encodeURIComponent(comment)}`
    })
    .then(res => res.text())
    .then(data => {
        console.log('Server Response:', data);
        showToast(data); // Show toast
    
        const toast = document.getElementById('toast');
        console.log('Toast Element:', toast);
        console.log('Toast Class Before:', toast.className);
        
        // location.reload(); // Commented out to test
    })    
    .catch(err => {
        showToast("Something went wrong. Please try again."); // Show error toast
        console.error('Rating failed:', err);
    });    
}
  
function showToast(message) {
    const toast = document.getElementById('toast');
    console.log("Toast found in showToast:", toast); // Debug
  
    toast.textContent = message;
    toast.className = "toast show";
  
    setTimeout(() => {
      toast.className = "toast";
    }, 3000);
  }  
  