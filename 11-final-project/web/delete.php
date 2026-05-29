<?php
include("library.php");

if (isset($_GET['id'])) {
    $connection = get_connection();

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $ev_id = intval($_GET['id']);

    $sql = "DELETE FROM events WHERE ev_id = $ev_id";

    if ($connection->query($sql)) {
        header("Location: index.php"); 
        exit();
    } else {
        echo "Delete failed: " . $connection->error;
    }

    $connection->close();
} else {
    header("Location: index.php");
    exit();
}
?>