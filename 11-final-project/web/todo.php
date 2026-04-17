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
    $task = trim($_POST['task'] ?? $_GET['task'] ?? '');

    if ($task === '') {
        echo 'Empty task';
        exit;
    }

    $task_escaped = $connection->real_escape_string($task);

    $sql = "INSERT INTO todos (todo_task, todo_date) VALUES ('$task_escaped', CURDATE())";

    if ($connection->query($sql) === TRUE) {
        echo $connection->insert_id; // Return the ID for the JS to use
    } else {
        echo 'Insert failed: ' . $connection->error;
    }

    $connection->close();
    exit;
}