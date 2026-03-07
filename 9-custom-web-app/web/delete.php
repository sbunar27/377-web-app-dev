<?php

/*************************************************************************************************
 * This page deletes a single movie record based on the values submitted by the user
*************************************************************************************************/

include("library.php");
$connection = getConnection();

$id = $connection->real_escape_string($id);

/* danger zone */
$delete =<<<SQL
DELETE FROM book
WHERE book_id = $id
SQL;

$connection->query($delete);

header('Location: index.php?content=list');
?>