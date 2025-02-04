<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'User_Database';

//create a database connection 
$conn = mysqli_connect($servername, $username, $password, $dbname);

//If the database connection fails, display an error message and exit
if (!$conn)
{
 die('Could not connect: ' . mysqli_error($conn));
}

//If the connection is successful, select the database and display success message
else
{
mysqli_select_db($conn, $dbname);	
echo("Database connection is established");	
}

?>