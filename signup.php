<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worknet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize inputs
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $account_type = $conn->real_escape_string($_POST["account_type"]);

    // Insert into users table
    $user_sql = "INSERT INTO users (email, password, account_type) VALUES ('$email', '$password', '$account_type')";

    if ($conn->query($user_sql)) {
        $user_id = $conn->insert_id;

        if ($account_type == 'gig_worker') {
            $skills = $conn->real_escape_string($_POST["skills"]);
            $experience = $conn->real_escape_string($_POST["experience"]);
            $availability = $conn->real_escape_string($_POST["availability"]);

            $worker_sql = "INSERT INTO gig_worker (user_id, skills, experience, availability) 
                           VALUES ('$user_id', '$skills', '$experience', '$availability')";

if ($conn->query($worker_sql)) {
    echo "Sign up successful";
} else {
    echo "Worker Info Error: " . $conn->error;
} 
        } 
        else {
            echo "Sign up successful";
        }


    } else {
        echo "User Insert Error: " . $conn->error;
    }

    $conn->close();
}
?>
