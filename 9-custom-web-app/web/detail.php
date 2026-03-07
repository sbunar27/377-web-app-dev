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
        <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>">

        <div>
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
        </div>

        <div>
            <label for="title" class="form-label">Author</label>
            <input type="text" class="form-control" name="author" value="<?php echo $author; ?>">
        </div>

        <div>
            <label for="title" class="form-label">Read Status (R, NR, NF)</label>
            <input type="text" class="form-control" name="status" value="<?php echo $status; ?>">
        </div>

        <div>
            <label for="genre" class="form-label">Genre</label>
            <input type="text" class="form-control" name="genre" value="<?php echo $genre; ?>">
        </div>

        <div>
            <label for="rating" class="form-label">Rating</label>
            <input type="text" class="form-control" name="rating" value="<?php echo $rating; ?>">
        </div>

        <div>
            <label for="review" class="form-label">Review</label>
            <textarea id="reviewInput" name="review" rows="5" cols="40"><?php echo $review; ?></textarea>

        </div>

        <div>
            <label for="length" class="form-label">Length</label>
            <input type="text" class="form-control" name="length" value="<?php echo $length; ?>">
        </div>

        <div>
            <label for="date_started" class="form-label">Date Started (YYYY/MM/DD)</label>
            <input type="datetime" class="form-control" name="dateStarted" value="<?php echo $dateStarted; ?>">
        </div>

        <div>
            <label for="date_finished" class="form-label">Date Finished (YYYY/MM/DD)</label>
            <input type="datetime" class="form-control" name="dateFinished" value="<?php echo $dateFinished; ?>">
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