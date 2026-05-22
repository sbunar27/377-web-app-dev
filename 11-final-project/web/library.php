<?php
$connection = null;

function get_connection() {

    $host = 'localhost';
    $user = 'root';
    $pass = 'password';
    $db = 'solace';

    $connection = new mysqli($host, $user, $pass, $db);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}

?>