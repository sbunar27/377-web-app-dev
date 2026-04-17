<?php
$connection = null;

function get_connection() {

    $host = 'localhost';
    $user = 'root';
    $pass = 'password';
    $db = 'solace';

    $connection = new mysqli($host, $user, $pass, $db);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}

// function login($username, $password) {
//     global $connection;

//     $stmt = $connection->prepare('SELECT id, username, password_hash FROM user WHERE username = ?');
//     $stmt->bind_param('s', $username);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $user = $result->fetch_assoc();

//     if ($user && password_verify($password, $user['password_hash'])) {
//         session_start();
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['username'] = $user['username'];
//         return true;
//     }
//     return false;
// }
?>