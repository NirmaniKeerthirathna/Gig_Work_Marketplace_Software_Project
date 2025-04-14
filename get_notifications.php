<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worknet";

$mysqli = new mysqli("localhost", "root", "", "worknet");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = $mysqli->prepare("SELECT id, title, message, is_read, created_at, message_id FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notifications);
?>
