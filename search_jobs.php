<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worknet";

$keyword = $_GET['keyword'] ?? '';

$conn = new mysqli("localhost", "root", "", "worknet");

if ($conn->connect_error) 
    {
    die("Connection failed: " . $conn->connect_error);
    }

$keyword = "%" . $conn->real_escape_string($keyword) . "%";

$sql = "SELECT id, title, job_category, job_description, task_date, location, payment 
        FROM tasks 
        WHERE title LIKE ? OR job_category LIKE ? OR job_description LIKE ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $keyword, $keyword, $keyword);
$stmt->execute();

$result = $stmt->get_result();
$jobs = [];

while ($row = $result->fetch_assoc()) 
    {
    $jobs[] = $row;
    }

header('Content-Type: application/json');
echo json_encode($jobs);
?>