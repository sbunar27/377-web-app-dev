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
    $ev_time = $_POST['ev_time'] ?? '';

    if ($ev_date === '' || $ev_title === '' || $ev_desc === '' || $ev_time === '') {
        echo 'All fields are required';
        exit;
    }

    $sql = <<<SQL
    INSERT INTO events (ev_date, ev_title, ev_desc, ev_time)
    VALUES ('$ev_date', '$ev_title', '$ev_desc', '$ev_time')
    SQL;

    if ($connection->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $connection->error;
    }
}

$connection->close();

?>