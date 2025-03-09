<?php

include 'database.php';

$worker = $_POST['worker'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

$sql = "INSERT INTO rating (worker, rating, comment) VALUES ('$worker', '$rating', '$comment')";

if(mysqli_query($conn, $sql))
{
  echo "Rating added successful!";
}

else
{
  echo "Error: " . mysqli_error($conn);
}

header('Location: index.html');

?>
