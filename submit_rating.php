<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worknet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "Please log in.";
    exit;
}

$worker_id = $_POST['worker_id'];
$rating = $_POST['rating'];
$employer_id = $_SESSION['user_id'];

if ($rating < 1 || $rating > 5) {
    echo "Invalid rating.";
    exit;
}

$comment = isset($_POST['comment']) ? trim($_POST['comment']) : null;

// Check if the employer has already rated this worker
$check = $conn->prepare("SELECT id FROM reviews WHERE employer_id = ? AND worker_id = ?");
$check->bind_param("ii", $employer_id, $worker_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $update = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE employer_id = ? AND worker_id = ?");
    $update->bind_param("isii", $rating, $comment, $employer_id, $worker_id);
    $update->execute();
    echo "Rating updated!";
} else {
    $insert = $conn->prepare("INSERT INTO reviews (worker_id, employer_id, rating, comment) VALUES (?, ?, ?, ?)");
    $insert->bind_param("iiis", $worker_id, $employer_id, $rating, $comment);
    $insert->execute();
    echo "Rating submitted!";
}

?>