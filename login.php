<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  include 'database.php';

  $username = $_POST["username"];
  $password = $_POST["password"];

  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) 
  {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row["password"])) 
    {
      $_SESSION["user_id"] = $row["id"];
      $redirect_to = isset($_SESSION["redirect_to"]) ? $_SESSION["redirect_to"] : "index.html";
      unset($_SESSION["redirect_to"]);
      header("Location: $redirect_to");
    } 
    
    else 
    {
      echo "Invalid password.";
    }
    } 
  
    else 
    {
      echo "User not found.";
    }
  
    mysqli_close($conn);
}

?>

<!DOCTYPE html>

<html>

<head>

  <title>Log In</title>

  <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

  <form method="POST" action="login.php">

    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    
    <input type="submit" value="Log In">
  
  </form>

</body>

</html>

