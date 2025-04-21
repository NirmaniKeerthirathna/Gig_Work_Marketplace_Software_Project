<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['app_id']) || !isset($_POST['status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

$app_id = $_POST['app_id'];
$status = $_POST['status'];
$allowed = ['Submitted', 'Under Review', 'Selected', 'Not Selected', 'Finalized', 'Withdrawn'];

if (!in_array($status, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid status.']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "worknet");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB error.']);
    exit;
}

$stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $app_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Application status updated.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
}
?>
