<?php

include 'database.php';

session_start();

$job_id = $_GET["job_id"];
$user_id = $_SESSION["user_id"];

$sql = "INSERT INTO job_application (job_id, user_id) VALUES ('$job_id', '$user_id')";

if (mysqli_query($conn, $sql)) 
{
  echo "Application submitted successfully!";
} 
  
else 
{
  echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
