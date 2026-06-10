<!-- 
 
 completed.php: displays a log of all completed events and to-dos, sorted by most recent date. 
 It retrieves completed events and to-dos from the database, formats the dates, and passes the data to JavaScript to render in DataTables. 
 The page allows users to review their completed items and click back into event details if they want to uncheck completion status.

 -->

<?php
include_once("library.php");

$connection = get_connection();
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// get ONLY completed events, sorted by most recent date
$sql = <<<SQL
SELECT *, date_format(ev_date, '%m/%d/%Y') as 'formatted_date'
FROM events
WHERE ev_completed = 1
ORDER BY ev_date DESC, ev_time ASC
SQL;

$rows = [];
$result = $connection->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $result->free();
}
$connection->close();

// pass the completed data to JavaScript
echo "<script>var completedData = " . json_encode($rows, JSON_PARTIAL_OUTPUT_ON_ERROR) . ";</script>";

// get ONLY completed todos, sorted by most recent date
$connection = get_connection();
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}  

$sql = <<<SQL
SELECT *, date_format(todo_date, '%m/%d/%Y') as 'formatted_date'
FROM todos
WHERE todo_completed = 1
ORDER BY todo_date DESC
SQL;

$rows = [];
$result = $connection->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $result->free();
}
$connection->close();

// pass the completed data to JavaScript
echo "<script>var completedTodoData = " . json_encode($rows, JSON_PARTIAL_OUTPUT_ON_ERROR) . ";</script>";

?>

<div id="completed-section">
    <h2>Completed Events</h2>
    <p>Here is a log of everything you've crossed off your calendar!</p>
    <br>
    
    <table id="completedTable" class="stripe hover dataTables"></table>
    <br><br>

    <h2>Completed To-Dos</h2>
    <p>And here are the tasks you've checked off your to-do list!</p>
    <br>
    <table id="completedTodoTable" class="stripe hover dataTables"></table>
    
    <br>
    <a class="button" href="index.php?nav=agenda">Back to Agenda</a>
</div>

<script>
//completed events
$(document).ready(function() {
    var completedTable = $('#completedTable').DataTable({
        data: completedData,
        columns: [
            { title: "Date", data: "formatted_date" },
            { title: "Event", data: "ev_title", render: function(data, type, row) {
                // Allows clicking back in to review or uncheck completion status
                return type === 'display' ? '<a href="index.php?nav=detail&id=' + row.ev_id + '">' + data + '</a>' : data;
            }},
            { title: "Time", data: "ev_time" },
            { title: "Description", data: "ev_desc" },
            { title: "Category", data: "ev_cat" }
        ]
    });

    $('#completedTable').css('background-color', '#5f7b5eff');
    $('#completedTable').css('color', '#e6e3db');
});

// completed to-dos
$(document).ready(function() {
    var completedTodoTable = $('#completedTodoTable').DataTable({
        data: completedTodoData,
        columns: [
            { title: "Date", data: "formatted_date" },
            { title: "Task", data: "todo_task" }
        ]
    });

    $('#completedTodoTable').css('background-color', '#5f7b5eff');
    $('#completedTodoTable').css('color', '#e6e3db');
});
</script>