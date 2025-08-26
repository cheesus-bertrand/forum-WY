<?php
$title = "Forum Home";
require_once('header.php');
require_once('db/connex.php');

$sql = "SELECT Thread.id, Thread.title, Thread.article, Thread.created_at, Thread.user_id, User.name AS author_name
		FROM Thread
		JOIN User ON Thread.user_id = User.id
		ORDER BY created_at DESC";

$result = mysqli_query($connex, $sql);
?>
<div class="thread-list">
	<?php if (mysqli_num_rows($result) > 0) { ?>
		<?php while ($thread = mysqli_fetch_assoc($result)) { ?>
			<div class="thread-item">
				<div class="thread-title-and-content">
					<h2><a href="thread.php?id=<?= $thread['id']; ?>"><?= htmlspecialchars($thread['title']); ?></a></h2>
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
		<?php } ?>
	<?php } else { ?>
		<p>No threads have been created yet.</p>
	<?php } ?>
</div>

<?php
mysqli_close($connex);
require_once('footer.php');
?>