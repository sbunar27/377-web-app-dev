<!-- 
 
 todo.php: handles the backend logic for adding new to-do items and marking them as completed. 
 When a new to-do item is added, it validates the input data and inserts it into the 'todos' table in the database. 
 When a to-do item is marked as completed, it updates the 'todos' table to set the item as completed.

-->

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

    // $sql = "DELETE FROM todos WHERE todo_id = $id";
    // Instead of deleting the record (dangerous), mark it as completed
    $sql = <<<SQL
    UPDATE todos SET
    todo_completed = 1
    WHERE todo_id = $id
    SQL;

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
    
    // explicitly grab post date, default to standard PHP date string
    $posted_date = (isset($_POST['date']) && !empty($_POST['date'])) ? $_POST['date'] : date('Y-m-d');
    $date_escaped = $connection->real_escape_string($posted_date);

    $sql = <<<SQL
    INSERT INTO todos (todo_task, todo_date, todo_completed) 
    VALUES ('$task_escaped', '$date_escaped', 0)
    SQL;

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