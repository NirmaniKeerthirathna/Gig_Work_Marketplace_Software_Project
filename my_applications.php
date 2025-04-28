<?php
session_start();
if (!isset($_SESSION['user_id'])) 
  {
    header("Location: ../log_in/login.html");
    exit;
  }

$conn = new mysqli("localhost", "root", "", "worknet");
if ($conn->connect_error) 
  {
    die("Connection failed: " . $conn->connect_error);
  }

$user_id = $_SESSION['user_id'];

$sql = "SELECT a.id, t.title, t.job_description, t.location, t.payment, a.status, a.applied_at
        FROM applications a
        JOIN tasks t ON a.task_id = t.id
        WHERE a.user_id = ?
        ORDER BY a.applied_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title> My Applications </title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2> My Job Applications </h2>

  <div id="messageBox" class="hidden"></div>

  <div class="applications">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="application-card">
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p><strong> Description: </strong> <?= htmlspecialchars($row['job_description']) ?></p>
        <p><strong> Location: </strong> <?= htmlspecialchars($row['location']) ?></p>
        <p><strong> Payment: </strong> $<?= htmlspecialchars($row['payment']) ?></p>
        <p><strong> Status: </strong> <?= htmlspecialchars($row['status']) ?></p>
        <p><small> Applied on: <?= htmlspecialchars($row['applied_at']) ?></small></p>

        <?php if (in_array($row['status'], ['Submitted', 'Under Review'])): ?>
            <form method="POST" action="withdraw_application.php" class="withdraw-form">
            <input type="hidden" name="app_id" value="<?= $row['id'] ?>">
            <button type="button" class="withdraw-btn" data-app-id="<?= $row['id'] ?>"> Withdraw Application </button>
            </form>
        <?php endif; ?>
        <hr>
      </div>
    <?php endwhile; ?>
  </div>

  <div id="confirmModal" class="modal hidden">
  <div class="modal-content">
      <p> Are you sure you want to withdraw this application? </p>
      <div class="modal-actions">
        <button id="confirmYes" class="modal-btn confirm"> Yes, Withdraw </button>
        <button id="confirmNo" class="modal-btn cancel"> Cancel </button>
      </div>
  </div>
  </div>

  <div id="toast" class="toast"></div>

  <script src="my_applications.js"></script>
</body>
</html>