<?php
include_once("library.php");

$connection = get_connection();
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// get ONLY completed events, sorted by most recent date
$sql = "SELECT *, date_format(ev_date, '%m/%d/%Y') as 'formatted_date'
        FROM events
        WHERE ev_completed = 1
        ORDER BY ev_date DESC, ev_time ASC";

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
?>

<div id="completed-section">
    <h2>Completed Events</h2>
    <p>Here is a log of everything you've crossed off your calendar!</p>
    <br>
    
    <table id="completedTable" class="stripe hover dataTables"></table>
    
    <br>
    <a class="button" href="index.php?nav=agenda">Back to Agenda</a>
</div>

<script>
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
            { title: "Description", data: "ev_desc" }
        ]
    });

    $('#completedTable').css('background-color', '#5f7b5eff');
    $('#completedTable').css('color', '#e6e3db');
});
</script>