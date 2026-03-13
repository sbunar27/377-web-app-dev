<?php

/*************************************************************************************************
 * detail.php
 *
 * Displays the details for a single book review. This page expects to be included within index.php.
 *************************************************************************************************/

// define variable for each book review attribute
$title = "";
$author = "";
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
    $author = $row["book_author"];
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

<span class="main">
    <h2><?php echo $title; ?></h2>
    <form action="save.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div>
            <label for="title">Title</label>
            <input type="text" id="title-box" name="title" value="<?php echo $title; ?>">
        </div>

        <div>
            <label for="title">Author</label>
            <input type="text" name="author" value="<?php echo $author; ?>">
        </div>

        <div>
            <label for="title">Read Status</label>
            <!-- <input type="text"  name="status" value="<?php echo $status; ?>"> -->
            <select name="status">
                <option value="R" <?php if ($status == 'R') echo 'selected'; ?>>R</option>
                <option value="NR" <?php if ($status == 'NR') echo 'selected'; ?>>NR</option>
                <option value="NF" <?php if ($status == 'NF') echo 'selected'; ?>>NF</option>
            </select>

        </div>

        <div>
            <label for="genre">Genre</label>
            <input type="text" name="genre" value="<?php echo $genre; ?>">
        </div>

        <div>
            <label for="rating">Rating</label>
            <input type="number" name="rating" value="<?php echo $rating; ?>">
        </div>

        <div>
            <label for="review"  style="float:inline-start;">Review&ensp;</label>
            <textarea id="reviewInput" name="review" rows="5" cols="40"><?php echo $review; ?></textarea>

        </div>

        <div>
            <label for="length">Length (Pages)</label>
            <input type="number" name="length" value="<?php echo $length; ?>">
        </div>

        <div>
            <label for="date_started">Date Started (YYYY/MM/DD)</label>
            <input type="datetime" name="dateStarted" value="<?php echo $dateStarted; ?>">
        </div>

        <div>
            <label for="date_finished">Date Finished (YYYY/MM/DD)</label>
            <input type="datetime" name="dateFinished" value="<?php echo $dateFinished; ?>">
        </div>

        <button type="submit">Save</button>
        <?php
        if ($id != "" || !isset($id))
        {
            echo "<a class='button' href='delete.php?id=$id' role='button'>Delete</a>";
        }
        ?>
        <a class="button" href="index.php?content=list" role="button">Cancel</a>
    </form>
</span>