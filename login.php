<?php
$title = "Login";
require_once('header.php');

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('location: index.php');
    exit();
}
?>
    <form action="auth.php" method="post">
        <h1>Login</h1>
      
        <label for="username">Username</label>
        <input id="username" name="username" type="text" required>
        
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>
        
        <button type="submit">Login</button>
    </form>
<?php 

require_once('footer.php');
?>