<?php
$title = "Edit Thread";
require_once('header.php');
require_once('db/connex.php');

$thread_id = $_GET['id'] ?? null;
if (!$thread_id || !is_numeric($thread_id)) {
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
$sql = "SELECT title, article, user_id FROM Thread WHERE id = $thread_id";
$result = mysqli_query($connex, $sql);
$thread = mysqli_fetch_assoc($result);

if (!$thread || $thread['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error_msg'] = "Unauthorized action";
    header('location: index.php');
    exit();
}
?>
    <h1>Edit Thread</h1>
    <form action="thread-update.php" method="post">
        <input type="hidden" name="id" value="<?= $thread_id; ?>">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($thread['title']); ?>" required>

        <label for="article">Article</label>
        <textarea name="article" id="article" rows="10" required><?= htmlspecialchars($thread['article']); ?></textarea>

        <button type="submit">Update Thread</button>
    </form>

<?php
mysqli_close($connex);
require_once('footer.php');
?>