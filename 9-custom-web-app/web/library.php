<?php

error_reporting(0);

// This is the repository of all common functions for the book nook website
extract($_REQUEST);

function getConnection(){
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "booknook";

    // connect to database and make sure it was successful
    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}