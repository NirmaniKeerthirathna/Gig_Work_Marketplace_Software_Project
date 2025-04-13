<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to post a job.";
    exit;
}

$host = "localhost";
$db = "worknet";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        INSERT INTO tasks (title, task_date, location, job_description, job_category, payment, user_id)
        VALUES (:title, :task_date, :location, :job_description, :job_category, :payment, :user_id)
    ");

    $stmt->execute([
        ':title'           => $_POST['title'],
        ':task_date'       => $_POST['date'],
        ':location'        => $_POST['location'],
        ':job_description' => $_POST['job_description'],
        ':job_category'    => $_POST['job_category'],
        ':payment'         => $_POST['payment'],
        ':user_id'         => $_SESSION['user_id'],
    ]);

    echo "success";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>