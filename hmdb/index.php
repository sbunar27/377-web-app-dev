<html>
    <head>
        <title>hMDb</title>
        <!-- favicon -->
        <link rel="icon" type="image/x-icon" href="favicon_io/favicon.ico"/>   
        
        <style>
            body{
                font-family: Verdana, sans-serif;
                background-color: #131313ff;
                color: #ffffff;
                margin-left: 100px;
                margin-right: 100px;
            }
            h1, h2 {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-weight: 800;
                color: #ffcc00ff;
            }
            h2 {
                font-size: 28px;
            }
            p {
                font-size: 18px;
            }
            a {
                color: #fff7d8ff;
            }
            td{
                padding: 8px;
                border-bottom: 1px solid #444444;
                width: 200px;
            }
            .navbar {
                margin-bottom: 20px;
                background-color: #232222ff;
                padding: 10px;
            }
        </style>

    </head>
    <body>
        <h1>hMDb: The Hanover Movie Database</h1>

        <h2>Movies ( <span id="recordCount"></span> )</h2>

<?php
echo "<a href='index.php' style='margin-right: 10px; color: #ffcc00ff; text-decoration: underline;'>ALL</a> ";
for ($i = 0; $i < 10; $i++) {
    $number = chr($i + ord('0'));
    echo "<a href='index.php?filter=$number' style='margin-right: 10px; color: #ffcc00ff; text-decoration: underline;'>$number</a> ";
}   
for ($i = 0; $i < 26; $i++) {
    $letter = chr($i + ord('A'));
    echo "<a href='index.php?filter=$letter' style='margin-right: 10px; color: #ffcc00ff; text-decoration: underline;'>$letter</a> ";
}   
?>

        <table>
            <tr>
                <td>Title</td>
                <td>Release</td>
                <td>Rating</td>
                <td>Duration (minutes)</td>
            </tr>

<?php 

$servername = "localhost";
$username = None;
$password = None;
$dbname = "hmdb";

// connect to database and make sure it was successful
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

extract($_REQUEST);
if (!isset($filter)) {
    $filter = "";
}
else {
    $filter = $connection->real_escape_string($filter);
}
$filter = $filter . "%";

$sql = <<<SQL
    SELECT *
    FROM movie
    WHERE mov_title LIKE '$filter'
    ORDER BY mov_title ASC;
SQL;
$result = $connection->query($sql);

$recordCount = 0;
echo "<table border='1'>";
while ($row = $result->fetch_assoc()) {

    echo "<tr>";
    echo "<td><a href='detail.php?id=" . $row["mov_id"] . ">" . $row["mov_title"] . "</a></td>";
    echo "<td>" . $row["mov_release_year"] . "</td>";
    echo "<td>" . $row["mov_rating"] . "</td>";
    echo "<td>" . $row["mov_duration"] . "</td>";
    echo "</tr>";
    $recordCount++;
}

?>

        </table>
<?php

$code =<<<JS
<script>
    document.getElementById('recordCount').innerHTML = "$recordCount records";
</script>
JS;

echo $code;

?>

    </body>
</html>
