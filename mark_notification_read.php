<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "worknet");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Not logged in";
    exit;
}

$notif_id = $_POST['notification_id'] ?? null;
$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $notif_id, $user_id);
$stmt->execute();

echo $stmt->affected_rows > 0 ? "Marked as read" : "Update failed";
?>
