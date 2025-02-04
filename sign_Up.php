<?php

include ("create_Database_Connection.php");

$email = $_POST['email'];
$password = $_POST['password'];

$sql="INSERT INTO users (Email, Password) values
('".$email."', '".$password."')";

if (mysqli_query($conn, $sql))
{  
echo "Sign up successful!";
}

else
{
echo "Check your database connection";
}

?>