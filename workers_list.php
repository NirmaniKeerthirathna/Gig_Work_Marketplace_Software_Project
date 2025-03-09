<?php

include 'database.php';

$gig_workers = [
    ['name' => 'Tasker 1', 'rating' => 4, 'comment' => 'Great job!'],
    ['name' => 'Tasker 2', 'rating' => 5, 'comment' => 'Excellent service!'],
    ['name' => 'Tasker 3', 'rating' => 3, 'comment' => 'Good, but could improve.'],
];

foreach ($gig_workers as $gig_worker) {
  echo '<div class="worker_card">';
  echo '<h2>' . $gig_worker['name'] . '</h2>';
  echo '<div class="rating">';
  for ($i = 1; $i <= 5; $i++) {
      if ($i <= $gig_worker['rating']) {
          echo '<span class="star filled">&#9733;</span>';
      } else {
          echo '<span class="star">&#9733;</span>';
      }
  }
  echo '</div>';
  echo '<div class="comment">' . $gig_worker['comment'] . '</div>';
  echo '</div>';
}



//Review - Displaying Ratings from File
//To display ratings from the file, modify workers_list.php
$taskers = [];

if (file_exists($file)) {
    $lines = file($file);
    foreach ($lines as $line) {
        list($name, $rating, $comment) = explode('|', trim($line));
        $taskers[] = ['name' => $name, 'rating' => (int)$rating, 'comment' => $comment];
    }
}

foreach ($taskers as $tasker) {
    echo '<div class="tasker-card">';
    echo '<h2>' . $tasker['name'] . '</h2>';
    echo '<div class="rating">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $tasker['rating']) {
            echo '<span class="star filled">&#9733;</span>';
        } else {
            echo '<span class="star">&#9733;</span>';
        }
    }
    echo '</div>';
    echo '<div class="comment">' . $tasker['comment'] . '</div>';
    echo '</div>';
}

?>
