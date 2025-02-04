<?php

include ("create_Database_Connection.php");

$email = $_POST['email'];
$password = $_POST['password'];

$sql="SELECT * FROM users WHERE email = '$email' AND password = '$password'";

//Retrieving user details from the user database
$result = $conn->query($sql);

if ($result->num_rows > 0)
{  
echo "Login successful!";
}

else
{
echo "Invalid email or password";
}

?>