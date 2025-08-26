<?php
$title = "Thread";
require_once('header.php');
require_once('db/connex.php');

$thread_id = $_GET['id'] ?? null;
if (!$thread_id || !is_numeric($thread_id)) {
    $_SESSION['error_msg'] = "Invalid request";
    header('location: index.php');
    exit();
}

$sql_thread = "SELECT Thread.id, Thread.title, Thread.article, Thread.created_at, Thread.user_id, User.name AS author_name
		FROM Thread
		JOIN User ON Thread.user_id = User.id
		WHERE Thread.id = $thread_id";
$result_thread = mysqli_query($connex, $sql_thread);
$thread = mysqli_fetch_assoc($result_thread);

if (!$thread) {
    $_SESSION['error_msg'] = "Thread not found";
    header('location: index.php');
    exit();
}

$sql_posts = "SELECT Post.id, Post.article, Post.created_at, Post.user_id, User.name AS author_name
		FROM Post
		JOIN User ON Post.user_id = User.id
		WHERE Post.thread_id = $thread_id
		ORDER BY created_at ASC";
$result_posts = mysqli_query($connex, $sql_posts);

//$title = $thread['title'];
?>
<div class="thread-view">
	<div class="thread-item">
		<div class="thread-title-and-content">
			<h1><?= htmlspecialchars($thread['title']); ?></h1>
			<p><?= nl2br(htmlspecialchars($thread['article'])); ?></p>
		</div>
		<div class="thread-meta-compact">
			<span class="thread-date"><?= date("Y-m-d", strtotime($thread['created_at'])); ?></span>
			<span class="thread-author"><?= htmlspecialchars($thread['author_name']); ?></span>
			<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $thread['user_id']) { ?>
				<span class="thread-edit"><a href="thread-edit.php?id=<?= $thread['id']; ?>">Edit</a></span>
				<span class="thread-delete"><a href="thread-delete.php?id=<?= $thread['id']; ?>">Delete</a></span>
			<?php } ?>
		</div>
	</div>

<div class="post-list">
	<?php while ($post = mysqli_fetch_assoc($result_posts)) { ?>
		<div class="post-item">
			<div class="thread-title-and-content">
				<p><?= nl2br(htmlspecialchars($post['article'])); ?></p>
			</div>
			<div class="thread-meta-compact">
				<span class="thread-date"><?= date("Y-m-d", strtotime($post['created_at'])); ?></span>
				<span class="thread-author"><?= htmlspecialchars($post['author_name']); ?></span>
				<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post['user_id']) { ?>
					<span class="post-edit"><a href="post-edit.php?id=<?= $post['id']; ?>&thread_id=<?= $thread_id; ?>">Edit</a></span>
					<span class="post-delete"><a href="post-delete.php?id=<?= $post['id']; ?>&thread_id=<?= $thread_id; ?>">Delete</a></span>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>

	<?php if (isset($_SESSION['user_id'])) { ?>
		<?php
		$placeholder_text = (mysqli_num_rows($result_posts) === 0) ? "No replies yet. Be the first!" : "";
		?>
		<form action="post-store.php" method="post" class="reply-form">
			<input type="hidden" name="thread_id" value="<?= $thread_id; ?>">
			<label for="article">Reply</label>
			<textarea name="article" id="article" rows="5" required placeholder="<?= htmlspecialchars($placeholder_text); ?>"></textarea>
			<button type="submit">Post Reply</button>
		</form>
	<?php } ?>
</div>

<?php
mysqli_close($connex);
require_once('footer.php');
?>