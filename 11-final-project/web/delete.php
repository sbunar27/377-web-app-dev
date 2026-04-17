<?php
include("library.php");

if (isset($_GET['id'])) {
    $connection = get_connection();

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // 1. Sanitize the ID (ensure it's just a number)
    $ev_id = intval($_GET['id']);

    // 2. Run the delete query
    $sql = "DELETE FROM events WHERE ev_id = $ev_id";

    if ($connection->query($sql)) {
        // 3. Success! Redirect back to the agenda. 
        // If you have the date handy, you could pass it back here too.
        header("Location: index.php"); 
        exit();
    } else {
        echo "Delete failed: " . $connection->error;
    }

    $connection->close();
} else {
    // No ID provided? Send them home.
    header("Location: index.php");
    exit();
}
?>