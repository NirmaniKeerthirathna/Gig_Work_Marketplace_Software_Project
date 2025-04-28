<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worknet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
    {
    die("Connection failed: " . $conn->connect_error);
    }

$query = $conn->prepare("
    SELECT p.user_id, p.name, p.skills, p.experience, 
           IFNULL(AVG(r.rating), 0) AS average_rating, 
           COUNT(r.id) AS total_ratings
    FROM profiles p
    LEFT JOIN reviews r ON p.user_id = r.worker_id
    GROUP BY p.user_id
");

if (!$query) 
    {
    die("Query error: " . $conn->error);
    }

$query->execute();
$result = $query->get_result();

$profiles = [];
while ($row = $result->fetch_assoc()) 
    {
    $profiles[] = $row;
    }

header('Content-Type: application/json');
echo json_encode($profiles);
?>