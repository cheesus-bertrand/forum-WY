<?php
session_start();
require_once('db/connex.php');

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_SESSION['user_id'])) {
	header('location: login.php');
	exit();
}

$post_id = $_GET['id'] ?? null;
$thread_id = $_GET['thread_id'] ?? null;

if (!$post_id || !$thread_id || !is_numeric($post_id) || !is_numeric($thread_id)) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: index.php');
    exit();
}

$post_id = mysqli_real_escape_string($connex, $post_id);

// verify ownership
$sql_check = "SELECT user_id FROM Post WHERE id = '$post_id'";
$result_check = mysqli_query($connex, $sql_check);
$post_owner = mysqli_fetch_assoc($result_check);

if (!$post_owner || $post_owner['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error_msg'] = "Unauthorized action";
    header('location: thread.php?id=' . $thread_id);
	exit();
}

// Delete
$sql_delete = "DELETE FROM Post WHERE id = '$post_id'";
mysqli_query($connex, $sql_delete);

mysqli_close($connex);
header('location: thread.php?id=' . $thread_id);
exit();
?>