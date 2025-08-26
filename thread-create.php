<?php
$title = "Create Thread";
require_once('header.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "Must login to post";
    header('location: login.php');
	exit();
}
?>
	<h1>Create a New Thread</h1>
	<form action="thread-store.php" method="post">
		<label for="title">Thread Title</label>
		<input type="text" name="title" id="title" required>
		
		<label for="article">Article Content</label>
		<textarea name="article" id="article" rows="10" required></textarea>
		
		<button type="submit">Publish Thread</button>
	</form>
<?php 
require_once('footer.php');
?>