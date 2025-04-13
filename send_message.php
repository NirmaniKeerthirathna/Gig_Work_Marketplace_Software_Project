<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "worknet");

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to send a message.";
    exit;
}

$task_id = $_POST['task_id'];
$message = trim($_POST['message']);
$sender_id = $_SESSION['user_id'];

// Get employer (poster) of the task
$taskQuery = $mysqli->prepare("SELECT user_id FROM tasks WHERE id = ?");
$taskQuery->bind_param("i", $task_id);
$taskQuery->execute();
$taskResult = $taskQuery->get_result();

if ($taskResult->num_rows === 0) {
    echo "Invalid task.";
    exit;
}

$row = $taskResult->fetch_assoc();
$receiver_id = $row['user_id'];

// Insert message
$stmt = $mysqli->prepare("INSERT INTO messages (task_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $task_id, $sender_id, $receiver_id, $message);

if ($stmt->execute()) {
    echo "Message sent successfully.";
} else {
    echo "Failed to send message.";
}

$notifMsg = "You have a new message about a task.";
$notifStmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
$notifStmt->bind_param("is", $receiver_id, $notifMsg);
$notifStmt->execute();


?>
