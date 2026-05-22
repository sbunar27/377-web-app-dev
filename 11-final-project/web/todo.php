<?php

include("library.php");

$connection = get_connection();
if ($connection->connect_error) {
    echo 'DB connection failed';
    exit;
}

if (($action = $_POST['action'] ?? '') === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
        echo 'Invalid ID';
        exit;
    }

    $sql = "DELETE FROM todos WHERE todo_id = $id";

    if ($connection->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Delete failed';
    }

    $connection->close();
    exit;
} else {
    $task = trim($_POST['task'] ?? '');

    if ($task === '') {
        echo 'Error: Empty task string sent to server';
        exit;
    }

    $task_escaped = $connection->real_escape_string($task);
    
    // explicitly grab post date, default safely to standard PHP date string
    $posted_date = (isset($_POST['date']) && !empty($_POST['date'])) ? $_POST['date'] : date('Y-m-d');
    $date_escaped = $connection->real_escape_string($posted_date);

    $sql = "INSERT INTO todos (todo_task, todo_date) VALUES ('$task_escaped', '$date_escaped')";

    if ($connection->query($sql) === TRUE) {
        // return only the numeric ID
        echo $connection->insert_id; 
    } else {
        // output SQL string issues back to JS log
        echo 'SQL Error: ' . $connection->error . ' | Running query: ' . $sql;
    }

    $connection->close();
    exit;
}