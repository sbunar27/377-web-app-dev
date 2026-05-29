<?php
include("library.php");

// Set header to return JSON data format --> fixed by Copilot/Gemini in order to properly handle AJAX responses
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = get_connection();
    
    if ($connection->connect_error) {
        http_response_code(500);
        echo json_encode(["error" => "Connection failed: " . $connection->connect_error]);
        exit;
    }

    $ev_id        = isset($_POST['ev_id']) ? intval($_POST['ev_id']) : 0;
    $ev_title     = isset($_POST['ev_title']) ? trim($_POST['ev_title']) : '';
    $ev_date      = isset($_POST['ev_date']) ? $_POST['ev_date'] : '';
    $ev_time      = isset($_POST['ev_time']) ? $_POST['ev_time'] : '';
    $ev_desc      = isset($_POST['ev_desc']) ? trim($_POST['ev_desc']) : '';
    $ev_cat       = isset($_POST['ev_cat']) ? $_POST['ev_cat'] : 'Other';
    $ev_completed = isset($_POST['ev_completed']) ? 1 : 0; 

    // Basic validation
    if ($ev_id === 0 || empty($ev_title) || empty($ev_date)) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields."]);
        exit;
    } else if ($ev_time > '22:00:00') {
        http_response_code(400);
        echo json_encode(["error" => "Events cannot be scheduled after 10pm, remember to get some sleep!"]);
        exit;
    } else if ($ev_time < '05:00:00') {
        http_response_code(400);
        echo json_encode(["error" => "Events cannot be scheduled before 5am, remember to get some rest!"]);
        exit;
    }

    // Sanitize the inputs
    $ev_title = $connection->real_escape_string($ev_title);
    $ev_date = $connection->real_escape_string($ev_date);
    $ev_time = $connection->real_escape_string($ev_time);
    $ev_desc = $connection->real_escape_string($ev_desc);
    $ev_cat = $connection->real_escape_string($ev_cat);

    $sql = "UPDATE events SET 
            ev_title = '$ev_title', 
            ev_date = '$ev_date', 
            ev_time = '$ev_time', 
            ev_desc = '$ev_desc', 
            ev_completed = $ev_completed,
            ev_cat = '$ev_cat'
            WHERE ev_id = $ev_id";

    if ($connection->query($sql)) {
        // Return success and let JavaScript handle the redirection
        echo json_encode([
            "success" => true,
            "redirect" => "index.php?date=" . urlencode($ev_date)
        ]);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Update failed: " . $connection->error]);
        exit();
    }

    $connection->close();
} else {
    // If accessed directly without POST
    header("Location: index.php");
    exit();
}
?>

<!-- old code before fixes -->
<?php
// include("library.php");

// // Check if form was actually submitted via ajax
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $connection = get_connection();
    
//     if ($connection->connect_error) {
//         die("Connection failed: " . $connection->connect_error);
//     }

//     $ev_id = isset($_POST['ev_id']) ? intval($_POST['ev_id']) : 0;
//     $ev_title = trim($_POST['ev_title']);
//     $ev_date = $_POST['ev_date'];
//     $ev_time = $_POST['ev_time'];
//     $ev_desc = trim($_POST['ev_desc']);
    
//     // Checkbox logic: If it's checked, it sends '1'. If unchecked, it sends nothing.
//     $ev_completed = isset($_POST['ev_completed']) ? 1 : 0; 

//     // Basic validation
//     if ($ev_id === 0 || empty($ev_title) || empty($ev_date)) {
//         http_response_code(400);
//         echo "Missing required fields.";
//         exit;
//     } else if ($ev_time > '22:00:00') {
//         http_response_code(400);
//         echo 'Events cannot be scheduled after 10pm, remember to get some sleep!';
//         exit;
//     } else if ($ev_time < '05:00:00') {
//         http_response_code(400);
//         echo 'Events cannot be scheduled before 5am, remember to get some rest!';
//         exit;
//     }

//     // Sanitize the inputs
//     $ev_id        = intval($_POST['ev_id']); 
//     $ev_title     = $connection->real_escape_string($_POST['ev_title']);
//     $ev_date      = $connection->real_escape_string($_POST['ev_date']);
//     $ev_time      = $connection->real_escape_string($_POST['ev_time']);
//     $ev_desc      = $connection->real_escape_string($_POST['ev_desc']);
//     $ev_completed = isset($_POST['ev_completed']) ? 1 : 0;
//     $ev_cat       = $connection->real_escape_string($_POST['ev_cat']);

//     $sql = "UPDATE events SET 
//             ev_title = '$ev_title', 
//             ev_date = '$ev_date', 
//             ev_time = '$ev_time', 
//             ev_desc = '$ev_desc', 
//             ev_completed = $ev_completed,
//             ev_cat = '$ev_cat'
//             WHERE ev_id = $ev_id";

//     if ($connection->query($sql)) {
//         header("Location: index.php?date=" . $ev_date);
//         exit();
//     } else {
//         echo "Update failed: " . $connection->error;
//     }

//     $connection->close();
// } else {
//     // If someone tries to load save.php directly in their browser without clicking "Save"
//     header("Location: index.php");
//     exit();
// }
?>