<?php

include 'database.php';

$search_query = $_GET['search_query'];
$sql = "SELECT job_id, job_title, job_description, category_id FROM job_posting WHERE job_title LIKE '%$search_query%' OR job_description LIKE '%$search_query%'";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang = "en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Search Results </title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

  <h1> Search Results </h1>

  <ul>
  <?php
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) 
      {
        echo "<li>";
        echo $row["job_title"] . " - " . $row["job_description"];
        echo " <a href='apply_for_jobs.php?job_id=" . $row['job_id'] . "'> Apply </a>";
        echo "</li>";
      }
    } 
    else 
    {
      echo "<li> No results found </li>";
    }
    ?>
  </ul>

</body>

</html>
