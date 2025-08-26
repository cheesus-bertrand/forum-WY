<?php
session_start();

// Unset and destroy session
$_SESSION = array();
session_destroy();

header('location: index.php');
exit();
?>