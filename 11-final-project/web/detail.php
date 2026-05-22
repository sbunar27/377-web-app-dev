<?php
include_once("library.php");

$connection = get_connection();
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// get the event ID from the URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id === 0) {
    die("Invalid Event ID.");
}

// instead of using a direct query, use a prepared statement to prevent SQL injection --> fixed by Gemini/Copilot
$stmt = $connection->prepare("SELECT ev_title, ev_desc, ev_date, ev_time, ev_completed FROM events WHERE ev_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();
$stmt->close();
$connection->close();
?>

<div id="event-detail">
    <h3>Edit Event</h3>
    
    <form action="save.php" method="POST">
        
        <input type="hidden" name="ev_id" value="<?php echo htmlspecialchars($event_id); ?>">

        <div class="form-group">
            <label for="ev_title"><strong>Event Title:</strong></label><br>
            <input type="text" id="ev_title" class="form-control" name="ev_title" value="<?php echo htmlspecialchars($event['ev_title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="ev_date"><strong>Date:</strong></label><br>
            <input type="date" id="ev_date" class="form-control" name="ev_date" value="<?php echo htmlspecialchars($event['ev_date']); ?>" required>
        </div>

        <div class="form-group">
            <label for="ev_time"><strong>Time:</strong></label><br>
            <input type="time" id="ev_time" class="form-control" name="ev_time" value="<?php echo htmlspecialchars($event['ev_time']); ?>">
        </div>

        <div class="form-group">
            <label for="ev_desc"><strong>Description:</strong></label><br>
            <textarea id="ev_desc" class="form-control" name="ev_desc" rows="5"><?php echo htmlspecialchars($event['ev_desc']); ?></textarea>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="ev_completed" value="1" <?php echo ($event['ev_completed'] == 1) ? 'checked' : ''; ?>>
                <strong>Mark as Completed</strong>
            </label>
        </div>

        <button type="submit" class="button">Save Changes</button>
        <a class="button" href="index.php?nav=agenda">Cancel</a>
        <a class="death-button button" href="delete.php?id=<?php echo $event_id; ?>" 
            onclick="return confirm('Are you sure you want to delete this event?');">
            Delete Event
        </a>
    </form>
</div>