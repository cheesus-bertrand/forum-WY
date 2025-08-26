<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header('location: login.php');
    exit();
}

require_once('db/connex.php');

// Sanitize POST data
foreach ($_POST as $key => $value) {
    $$key = mysqli_real_escape_string($connex, $value);
}

$sql = "SELECT id, username, password FROM User WHERE username = '$username'";
$result = mysqli_query($connex, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    $dbPassword = $user['password'];

    if (password_verify($password, $dbPassword)) {
        // Success
        session_regenerate_id();
        // fingerprint
        $_SESSION['fingerPrint'] = md5($_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]);
        // Store session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('location: index.php');
        exit();
    } else {
        // Failed
        $_SESSION['error_msg'] = "bad password";
        header('location: login.php');
        exit();
    }
} else {
    // User not found (or multiple users found)
    $_SESSION['error_msg'] = "bad user";
    header('location: login.php');
    exit();
}
?>