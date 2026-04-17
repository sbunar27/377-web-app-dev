<?php

include("library.php");
$connection = get_connection();

if ($connection->connect_error) {
    echo 'DB connection failed';
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $ev_date = $_POST['ev_date'] ?? '';
    $ev_title = $_POST['ev_title'] ?? '';
    $ev_desc = $_POST['ev_desc'] ?? '';

    if ($ev_date === '' || $ev_title === '' || $ev_desc === '') {
        echo 'All fields are required';
        exit;
    }

    $sql = <<<SQL
    INSERT INTO events (ev_date, ev_title, ev_desc)
    VALUES ('$ev_date', '$ev_title', '$ev_desc')
    SQL;

    if ($connection->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $connection->error;
    }
}

$connection->close();

?>