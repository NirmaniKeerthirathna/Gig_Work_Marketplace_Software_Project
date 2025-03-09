<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    include 'database.php';

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $account_type = $_POST["account_type"];

    $sql = "INSERT INTO users (username, email, password, account_type) VALUES ('$username', '$email', '$password', '$account_type')";

    if(mysqli_query($conn, $sql))
    {
        //Retrieve the ID of the last created user account
        $user_id = mysqli_insert_id($conn);
    
    if($account_type == 'gig_worker')
    {
        $skills = $_POST["skills"];  
        $experience = $_POST["experience"];
        $availability = $_POST["availability"]; 
        
        $sql = "INSERT INTO gig_worker (user_id, skills, experience, availability) VALUES ('$user_id', '$skills', '$experience', '$availability')";
    }
        
    if(mysqli_query($conn, $sql))
    {
        echo "Sign up successful!";
    }

    else
    {
        echo "Error: " . mysqli_error($conn);
    }
    } 
    
    else
    {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title> Sign Up </title>
    <link rel = "stylesheet" type = "text/css" href = "style.css">
</head>

<body>
<form method = "POST" action = "signup.php">

<input type="text" name="username" placeholder="Username" required> <br>
<input type="email" name="email" placeholder="Email" required> <br>
<input type="password" name="password" placeholder="Password" required> <br>

<!--Showing the subsequent fields applicable to account type--> 
Account Type: <select name = "account_type" id = "account_type" required onchange = "show_account_type_fields(this.value)">
    <option value = "gig_worker"> Worker </option>
    <option value = "employer"> Employer </option>
</select> <br>

<div id="gig_worker_fields" style="display: none;">
      Skills: <textarea name="skills"></textarea><br>
      Experience: <textarea name="experience"></textarea><br>
      Availability: <select name = "availability" id = "availability">
        <option value = "weekdays"> Weekdays </option>
        <option value = "weekends"> Weekends </option>
        </select> <br>  
</div>
    
    <input type="submit" value="Sign Up">

</form>

<script>

    function show_account_type_fields(account_type) 
    {
      document.getElementById('gig_worker_fields').style.display = account_type === 'gig_worker' ? 'block' : 'none';
    }

  </script>
  
</body>
</html>    