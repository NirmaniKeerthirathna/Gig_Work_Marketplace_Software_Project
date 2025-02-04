<?php

//Create database connection
include ("create_Database_Connection.php");

//Get data from post_Task_Form
$task_Name = $_POST['task_Name'];
$date = $_POST['date'];
$location = $_POST['location'];
$details = $_POST['details'];
$payment_Amount = $_POST['payment_Amount'];

//Insert task data into the job database
$sql="INSERT INTO tasks (task_Name, date, location, details, payment_Amount) VALUES
('".$task_Name."', '".$date."', '".$location."', '".$details."', '".$payment_Amount."')";

//If the SQL query is successfully executed, notifying the user 
if ($conn->query($sql) === TRUE)
{    
echo "Task posted successfully";
}

else
{
echo "Check your database connection";
}

?>