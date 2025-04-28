<?php
session_start();
header('Content-Type: application/json');

ini_set("log_errors", 1);
ini_set("error_log", "php-error.log"); 
error_reporting(0);
ini_set('display_errors', 0);
if (!isset($_SESSION['user_id'])) 
    {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to apply.']);
    exit;
    }

$task_id = $_POST['task_id'] ?? null;

if (!$task_id) 
    {
    echo json_encode(['status' => 'error', 'message' => 'Invalid job.']);
    exit;
    }

$conn = new mysqli("localhost", "root", "", "worknet");

if ($conn->connect_error) 
    {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
    }

$user_id = $_SESSION['user_id'];

file_put_contents('log.txt', "task_id: $task_id | user_id: $user_id\n", FILE_APPEND);

if (!$user_id || !$task_id) 
    {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
    }

$check = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND task_id = ?");
$check->bind_param("ii", $user_id, $task_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) 
    {
    echo json_encode(['status' => 'error', 'message' => 'You have already applied for this job.']);
    exit;
    }

$stmt = $conn->prepare("INSERT INTO applications (user_id, task_id, status) VALUES (?, ?, 'Submitted')");
if (!$stmt) 
    {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
    }

$stmt->bind_param("ii", $user_id, $task_id);

if ($stmt->execute()) 
    {
    echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully.']);
    } 
else 
    {
    echo json_encode(['status' => 'error', 'message' => 'Execution failed: ' . $stmt->error]);
    }
exit;
?>