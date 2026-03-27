<?php

/*************************************************************************************************
 * detail.php
 *
 * Displays the details for a single book review. This page expects to be included within index.php.
 *************************************************************************************************/

// define variable for each book review attribute
$title = "";
$author_first = "";
$author_last = "";
$rating = "";
$review = "";
$status = "";
$genre = "";
$length = "";
$dateStarted = "";
$dateFinished = "";

// only query database if id supplied
if (isset($id)){
    $sql =<<<SQL
    SELECT *
    FROM book
    WHERE book_id = $id
    SQL;

    $connection = getConnection();

    // Run the query on the database
    $result = $connection->query($sql);

    // Store the ONE result in an associative array
    $row = $result->fetch_assoc();

    $id = $row["book_id"];
    $author_first = $row["book_author_first"];
    $author_last = $row["book_author_last"];
    $title = $row["book_title"];
    $genre = $row["book_genre"];
    $status = $row["book_status"];
    $rating = $row["book_rating"];
    $review = $row["book_review"];
    $length = $row["book_length"];
    $dateStarted = $row["book_date_started"];
    $dateFinished = $row["book_date_finished"];
} else {
    $id = "";
}

?>

<br><br><br><br><br><br><br>

<span class="main">
    <h2 id="title-header">
    <?php
        if (isset($id) && $id > 0) {
            echo "$title";
        } else {
            echo "Add New Book Review";
        }
    ?></h2>

    <form id="bookForm" action="save.php" method="POST">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

        <div>
            <label for="title">Title *</label>
            <input type="text" name="title" id="title" value="<?php echo $title; ?>">
        </div>

        <div>
            <label for="title">Author First Name</label>
            <input type="text" name="author_first" id="author_first" value="<?php echo $author_first; ?>">
        </div>

        <div>
            <label for="title">Author Last Name</label>
            <input type="text" name="author_last" id="author_last" value="<?php echo $author_last; ?>">
        </div>

        <div>
            <label for="title">Read Status *</label>
            <!-- <input type="text"  name="status" value="<?php echo $status; ?>"> -->
            <select name="status">
                <option value="R" <?php if ($status == 'R') echo 'selected'; ?>>R</option>
                <option value="NR" <?php if ($status == 'NR') echo 'selected'; ?>>NR</option>
                <option value="NF" <?php if ($status == 'NF') echo 'selected'; ?>>NF</option>
            </select>

        </div>

        <div>
            <label for="genre">Genre</label>
            <input type="text" name="genre" id="genre" value="<?php echo $genre; ?>">
        </div>

        <div>
            <label for="rating">Rating</label>
            <input type="number" name="rating" id="rating" value="<?php echo $rating; ?>">
        </div>

        <div>
            <label for="review"  style="float:inline-start;">Review&ensp;</label>
            <textarea id="reviewInput" name="review" rows="5" cols="40"><?php echo $review; ?></textarea>

        </div>

        <div>
            <label for="length">Length (Pages)</label>
            <input type="number" name="length" id="length" value="<?php echo $length; ?>">
        </div>

        <div>
            <label for="date_started">Date Started YYYY-MM-DD </label>
            <input type="text" name="dateStarted" id="dateStarted" value="<?php echo $dateStarted; ?>">
        </div>

        <div>
            <label for="date_finished">Date Finished YYYY-MM-DD </label>
            <input type="text" name="dateFinished" id="dateFinished" value="<?php echo $dateFinished; ?>">
        </div>

        <br>

        <p><em>* indicates a required field</em></p>

        <button type="button" onclick="save()">Save</button>
        <?php
        if (isset($id) && $id > 0) {
            echo "<a class='button' href='delete.php?id=$id' role='button'>Delete</a>";
        }
        ?>
        <a class="button" href="index.php?content=list" role="button">Cancel</a>
    </form>
</span>

<script>
    function save(){
        var settings = {
            'async': true,
            'url': 'save.php?id=' + $('#id').val() +
            '&title=' + $('#title').val() +
            '&dateStarted=' + $('#dateStarted').val() +
            '&dateFinished=' + $('#dateFinished').val() +
            '&author_first=' + $('#author_first').val() +
            '&author_last=' + $('#author_last').val() +
            '&status=' + $('select[name="status"]').val() +
            '&genre=' + $('#genre').val() +
            '&rating=' + $('#rating').val() +
            '&review=' + $('#reviewInput').val() +
            '&length=' + $('#length').val(),
            'method': 'POST',
            'headers': {
                'Cache-Control': 'no-cache'
            }
        };

        $.ajax(settings).done(function(response) {
            console.log(response);
            const id = $('#id').val();
            if (id == "") {
                $('#id').val(response);
            }

            $('#title-header').html($('#title').val());
            showAlert('success', 'Save Successful!', 'Your book review has been saved.');
            $('#results').html('Record saved successfully!');
        }).fail(function() {
            // showAlert('danger', 'Save Failed!', 'Check your input and try again.');
            $('#results').html('Error saving record. Please check your input and try again.');
            showAlert('danger', 'Save Failed!', 'Error saving record. Please check your input and try again.');
        });
    }
</script>