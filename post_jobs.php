<?php

session_start();

if (!isset($_SESSION["user_id"])) 
{
  header("Location: login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  include 'database.php';

  $job_title = $_POST["job_title"];
  $job_description = $_POST["job_description"];
  $rate = $_POST["rate"];
  $start_date = $_POST["start_date"];
  $end_date = $_POST["end_date"];
  $user_id = $_SESSION["user_id"];
  //$created_at = $_POST["created_at"];
  //$job_status = $_POST["job_status"];
  $category_name = $_POST["category_name"];

  $sql = "SELECT category_id FROM job_category WHERE category_name = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $category_name);
  $stmt->execute();
  $stmt->bind_result($category_id);
  $stmt->fetch();
  $stmt->close();

  if ($category_id) 
  {
    //Insert job posting with category ID
    $sql = "INSERT INTO job_posting (job_title, job_description, rate, start_date, end_date, user_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissii", $job_title, $job_description, $rate, $start_date, $end_date, $user_id, $category_id);
    $stmt->execute();
    $stmt->close();
    
    echo "Job posted successfully!";
  } 

  else 
  {
    //echo "Error: " . mysqli_error($conn);
    echo "Job not posted";
  }

  mysqli_close($conn);
}

?>
 