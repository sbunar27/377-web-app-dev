<link rel="stylesheet" href="style.css"/>

<?php

// This is the repository of all common functions for the hMDb website
extract($_REQUEST);

function getConnection(){
    $servername = "none";
    $username = "none";
    $password = 'none';
    $dbname = "none";

    // connect to database and make sure it was successful
    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}