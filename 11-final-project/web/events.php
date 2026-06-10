<!-- 
 
 events.php: handles the backend logic for adding new events to the database. 
 It validates the input data, ensuring that all required fields are filled and that the event time is between 5am and 10pm. 
 If the validation passes, it inserts the new event into the 'events' table in the database. 
 The script returns a success message if the insertion is successful or an error message if it fails.

-->

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
    $ev_cat = $_POST['ev_cat'] ?? '';

    if ($ev_date === '' || $ev_title === '' || $ev_time === '') {
        echo 'All fields are required';
        exit;
    } // If the time is later than 10pm, stop the user from adding the event and return an error message
    else if ($ev_time > '22:00:00') {
        echo 'Events cannot be scheduled after 10pm, remember to get some sleep!';
        exit;
    } // If the time is earlier than 5am, stop the user from adding the event and return an error message
    else if ($ev_time < '05:00:00') {
        echo 'Events cannot be scheduled before 5am, remember to get some rest!';
        exit;
    } else {
        echo '';
    }

    $sql = <<<SQL
    INSERT INTO events (ev_date, ev_title, ev_desc, ev_time, ev_cat)
    VALUES ('$ev_date', '$ev_title', '$ev_desc', '$ev_time', '$ev_cat')
    SQL;

    if ($connection->query($sql) === TRUE) {
        echo 'success:' . $connection->insert_id;
    } else {
        echo 'Error: ' . $connection->error;
    }
}

$connection->close();

?>