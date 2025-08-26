<?php
$title = "Create User";
require_once('header.php');

// already logged in
if (isset($_SESSION['user_id'])) {
	header('location: index.php');
	exit();
}
?>
	<h1>Create a New User</h1>
	<form action="user-store.php" method="post">
		<label for="name">Name</label>
		<input 
			type="text" 
			name="name" 
			id="name" 
			pattern=".*\S.*" 
			title="Display Name must not be empty" 
			required>
		
		<label for="username">Username</label>
		<input 
			type="text" 
			name="username" 
			id="username" 
			pattern="[a-zA-Z0-9_]{3,16}" 
			title="Username must be 3-16 characters long and contain only letters, numbers, or underscores." 
			required>
		
		<label for="password">Password</label>
		<input 
			type="password" 
			name="password" 
			id="password" 
			pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
			title="Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number." 
			required>
		
		<label for="dob">Date of Birth</label>
		<input type="date" name="dob" id="dob">
		
		<button type="submit">Create User</button>
	</form>
<?php 
require_once('footer.php');
?>