<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worknet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
    {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
    }

if (!isset($_POST['email']) || !isset($_POST['password'])) 
    {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
    }

$email = $conn->real_escape_string($_POST['email']);
$password_input = $_POST['password'];

$sql = "SELECT id, password FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows === 1) 
    {
    $user = $result->fetch_assoc();
    if (password_verify($password_input, $user['password'])) 
        {
        $session_token = bin2hex(random_bytes(32));
        $user_id = $user['id'];

        $stmt = $conn->prepare("INSERT INTO user_sessions (user_id, session_token) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $session_token);
        $stmt->execute();

        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['session_token'] = $session_token;

        echo json_encode(["status" => "success"]);
        } 
    else 
        {
        echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } 
else 
    {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    }

$conn->close();
?>