<?php
session_start();

// Validate the user's session once and store the result in a variable
$userValidated = isset($_SESSION['fingerPrint']) && $_SESSION['fingerPrint'] === md5($_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?= $title; ?></title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if ($userValidated) { ?>
                <li><a href="thread-create.php">Create Thread</a></li>
            <?php } ?>
        </ul>
        <ul>
            <?php if (!$userValidated) { ?>
                <li><a href="user-create.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } else { ?>
                <li><span class="user-greeting">Hello <?= htmlspecialchars($_SESSION['username']); ?></span></li>
                <li><a href="logout.php">Logout</a></li>
            <?php } ?>
        </ul>
    </nav>
    <main>
		<?php if (isset($_SESSION['error_msg'])) { ?>
			<div class="error-message"><?= htmlspecialchars($_SESSION['error_msg']); ?></div>
			<?php unset($_SESSION['error_msg']); ?>
		<?php } ?>
 