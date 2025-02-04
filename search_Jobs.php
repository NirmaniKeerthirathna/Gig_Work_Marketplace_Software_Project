<?php

//Create database connection
include ("create_Database_Connection.php");

//Get data from search_Jobs_Form
$search_Term = $_POST['search_Term'];

//Search jobs in the job database
$sql = "SELECT * FROM tasks WHERE task_Name LIKE '%$search_Term%' OR details LIKE '%$search_Term%'";
$result = $conn->query($sql);

//If there are jobs matching the search term, displaying the search results 
if ($result->num_rows > 0)
{    
    //Looping through the search result rows until the last row is fetched
    while ($row = $result->fetch_assoc())
    {
    echo "Task Name:" .$row["task_Name"]. "Date:" .$row["date"]. "Location:" .$row["location"]. "Details:" .$row["details"]. "Payment Amount:" .$row["payment_Amount"]. "<br>";
    }
}
else
{
echo "No results";
}

?>