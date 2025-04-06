<?php

include 'database.php';

$category_name = $_POST['category_name'];

$sql = "INSERT INTO job_category (category_name) VALUES ('$category_name')";

if(mysqli_query($conn, $sql))
{
    echo "Job category added successfully";
}

else 
{
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
