<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

$task_id = $_POST['id'];
$status = $_POST['status'];

$valid_statuses = ['Applications Received', 'Applicant Selected', 'In Progress', 'Completed'];

if (!in_array($status, $valid_statuses)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid status value.']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "worknet");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if the task belongs to this user
$check = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND user_id = ?");
$check->bind_param("ii", $task_id, $user_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized to update this task.']);
    exit;
}

// Update the status
$stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $task_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Status updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
}
?>
