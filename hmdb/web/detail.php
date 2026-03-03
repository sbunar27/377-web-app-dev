<?php

/*************************************************************************************************
 * detail.php
 *
 * Displays the details for a single movie. This page expects to be included within index.php.
 *************************************************************************************************/

// define variable for each movie attribute
$title = "";
$genre = "";
$rating = "";
$mpaa = "";
$duration = "";
$releaseYear = "";

// only query database if id supplied
if (isset($id)){
    $sql =<<<SQL
    SELECT *
    FROM movie
    WHERE mov_id = $id
    SQL;
    $idMade = True;

    $connection = getConnection();

    // Run the query on the database
    $result = $connection->query($sql);

    // Store the ONE result in an associative array
    $row = $result->fetch_assoc();

    $id = $row["mov_id"];
    $title = $row["mov_title"];
    $genre = $row["mov_genre"];
    $rating = $row["mov_rating"];
    $mpaa = $row["mov_mpaa"];
    $duration = $row["mov_duration"];
    $releaseYear = $row["mov_release_year"];
} else {
    $id = "";
}

?>

<h2><?php echo $title; ?></h2>

<form action="save.php" method="POST">
    <!-- <div>
        <label for="id" class="form-label">ID</label>
        <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>">
    </div> -->

    <div>
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
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
        <label for="mpaa" class="form-label">MPAA</label>
        <input type="text" class="form-control" name="mpaa" value="<?php echo $mpaa; ?>">
    </div>

    <div>
        <label for="duration" class="form-label">Duration</label>
        <input type="text" class="form-control" name="duration" value="<?php echo $duration; ?>">
    </div>

    <div>
        <label for="release_year" class="form-label">Release Year</label>
        <input type="text" class="form-control" name="release_year" value="<?php echo $releaseYear; ?>">
    </div>

    <button type="submit">Save</button>
    <?php
    if ($id != "" || !isset($id))
    {
        echo "<a class='button' href='delete.php?id=<?php echo $id; ?>' role='button'>Delete</a>";
    }
    ?>
    <a class="button" href="index.php?content=list" role="button">Cancel</a>
</form>