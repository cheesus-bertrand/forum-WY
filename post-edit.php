<?php
$title = "Edit Post";
require_once('header.php');
require_once('db/connex.php');

$post_id = $_GET['id'] ?? null;
$thread_id = $_GET['thread_id'] ?? null;

if (!$post_id || !$thread_id || !is_numeric($post_id) || !is_numeric($thread_id)) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: index.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "Must login to edit post";
    header('location: login.php');
	exit();    
}

// verify ownership
$sql = "SELECT article, user_id, thread_id FROM Post WHERE id = '$post_id'";
$result = mysqli_query($connex, $sql);
$post = mysqli_fetch_assoc($result);

if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error_msg'] = "You are not the author";
	header('location: thread.php?id=' . $thread_id);
	exit();
}

?>
	<h1>Edit Post</h1>
	<form action="post-update.php" method="post">
		<input type="hidden" name="id" value="<?= $post_id; ?>">
		<input type="hidden" name="thread_id" value="<?= $post['thread_id']; ?>">
		
		<label for="article">Post Content</label>
		<textarea name="article" id="article" rows="10" required><?= htmlspecialchars($post['article']); ?></textarea>
		
		<button type="submit">Update Post</button>
	</form>

<?php 

mysqli_close($connex);
require_once('footer.php');
?>