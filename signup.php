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

    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $account_type = $conn->real_escape_string($_POST["account_type"]);

    // Prepare name fields
    $first_name = '';
    $last_name = '';
    $employer_name = '';

    if ($account_type == 'gig_worker') {
        $first_name = $conn->real_escape_string($_POST["first_name"]);
        $last_name = $conn->real_escape_string($_POST["last_name"]);
    } else if ($account_type == 'employer') {
        $employer_name = $conn->real_escape_string($_POST["employer_name"]);
    }

    // Insert into users table
    $user_sql = "INSERT INTO users (email, password, account_type, first_name, last_name, employer_name) 
                 VALUES ('$email', '$password', '$account_type', '$first_name', '$last_name', '$employer_name')";

    if ($conn->query($user_sql)) {
        $user_id = $conn->insert_id;
        error_log("User inserted successfully with ID: $user_id");

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

        } else if ($account_type == 'employer') {
            // Nothing more needed since employer_name is already saved in users table
            echo "Sign up successful";
        }

    } else {
        echo "User Insert Error: " . $conn->error;
    }

    $conn->close();
}
?>
