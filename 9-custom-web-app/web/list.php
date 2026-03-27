<h2>books ( <span id="recordCount"></span> )</h2>


<!-- SELECTS THE PAGE THEME AND ALTERS THE CSS -->
<label for="theme-selector">Theme: </label>
<select id="theme-selector">
    <option value="brownSugar">Brown Sugar</option>
    <option value="taro">Taro</option>
    <option value="matcha">Matcha</option>
    <option value="strawberry">Strawberry</option>
    <option value="butterfly">Butterfly</option>
</select>


<!-- COOKIES! WITH HELP FROM GOOGLE! -->
<script>
    // uses cookies to save the user's theme across pages or on reloads
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

    // apply saved theme on page load
    window.onload = () => {
        const savedTheme = localStorage.getItem('selectedTheme') || 'brownSugar';
        document.body.setAttribute('data-theme', savedTheme);
        themeSelector.value = savedTheme;
    };

    // change theme instantly on user selection and save it
    themeSelector.addEventListener('change', (event) => {
        const selectedTheme = event.target.value;
        document.body.setAttribute('data-theme', selectedTheme);
        localStorage.setItem('selectedTheme', selectedTheme);
    });
</script>


<br><br><br>

<a class="button" href="index.php?nav=detail" role="button">Create Record</a>



<!-- datatables way -->

<table id="bookTable" class="stripe hover"></table>
<?php

$sql =<<<SQL
SELECT *, date_format(book_date_started, '%m/%d/%Y') as 'formatted_date_started', date_format(book_date_finished, '%m/%d/%Y') as 'formatted_date_finished'
FROM book
ORDER BY book_title ASC
SQL;

$connection = getConnection();

$rows = [];
$result = $connection->query($sql);
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

print('<script>');
print('var data = ' . json_encode($rows, JSON_PARTIAL_OUTPUT_ON_ERROR) . ';');
print('</script>');
?>

<script>
    var dataTable = $('#bookTable').DataTable({
        data: data,
        columns: [
            { title: "Title", data: "book_title", render: function(data, type, row) {
                if (type === 'display') {
                    return '<a href="index.php?nav=detail&id=' + row.book_id + '">' + data + '</a>';
                }
                return data;
            }},
            // { title: "Author Full Name", data: "book_author_last", render: function(data, type, row) {
            //     if (type === 'display') {
            //         return row.book_author_first + ' ' + data;
            //     }
            //     return data;
            // }},
            { title: "Author Surname", data: "book_author_last" },
            { title: "Rating", data: "book_rating" },
            { title: "Status", data: "book_status" },
            { title: "# of Pages", data: "book_length" },
            { title: "Date Started", data: "formatted_date_started" },
            { title: "Date Finished", data: "formatted_date_finished" }
        ]
    });
</script>

<hr>





<!-- boo old table -->




<br><br>

<?php 

$connection = getConnection();

// use an associative array (with keys and values)
$allowedFilters = [
    'book_title',
    'book_rating',
    'book_date_started',
    'book_date_finished'
];

$allowedOrders = ['ASC', 'DESC'];

// the ?? operator says that if something is null, make it this instead (google)
$statusFilters = $_GET['status'] ?? [];
$currentFilter = $_GET['filter'] ?? 'book_date_finished';
$currentOrder = $_GET['order'] ?? 'DESC';

$orderColumn = 'book_date_finished'; // default column
$orderDirection = 'DESC'; // default direction

if (isset($_GET['filter'])) {
    $filter = $_GET['filter']; // get the filter from the URL

    // check if the filter is one of the allowed options
    if (in_array($filter, $allowedFilters)) {
        $orderColumn = $filter;
    }
}

$allowedOrders = ['ASC', 'DESC'];

if (isset($_GET['order'])) {
    $order = $_GET['order']; // get the order from the URL

    if (in_array($order, $allowedOrders)) {
        $orderDirection = $order;
    }
}

?>

<table>

    <tr>
        <?= sortHeader('Title', 'book_title', $currentFilter, $currentOrder) ?>
        <th>Author</th>
        <?= sortHeader('Rating', 'book_rating', $currentFilter, $currentOrder) ?>
        <th>Status</th>
        <th># of Pages</th>
        <?= sortHeader('Date Started', 'book_date_started', $currentFilter, $currentOrder) ?>
        <?= sortHeader('Date Finished', 'book_date_finished', $currentFilter, $currentOrder) ?>
    </tr>


<?php 


function buildSortURL($filterKey, $currentFilter, $currentOrder) {
    // copy current GET parameters
    $parameters = $_GET;

    // toggle order if clicking the same filter, else default to DESC
    if ($filterKey === $currentFilter && $currentOrder === 'DESC') {
        $parameters['order'] = 'ASC';
    } else {
        $parameters['order'] = 'DESC';
    }

    $parameters['filter'] = $filterKey;

    // build link
    return 'index.php?' . http_build_query($parameters);
}

function sortHeader($label, $filterKey, $currentFilter, $currentOrder) {
    $url = buildSortURL($filterKey, $currentFilter, $currentOrder);
    if ($filterKey === $currentFilter) {
        if ($currentOrder === 'ASC') {
            $arrow = ' ▲';
        } else {
            $arrow = ' ▼';
        }
    } else {
        $arrow = '';
    }
    return '<th><a href="' . $url . '">' . $label . $arrow . '</a></th>';
}


?>

<!-- APPLIES FILTER BASED ON READ, NOT READ, OR NOT FINISHED -->
<span>
  <form id="statusFilterForm" action="index.php" method="GET">
    <fieldset>
      <legend>Read Status</legend>
      <label>
        <?php
            $checked = '';
            if (isset($_GET['status'])) {
                if (in_array('R', $_GET['status'])) {
                $checked = 'checked';
                }
            }
        ?>
        <input type="checkbox" name="status[]" value="R" <?php echo $checked; ?>
          onclick="statusChange(this)">
        Read
      </label>
      <label>
        <?php
            $checked = '';
            if (isset($_GET['status'])) {
                if (in_array('NR', $_GET['status'])) {
                $checked = 'checked';
                }
            }
        ?>
        <input type="checkbox" name="status[]" value="NR" <?php echo $checked; ?>
          onclick="statusChange(this)">
        Not Read (TBR)
      </label>
      <label>
        <?php
            $checked = '';
            if (isset($_GET['status'])) {
                if (in_array('NF', $_GET['status'])) {
                $checked = 'checked';
                }
            }
        ?>
        <input type="checkbox" name="status[]" value="NF" <?php echo $checked; ?>
          onclick="statusChange(this)">
        Not Finished
      </label>
    </fieldset>

    <!-- preserve current filter and order, use htmlspecialchars to make input safer (special characters turn into html entities)  -->
    <input type="hidden" name="filter" value="<?= htmlspecialchars($_GET['filter'] ?? 'book_date_finished') ?>">
    <input type="hidden" name="order" value="<?= htmlspecialchars($_GET['order'] ?? 'DESC') ?>">
  </form>
</span>

<script>
    function statusChange(checkbox){
        document.getElementById('statusFilterForm').submit();
    }
</script>

<?php 

$sql = "SELECT * FROM book";

// check if there are any status filters selected and if it's an array
if (!empty($statusFilters) && is_array($statusFilters)) {
    $validStatuses = ['R', 'NR', 'NF'];
    $filteredStatuses = [];

    // look at each status the user selected
    foreach ($statusFilters as $status) {
        // if the status is one of the allowed ones, 
        // add it to the filtered list
        if (in_array($status, $validStatuses)) {
            $filteredStatuses[] = $status;
        }
    }

    if (!empty($filteredStatuses)) {
        // start building SQL WHERE clause
        $sql .= " WHERE book_status IN (";

        // add each status to the SQL query, wrapped in quotes
        $first = true; // to handle commas correctly
        foreach ($filteredStatuses as $status) {
            if ($first) {
                $sql .= "'$status'"; // add the first status without a comma
                $first = false;
            } else {
                $sql .= ", '$status'"; // add a comma before the next statuses
            }
        }
        $sql .= ")";
    }
}

// Add ORDER BY clause
$sql .= " ORDER BY $orderColumn $orderDirection";


$result = $connection->query($sql);


$recordCount = 0;
while ($row = $result->fetch_assoc()) {
    $rowClass = ($row['book_status'] === 'R') ? 'read-row' : '';

    echo "<tr>";
    echo "<td><a href='index.php?nav=detail&id=" . $row["book_id"] . "'>" . $row["book_title"] . "</a></td>";
    echo "<td>" . $row["book_author"] . "</td>";
    echo "<td>" . $row["book_rating"] . "</td>";
    echo "<td class='{$rowClass}'>" . $row["book_status"] . "</td>";
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