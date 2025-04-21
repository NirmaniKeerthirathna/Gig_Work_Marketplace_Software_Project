<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['job_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

$job_id = $_GET['job_id'];
$conn = new mysqli("localhost", "root", "", "worknet");

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed.']);
    exit;
}

// Get applications and user details
$stmt = $conn->prepare("
    SELECT a.id, a.user_id, a.status, a.applied_at, 
           u.first_name, u.last_name, u.email
    FROM applications a
    JOIN users u ON a.user_id = u.id
    WHERE a.task_id = ?
");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

echo json_encode(['status' => 'success', 'applications' => $applications]);
?>
