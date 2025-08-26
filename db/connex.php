<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'maisonneuve_forum';
$dbPort = 3306;
$connex = null;

$isSetupMode = isset($_GET['setup']) && $_GET['setup'] === 'true';

try {
	if ($isSetupMode) {
		$connex = mysqli_connect($dbHost, $dbUser, $dbPass, null, $dbPort);
	} else {
		$connex = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
	}
} catch (mysqli_sql_exception $error) {
	// no db, give setup link
	if (strpos($error->getMessage(), "Unknown database") !== false) {
		echo "<h1>Database not found!</h1>
		<p><a href='db/connex.php?setup=true'>Click here to run the setup script.</a></p>";
	} else {
		echo "Connection error " . $error->getMessage();
	}
    require_once(__DIR__ . "/../footer.php");
    exit();
}

mysqli_set_charset($connex, "utf8");

// setup mode, run schema script
if ($isSetupMode) {

    // in case of reset, wipe sessions
    session_start();
    session_destroy();

	$sqlScript = file_get_contents('schema.sql');
	if ($sqlScript === false) {
		die("Error: Could not read schema.sql file.");
	}

	// Use mysqli_multi_query to execute the entire script
	if (mysqli_multi_query($connex, $sqlScript)) {
        // fetch all results to prevent commands from getting stuck
		do {
			if ($result = mysqli_store_result($connex)) {
				mysqli_free_result($result);
			}
		} while (mysqli_more_results($connex) && mysqli_next_result($connex));

	} else {
		die("Error creating database and tables: " . mysqli_error($connex));
	}
	
	mysqli_close($connex);
    header("location: ../index.php");

	exit();
}