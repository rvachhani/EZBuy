<?php
	require 'config.php';
	require 'connect.php';
	require 'logout.php';
	require 'check_credentials.php';

	//Start the session and initilize the err message string
	session_start();
	$_SESSION['err']='';

	//Connect to the database
	$dbname = "zeni";
	dbconnect($config['dbhost'], $dbname, $config['dbusername'], $config['dbpassword']);

	//If the user clicks the login button, run the check credentials function
	if (isset($_POST['login']) && $_POST['login']=="Login"){
		checkCredentials();
	}

	//If the user is an admin, redirect them to the admin account page
	//Potentially user the admin registration page as this page?
	if (isset($_SESSION['username']) && isset($_SESSION['is_admin'])) {
		header('Location: admin.php');
	}

	//This should redirect the user to the Zeni homepage if they are not an admin user
	if (isset($_SESSION['username']) && !isset($_SESSION['is_admin'])) {
		//The location needs to be entered (should redirect to the main html page)
		//header('Location: ______')
	}

	//If a regular user clicks the register button, redirect to the registration page
	if (isset($_POST['register']) && $_POST['register']=="Register") {
		header('Location: register.php');
	}

	// If a user clicks the logout button, run the logout function to end the session
	if (isset($_SESSION['username']) && isset($_POST['logout']) && $_POST['logout']=="Logout"){
		logout();
	}
?>

<!-- HTML form to allow the user to interact with the page  -->
<!doctype html>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<h1>Login</h1>
		<form method="post" action="index.php">
			<label for="username">Username: </label><input type="text" name="username" />
			<label for="password">Password: </label><input type="password" name="password" />
			<input name="login" type="submit" value="Login" />
		</form>
		<br></br>
		<form method="post" action="index.php">
			<input name="logout" type="submit" value="Logout" />
		</form>
		<form method="post" action="index.php">
			<input name="register" type="submit" value="Register" />
		</form>
		<?php if (isset($GLOBALS['msg'])) echo "<p class='msg'>" . $GLOBALS['msg']."</p" ?>
		<?php if (isset($_SESSION['err'])) echo "<p class='err'>" . $_SESSION['err']."</p>" ?>
	</body>
</html>