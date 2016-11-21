<?php
	require 'config.php';
	require 'connect.php';
	require 'logout.php';
	require 'check_credentials.php';

	session_start();
	$_SESSION['err']='';

	//Connect to the database
	$dbname = "zeni";
	dbconnect($config['dbhost'], $dbname, $config['dbusername'], $config['dbpassword']);

	if (isset($_POST['login']) && $_POST['login']=="Login"){
		checkCredentials();
	}

	if (isset($_SESSION['username']) && isset($_SESSION['is_admin'])) {
		//If the user is an admin, redirect them to the admin account page
		//Potentially user the admin registration page as this page?
		header('Location: admin.php');
	}

	//If a regular user clicks the register button, redirect to the registration page
	if (isset($_POST['register']) && $_POST['register']=="Register") {
		header('Location: register.php');
	}

	if (isset($_SESSION['username']) && isset($_POST['logout']) && $_POST['logout']=="Logout"){
		logout();
	}
?>

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