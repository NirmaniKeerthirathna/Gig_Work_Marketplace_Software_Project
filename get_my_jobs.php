<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) 
    {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
    exit;
    }

$conn = new mysqli("localhost", "root", "", "worknet");
if ($conn->connect_error) 
    {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
    }

$user_id = $_SESSION['user_id'];

$result = $conn->query("
  SELECT t.id, t.title, t.job_category, t.job_description, t.task_date, t.location, t.payment, t.status,
  (SELECT COUNT(*) FROM applications a WHERE a.task_id = t.id) as application_count
  FROM tasks t
  WHERE t.user_id = $user_id
  ORDER BY t.created_at DESC
");

$jobs = [];
while ($row = $result->fetch_assoc()) 
    {
    $jobs[] = $row;
    }
echo json_encode(['status' => 'success', 'jobs' => $jobs]);
?>