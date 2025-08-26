<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header('location: index.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "Must login to post";
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

require_once('db/connex.php');

// Sanitize form data
$thread_id = mysqli_real_escape_string($connex, $_POST['thread_id']);
$article = mysqli_real_escape_string($connex, $_POST['article']);
// should use mysqli_prepare() and mysqli_stmt_bind_param()

// validate thread id
if (!is_numeric($thread_id)) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: thread.php?id=' . $thread_id);
    exit();
}

$sql = "INSERT INTO Post (article, user_id, thread_id) 
        VALUES ('$article', '$user_id', '$thread_id')";

if (mysqli_query($connex, $sql)) {
    // Success
    header('location: thread.php?id=' . $thread_id);
    exit();
} else {
    $_SESSION['error_msg'] = "unable to create thread";
    header('location: thread.php?id=' . $thread_id);
    exit();
//    echo "Error: " . mysqli_error($connex);
}

mysqli_close($connex);
?>