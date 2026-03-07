<?php

/*************************************************************************************************
 * This page saves a single movie record based on the values submitted by the user
*************************************************************************************************/

include("library.php");
$connection = getConnection();

$title = $connection->real_escape_string($title);
$author = $connection->real_escape_string($author);
$genre = $connection->real_escape_string($genre);
$rating = $connection->real_escape_string($rating);
$review = $connection->real_escape_string($review);
$status = $connection->real_escape_string($status);
$length = $connection->real_escape_string($length);
$dateStarted = $connection->real_escape_string($dateStarted);
$dateFinished = $connection->real_escape_string($dateFinished);

if ($length === '' || !isset($length)) {
    $length = 0; // PHP null
} else {
    $length = (int)$length; // cast to int if not empty
}

if ($rating === '' || !isset($rating)) {
    $rating = 0; // PHP null
} else {
    $rating = (int)$rating; // cast to int if not empty
}

$sql = "";

if ($id === 0 || $id === "" || !isset($id)) {
    $sql =<<<SQL
    INSERT INTO book 
    (book_title, book_author, book_genre, book_rating, book_review, book_status, book_length, book_date_started, book_date_finished)
    VALUES ('$title', '$author', '$genre', $rating, '$review', '$status', $length, '$dateStarted', '$dateFinished')
    SQL;
}
else {
    $sql =<<<SQL
    UPDATE book
    SET book_title = '$title',
        book_author = '$author',
        book_genre = '$genre',
        book_rating = $rating,
        book_review = '$review',
        book_status = '$status',
        book_length = $length,
        book_date_started = '$dateStarted',
        book_date_finished = '$dateFinished'
    WHERE book_id = $id
    SQL;
}

$connection->query($sql);

header('Location: index.php?content=list');
?>