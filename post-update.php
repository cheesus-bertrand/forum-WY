<?php
session_start();
require_once('db/connex.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$post_id = $_POST['id'] ?? null;
$thread_id = $_POST['thread_id'] ?? null;
$article = $_POST['article'] ?? null;

if (!$post_id || !$thread_id || !is_numeric($post_id) || !is_numeric($thread_id) || !$article) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: thread.php?id=' . $thread_id);
    exit();
}

$article = mysqli_real_escape_string($connex, $article);

// verify ownership
$sql_check = "SELECT user_id FROM Post WHERE id = '$post_id'";
$result_check = mysqli_query($connex, $sql_check);
$post_owner = mysqli_fetch_assoc($result_check);

if (!$post_owner || $post_owner['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error_msg'] = "Unauthorized action";
    header('location: thread.php?id=' . $thread_id);
    exit();
}

// Update
$sql_update = "UPDATE Post SET article = '$article' WHERE id = '$post_id'";
mysqli_query($connex, $sql_update);

mysqli_close($connex);
header('location: thread.php?id=' . $thread_id);
exit();
?>