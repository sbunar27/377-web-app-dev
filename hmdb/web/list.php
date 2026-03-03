
<h2>Movies ( <span id="recordCount"></span> )</h2>

<a href='index.php?nav=list&' style='margin-right: 10px; color: #ffcc00ff; text-decoration: underline;'>ALL</a>

<?php
for ($i = 0; $i < 10; $i++) {
    $number = chr($i + ord('0'));
    echo "<a href='index.php?nav=list&filter=$number' style='margin-right: 10px; color: #ffcc00ff; text-decoration: underline;'>$number</a> ";
}   
for ($i = 0; $i < 26; $i++) {
    $letter = chr($i + ord('A'));
    echo "<a href='index.php?nav=list&filter=$letter' style='margin-right: 10px; color: #ffcc00ff; text-decoration: underline;'>$letter</a> ";
}   
?>

<a class="button" href="index.php?nav=detail" role="button">Create</a>

<table>
    <tr>
        <td class="t-header">Title</td>
        <td class="t-header">Release</td>
        <td class="t-header">Rating</td>
        <td class="t-header">Duration (minutes)</td>
    </tr>

<?php 

$connection = getConnection();

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
    echo "<td><a href='index.php?nav=detail&id=" . $row["mov_id"] . "'>" . $row["mov_title"] . "</a></td>";
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