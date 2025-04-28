<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "worknet");

if (!isset($_SESSION['user_id'])) 
    {
    echo "Please log in.";
    exit;
    }

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$task_id = $_POST['task_id'];
$message = trim($_POST['message']);

$emailQuery = $mysqli->prepare("SELECT email FROM users WHERE id = ?");
$emailQuery->bind_param("i", $sender_id);
$emailQuery->execute();
$emailResult = $emailQuery->get_result();
$sender_email = $emailResult->fetch_assoc()['email'];

$stmt = $mysqli->prepare("INSERT INTO messages (task_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $task_id, $sender_id, $receiver_id, $message);
$stmt->execute();

$message_id = $stmt->insert_id;

$notifStmt = $mysqli->prepare("INSERT INTO notifications (user_id, message, title, message_id) VALUES (?, ?, ?, ?)");
$notifStmt->bind_param("issi", $receiver_id, $message, $sender_email, $message_id);
$notifStmt->execute();

header("Location: view_message.php?id=" . $stmt->insert_id);
exit;
?>