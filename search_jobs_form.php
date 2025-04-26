<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Jobs</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include('../header/header.php'); ?>
    <h2>Find a Job</h2>

    <div class="info-section">
        Start your journey here! Use the search to find jobs that match your skills.
    </div>

    <form id="searchForm" onsubmit="return false;">
        <input type="text" id="keyword" placeholder="Search by title, category or description">
        
        <button type="submit">Search</button>
    </form>   

    <div id="results"></div>   
    
    <div id="toast" class="toast"></div>

    <script src="search.js"></script>
</body>
</html>