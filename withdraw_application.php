<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['app_id'])) 
    {
    header("Location: ../login.php");
    exit;
    }

$app_id = $_POST['app_id'];
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "worknet");
if ($conn->connect_error) 
    {
    die("Connection failed: " . $conn->connect_error);
    }

$stmt = $conn->prepare("SELECT id FROM applications WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $app_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) 
    {
    header("Location: my_applications.php?error=unauthorized");
    exit;
    }

$stmt = $conn->prepare("UPDATE applications SET status = 'Withdrawn' WHERE id = ?");
$stmt->bind_param("i", $app_id);
$stmt->execute();

header("Location: my_applications.php?success=withdrawn");
exit;
?>