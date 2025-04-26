<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>WorkNet</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <nav>
    <div class="navbar-container">
      <div class="logo">
        <video src="worknet_logo.mp4" autoplay muted loop class="logo-video"></video>
      </div>

      <ul class="nav-links left-nav">
        <li><a href="../post_job/post_job_form.php">Post a Job</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="../search_jobs/search_jobs_form.php">Browse Jobs</a></li>
        <li><a href="../reviews/profiles.html">Reviews</a></li>
        <?php if ($isLoggedIn): ?>
          <li><a href="../manage_jobs/my_jobs.html">Manage Jobs</a></li>
          <li><a href="../applications/my_applications.php">My Applications</a></li>
        <?php endif; ?>
      </ul>

      <div class="right-actions">
        <div class="notif-container">
          <button id="notifBtn" class="notif-button">
            <i class="fas fa-bell icon-anim"></i>
            <span class="notif-count" id="notifCount">0</span>
          </button>
          <div id="notifDropdown" class="hidden">
            <ul id="notifList"></ul>
          </div>
        </div>

        <ul class="nav-links right-nav">
          <?php if ($isLoggedIn): ?>
            <li><a href="../log_out/logout.php">Log Out</a></li>
          <?php else: ?>
            <li><a href="../log_in/login.html">Log In</a></li>
            <li><a href="../sign_up/signup.html">Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="home_page">
  <div class="tag_line">
  <h1 class="main-heading">Work Your Way</h1>
  <h2 class="sub-heading">Anytime, Anywhere</h2>
  <p class="motto">Where Talent Meets Opportunity</p>

  <div class="tagline-buttons">
    <a href="../post_job/post_job_form.php" class="tag-btn">Post jobs for free</a>
    <a href="../search_jobs/search_jobs_form.php" class="tag-btn">Find jobs to earn money</a>
  </div>
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
