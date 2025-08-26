<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header('location: thread-create.php');
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
$title = mysqli_real_escape_string($connex, $_POST['title']);
$article = mysqli_real_escape_string($connex, $_POST['article']);
// should use mysqli_prepare() and mysqli_stmt_bind_param()

$sql = "INSERT INTO Thread (title, article, user_id) 
        VALUES ('$title', '$article', '$user_id')";

if (mysqli_query($connex, $sql)) {
    // success
    header('location: index.php');
    exit();
} else {
    // fail
    $_SESSION['error_msg'] = "unable to create thread";
    header('location: thread-create.php');
    exit();
//    echo "Error: " . mysqli_error($connex);
}

mysqli_close($connex);
?>