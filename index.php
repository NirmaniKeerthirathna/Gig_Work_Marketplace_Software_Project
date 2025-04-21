<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WorkNet</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>
    <ul>
      <li><a href="../post_job/post_job.html">Post a Job</a></li>
      <li><a href="categories.php">Categories</a></li>
      <li><a href="../search_jobs/search_jobs.html">Browse Jobs</a></li>
      <li><a href="workers_list.php">Reviews</a></li>

      <?php if ($isLoggedIn): ?>
        <li><a href="../manage_jobs/my_jobs.html">Manage Jobs</a></li>
        <li><a href="../log_out/logout.php">Log Out</a></li>
      <?php else: ?>
        <li><a href="../log_in/login.html">Log In</a></li>
        <li><a href="../sign_up/signup.html">Sign Up</a></li>
      <?php endif; ?>

      <li style="position: relative;">
        <button id="notifBtn">🔔 Notifications (<span id="notifCount">0</span>)</button>
        <div id="notifDropdown" class="hidden">
          <ul id="notifList"></ul>
        </div>
      </li> 
    </ul>
    <hr>
  </nav>

  <div class="home_page">
    <div class="tag_line">
      <h1>Work Your Way</h1>
      <h2>Anytime, Anywhere</h2>
      <p>Where Talent Meets Opportunity</p>
    </div>
    <div class="image">
      <img src="home_page_image.png" alt="Home Page Image">
    </div>
  </div>

  <script src="../notifications/notifications.js"></script>

  <!-- Modal -->
  <div id="notifModal" class="modal hidden">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <h3 id="modalTitle"></h3>
      <p id="modalMessage"></p>
    </div>
  </div>
</body>
</html>
