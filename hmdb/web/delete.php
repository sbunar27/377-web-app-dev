<?php

/*************************************************************************************************
 * This page saves a single movie record based on the values submitted by the user
*************************************************************************************************/

include("library.php");
$connection = getConnection();
$title = $connection->real_escape_string($title);
$genre = $connection->real_escape_string($genre);
$rating = $connection->real_escape_string($rating);
$duration = $connection->real_escape_string($duration);
$release_year = $connection->real_escape_string($release_year);

/* danger zone */
$delete =<<<SQL
DELETE FROM movie
WHERE mov_id = $id
SQL;

$connection->query($delete);

header('Location: index.php?content=list');
?>