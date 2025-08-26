<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
	header('location: user-create.php');
	exit();
}

require_once('db/connex.php');

// regex patterns for validation
$name_pattern = '/.*\S.*/';
$username_pattern = '/[a-zA-Z0-9_]{3,16}/';
$password_pattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';

// Sanitize POST data
$name = mysqli_real_escape_string($connex, $_POST['name'] ?? '');
$username = mysqli_real_escape_string($connex, $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$dob = $_POST['dob'] ?? '';

// server-side validation using regex
if (!preg_match($name_pattern, $name) ||
	!preg_match($username_pattern, $username) ||
	!preg_match($password_pattern, $password)) {
	$_SESSION['error_msg'] = "Invalid input format."; // possibly malicious
	header("location: user-create.php");
	exit();
}

$password = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO User (name, username, password, date_of_birth)
		VALUES ('$name', '$username', '$password', '$dob')";

if(mysqli_query($connex, $sql)) {
	// successful
	header('location: login.php');
	exit();
} else {
	$_SESSION['error_msg'] = "Unable to create user";
	header('location: user-create.php');
	exit();
//    echo "Error: " . mysqli_error($connex);    
}

mysqli_close($connex);
?>