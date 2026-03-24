<?php

/*************************************************************************************************
 * This page saves a single book record based on the values submitted by the user
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
    $length = 0; // set to 0 if left empty
}

if ($rating === '' || !isset($rating)) {
    $rating = 0; // set to 0 if left empty
}

// NEW CODE! 

if ($connection->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Get POST data
$title = $connection->real_escape_string($title);
$author = $connection->real_escape_string($author);
$genre = $connection->real_escape_string($genre);
$rating = $connection->real_escape_string($rating);
$review = $connection->real_escape_string($review);
$status = $connection->real_escape_string($status);
$length = $connection->real_escape_string($length);
$dateStarted = $connection->real_escape_string($dateStarted);
$dateFinished = $connection->real_escape_string($dateFinished);

// Validate required fields
if ($title === "" || !isset($title) || empty($status)) {
    http_response_code(500);
    echo json_encode(['error' => 'Title and status are required.']);
    exit;
}

// After fetching $row
$dateStarted = $row["book_date_started"] ?? '';
if ($dateStarted != '0000-00-00' || $dateStarted == null) {
    $dateStarted = '';
}

$dateFinished = $row["book_date_finished"] ?? '';
if ($dateFinished != '0000-00-00' || $dateFinished == null) {
    $dateFinished = '';
}

$sql = "";

if ($id === "" || !isset($id)) {
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

try {
    if ($connection->query($sql) === TRUE) {
        // can't send data AND a response code, so just send the code and handle the message in the frontend
        // http_response_code(200);
        $id=$connection->insert_id;

        // echo json_encode(['message' => 'Book saved successfully']);
        echo $id;
    } else {
        // http_response_code(500);
        echo json_encode(['error' => 'Error saving book: ' . $connection->error]);
    }
} catch (Exception $e) {
    // http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
}





















// // OLD CODE!!!
// function isValidDate($date) {
//     // split the date string by '/' into parts (explode = split)
//     $parts = explode('/', $date);

//     if (count($parts) !== 3) {
//         return false;
//     }

//     $year = $parts[0];
//     $month = $parts[1];
//     $day = $parts[2];

//     // check if year has 4 digits and is all numbers
//     if (strlen($year) !== 4 || !ctype_digit($year)) {
//         return false;
//     }
//     // check if month has 2 digits and is all numbers
//     if (strlen($month) !== 2 || !ctype_digit($month)) {
//         return false;
//     }
//     // check if day has 2 digits and is all numbers
//     if (strlen($day) !== 2 || !ctype_digit($day)) {
//         return false;
//     }

//     // convert strings to numbers
//     $year = (int)$year;
//     $month = (int)$month;
//     $day = (int)$day;
//     // check if month is between 1 and 12
//     if ($month < 1 || $month > 12) {
//         return false;
//     }
//     // check if day is between 1 and 31
//     if ($day < 1 || $day > 31) {
//         return false;
//     }

//     // use built in function to check if the date is real
//     return checkdate($month, $day, $year);
// }

// // if either of these are true, it takes the user to an error page
// if ($dateStarted != ''){
//     if (!isValidDate($dateStarted)) {
//         die('<h1>ERROR: Invalid Date Started</h1><br><a href="index.php?content=list">Return to List</a>');
//     }
// }

// if ($dateFinished != ''){
//     if (!isValidDate($dateFinished)) {
//         die('<h1>ERROR: Invalid Date Finished</h1><br><a href="index.php?content=list">Return to List</a>');
//     }
// }

// $sql = "";

// if ($id === 0 || $id === "" || !isset($id)) {
//     $sql =<<<SQL
//     INSERT INTO book 
//     (book_title, book_author, book_genre, book_rating, book_review, book_status, book_length, book_date_started, book_date_finished)
//     VALUES ('$title', '$author', '$genre', $rating, '$review', '$status', $length, '$dateStarted', '$dateFinished')
//     SQL;
// }
// else {
//     $sql =<<<SQL
//     UPDATE book
//     SET book_title = '$title',
//         book_author = '$author',
//         book_genre = '$genre',
//         book_rating = $rating,
//         book_review = '$review',
//         book_status = '$status',
//         book_length = $length,
//         book_date_started = '$dateStarted',
//         book_date_finished = '$dateFinished'
//     WHERE book_id = $id
//     SQL;
// }

// if ($connection->query($sql)) {
//     // success
//     http_response_code(200);
// } else {
//     // failure
//     http_response_code(500);
// }

// ?>