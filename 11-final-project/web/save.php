<?php
include("library.php");

// Check if form was actually submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connection = get_connection();
    
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Retrieve inputs from the form
    $ev_id = isset($_POST['ev_id']) ? intval($_POST['ev_id']) : 0;
    $ev_title = trim($_POST['ev_title']);
    $ev_date = $_POST['ev_date'];
    $ev_time = $_POST['ev_time'];
    $ev_desc = trim($_POST['ev_desc']);
    
    // Checkbox logic: If it's checked, it sends '1'. If unchecked, it sends nothing.
    $ev_completed = isset($_POST['ev_completed']) ? 1 : 0; 

    // Basic validation
    if ($ev_id === 0 || empty($ev_title) || empty($ev_date)) {
        die("Missing required fields.");
    }

    // 1. Sanitize the inputs to prevent SQL crashes
    $ev_id        = intval($_POST['ev_id']); 
    $ev_title     = $connection->real_escape_string($_POST['ev_title']);
    $ev_date      = $connection->real_escape_string($_POST['ev_date']);
    $ev_time      = $connection->real_escape_string($_POST['ev_time']);
    $ev_desc      = $connection->real_escape_string($_POST['ev_desc']);
    $ev_completed = isset($_POST['ev_completed']) ? 1 : 0;

    // 2. Build the query string directly
    $sql = "UPDATE events SET 
            ev_title = '$ev_title', 
            ev_date = '$ev_date', 
            ev_time = '$ev_time', 
            ev_desc = '$ev_desc', 
            ev_completed = $ev_completed 
            WHERE ev_id = $ev_id";

    // 3. Execute and redirect
    if ($connection->query($sql)) {
        header("Location: index.php?date=" . $ev_date);
        exit();
    } else {
        echo "Update failed: " . $connection->error;
    }

    $connection->close();
} else {
    // If someone tries to load save.php directly in their browser without clicking "Save"
    header("Location: index.php");
    exit();
}
?>