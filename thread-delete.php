<?php
session_start();
require_once('db/connex.php');

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_SESSION['user_id'])) {
	header('location: login.php');
	exit();
}

$thread_id = $_GET['id'] ?? null;
if (!$thread_id || !is_numeric($thread_id)) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: index.php');
    exit();
}

$thread_id = mysqli_real_escape_string($connex, $thread_id);

// verify ownership
$sql_check = "SELECT user_id FROM Thread WHERE id = '$thread_id'";
$result_check = mysqli_query($connex, $sql_check);
$thread_owner = mysqli_fetch_assoc($result_check);

if (!$thread_owner || $thread_owner['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error_msg'] = "Unauthorized action";
    header('location: index.php');
    exit();
}

// Delete
$sql_delete = "DELETE FROM Thread WHERE id = '$thread_id'";
mysqli_query($connex, $sql_delete);

mysqli_close($connex);
header('location: index.php');
exit();
?>