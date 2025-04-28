<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "worknet");

if (!isset($_SESSION['user_id'])) 
    {
    echo "Please log in to send a message.";
    exit;
    }

$task_id = $_POST['task_id'];
$message = trim($_POST['message']);
$sender_id = $_SESSION['user_id'];

$taskQuery = $mysqli->prepare("SELECT user_id FROM tasks WHERE id = ?");
$taskQuery->bind_param("i", $task_id);
$taskQuery->execute();
$taskResult = $taskQuery->get_result();

if ($taskResult->num_rows === 0) 
    {
    echo "Invalid task.";
    exit;
    }

$row = $taskResult->fetch_assoc();
$receiver_id = $row['user_id'];

$stmt = $mysqli->prepare("INSERT INTO messages (task_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $task_id, $sender_id, $receiver_id, $message);

if ($stmt->execute()) 
    {
    echo "Message sent successfully.";
    $emailQuery = $mysqli->prepare("SELECT email FROM users WHERE id = ?");
    $emailQuery->bind_param("i", $sender_id);
    $emailQuery->execute();
    $emailResult = $emailQuery->get_result();
    $emailRow = $emailResult->fetch_assoc();
    $sender_email = $emailRow['email'] ?? 'Unknown Sender';

    $notifMsg = $message;
    $notifTitle = $sender_email;

    $message_id = $stmt->insert_id;

    $notifStmt = $mysqli->prepare("INSERT INTO notifications (user_id, title, message, message_id) VALUES (?, ?, ?, ?)");
    $notifStmt->bind_param("issi", $receiver_id, $notifTitle, $notifMsg, $message_id);
    $notifStmt->execute();

    } 
else 
    {
    echo "Failed to send message.";
    }
?>