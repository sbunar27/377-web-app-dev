<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>event details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="leaf.png">
</head>
<body>

<?php
include("library.php");

$connection = get_connection();
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get the event ID from the URL (e.g., detail.php?id=5)
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id === 0) {
    die("Invalid Event ID.");
}

// Fetch the event data securely using a prepared statement (Google help)
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

<div id="event-detail-section" style="max-width: 600px; margin: 20px auto; font-family: sans-serif;">
    <h2>Edit Event</h2>
    
    <form action="save.php" method="POST">
        
        <input type="hidden" name="ev_id" value="<?php echo htmlspecialchars($event_id); ?>">

        <div style="margin-bottom: 15px;">
            <label for="ev_title"><strong>Event Title:</strong></label><br>
            <input type="text" id="ev_title" name="ev_title" value="<?php echo htmlspecialchars($event['ev_title']); ?>" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ev_date"><strong>Date:</strong></label><br>
            <input type="date" id="ev_date" name="ev_date" value="<?php echo htmlspecialchars($event['ev_date']); ?>" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ev_time"><strong>Time:</strong></label><br>
            <input type="time" id="ev_time" name="ev_time" value="<?php echo htmlspecialchars($event['ev_time']); ?>" style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ev_desc"><strong>Description:</strong></label><br>
            <textarea id="ev_desc" name="ev_desc" rows="5" style="width: 100%; padding: 8px;"><?php echo htmlspecialchars($event['ev_desc']); ?></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>
                <input type="checkbox" name="ev_completed" value="1" <?php echo ($event['ev_completed'] == 1) ? 'checked' : ''; ?>>
                <strong>Mark as Completed</strong>
            </label>
        </div>

        <button type="submit" style="padding: 10px 20px; background-color: #588157; color: white; border: none; cursor: pointer;">Save Changes</button>
        <a href="index.php" style="margin-left: 10px; color: #555; text-decoration: none;">Cancel</a>
        <a href="delete.php?id=<?php echo $event_id; ?>" 
            onclick="return confirm('Are you sure you want to delete this event?');" 
            style="margin-left: 20px; color: #5c2323ff;">
            Delete Event
        </a>
    </form>
</div>