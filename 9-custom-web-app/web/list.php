<h2>Books ( <span id="recordCount"></span> )</h2>


<!-- SELECTS THE PAGE THEME AND ALTERS THE CSS -->

<label for="theme">Theme: </label>
<select id="theme-selector">
    <option value="brownSugar">Brown Sugar</option>
    <option value="taro">Taro</option>
    <option value="matcha">Matcha</option>
    <option value="strawberry">Strawberry</option>
</select>

<script>
    // uses cookies to save the user's choice across pages or on reloads
    function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days*24*60*60*1000).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
    }

    const themeSelector = document.getElementById('theme-selector');

    themeSelector.addEventListener('change', (event) => {
        const selectedTheme = event.target.value;
        document.body.setAttribute('data-theme', selectedTheme);
        setCookie('selectedTheme', selectedTheme, 365);
    });

    // Apply saved theme on page load
    window.onload = () => {
        const savedTheme = localStorage.getItem('selectedTheme') || 'brownSugar';
        document.body.setAttribute('data-theme', savedTheme);
        themeSelector.value = savedTheme;
    };

    // Change theme instantly on user selection and save it
    themeSelector.addEventListener('change', (event) => {
        const selectedTheme = event.target.value;
        document.body.setAttribute('data-theme', selectedTheme);
        localStorage.setItem('selectedTheme', selectedTheme);
    });
</script>

<!-- -->

<br><br><br>

<a class="button" href="index.php?nav=detail" role="button">Create Record</a>

<br>

<table>
    <tr>
        <td class="t-header">Title</td>
        <td class="t-header">Author</td>
        <td class="t-header">Rating</td>
        <td class="t-header"># of Pages</td>
        <td class="t-header">Date started</td>
        <td class="t-header">Date finished</td>
    </tr>

<?php 

$connection = getConnection();

?>

<!-- APPLIES A FILTER TO THE LIST -->
<span class="filter-form">
    <form action="index.php" method="POST">
        <label for="filter">Filter by: </label>
        <select name="filter" id="filter">
            <option value="date-finished">Date Finished</option>
            <option value="date-started">Date Started</option>
            <option value="book-title">Book Title</option>
        </select>

        <label for="order">Order: </label>
        <select name="order" id="order">
            <option value="DESC">Descending</option>
            <option value="ASC">Ascending</option>
        </select>

        <button type="submit" name="submit-filter">Apply</button>
    </form>
</span>

<?php

$allowedFilters = [
    'date-finished' => 'book_date_finished',
    'date-started' => 'book_date_started',
    'book-title' => 'book_title'
];

$allowedOrders = ['ASC', 'DESC'];

$orderColumn = 'book_date_finished';
$orderDirection = 'DESC';


// checks if form was submitted and the filter field exists in that data
// if both conditions are true, sets $orderColumn to the corresponding
// database column name from $allowedFilters
if (isset($_POST['submit-filter'], $_POST['filter']) && array_key_exists($_POST['filter'], $allowedFilters)) {
    $orderColumn = $allowedFilters[$_POST['filter']];
}

// if both conditions are true, sets $orderDirection to the corresponding
// database column name from $allowedOrders
if (isset($_POST['order']) && in_array($_POST['order'], $allowedOrders)) {
    $orderDirection = $_POST['order'];
}

$sql = <<<SQL
    SELECT *
    FROM book
    ORDER BY $orderColumn $orderDirection;
SQL;

$result = $connection->query($sql);

$recordCount = 0;
echo "<table border='1'>";
while ($row = $result->fetch_assoc()) {

    echo "<tr>";
    echo "<td><a href='index.php?nav=detail&id=" . $row["book_id"] . "'>" . $row["book_title"] . "</a></td>";
    echo "<td>" . $row["book_author"] . "</td>";
    echo "<td>" . $row["book_rating"] . "</td>";
    echo "<td>" . $row["book_length"] . "</td>";
    echo "<td>" . $row["book_date_started"] . "</td>";
    echo "<td>" . $row["book_date_finished"] . "</td>";
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