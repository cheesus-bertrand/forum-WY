<?php
session_start();
require_once('db/connex.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$thread_id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? null;
$article = $_POST['article'] ?? null;

// Validate inputs
if (!$thread_id || !is_numeric($thread_id) || !$title || !$article) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: index.php');
    exit();
}

$title = mysqli_real_escape_string($connex, $title);
$article = mysqli_real_escape_string($connex, $article);
// should use mysqli_prepare() and mysqli_stmt_bind_param()


// verify ownership
$sql_check = "SELECT user_id FROM Thread WHERE id = '$thread_id'";
$result_check = mysqli_query($connex, $sql_check);
$thread_owner = mysqli_fetch_assoc($result_check);

if (!$thread_owner || $thread_owner['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error_msg'] = "Unauthorized action";
    header('location: index.php');
    exit();
}

// Update
$sql_update = "UPDATE Thread SET title = '$title', article = '$article' WHERE id = '$thread_id'";
mysqli_query($connex, $sql_update);

mysqli_close($connex);
header('location: thread.php?id=' . $thread_id);
exit();
?>