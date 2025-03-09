<?php

include 'database.php';

$sql = "SELECT DISTINCT category FROM tasks";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html>

<head>

  <title>Job Categories</title>

  <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

  <h1>Job Categories</h1>

  <ul>
    <?php while ($row = mysqli_fetch_assoc($result)) 
    { ?>
      <li><?php echo $row["category"]; ?></li>
    <?php } ?>
  </ul>

</body>

</html>
