<?php
//Displays the options for the admin once they log in

session_start();

//establishes a connection to the database
try {
	$dbname = 'zeni';
	$user = 'root';
	$pass = 'root';
	$dbconn = new PDO('mysql:host=localhost;dbname='.$dbname, $user, $pass);

} catch (Exception $e) {
	echo "Error: " .$e->getMessage();
}

//If the user clicks the logout button, redirect them to the index page
if (isset($_POST['logout']) && $_POST['logout'] == 'Logout') {
	header('Location: index.php');
	exit();
}

//Make sure the user is an admin or else redirect them to the index page
if ($_SESSION['is_admin'] != true) {
	header('Location: index.php');
	exit();
} else {
	if (isset($_POST['register']) && $_POST['register'] == 'Register') {

		//Make sure all fields are filled out
		if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['username']) || 
			empty($_POST['password']) || empty($_POST['confirmPass'])) {
			$msg = "Please fill in all form fields";
		}
		//Make sure the passwords entered match
		else if ($_POST['password'] != $_POST['confirmPass']) {
			$msg = "Passwords must match.";
		}
		//Create the admin account
		else {
			//Generate random salt
			$salt = hash('sha256', uniqid(mt_rand(), true));

			//Apply salt to password
			$salted = hash('sha256', $salt.$_POST['password']);

			//Store the salt and password together
			$stmt = $dbconn->prepare("INSERT INTO users (fname, lname, username, password, salt, is_admin) 
				VALUES (:fname, :lname, :username, :password, :salt, :is_admin)");
			$stmt->execute(array(':fname' => $_POST['fname'], ':lname' => $_POST['lname'],
				':username' => $_POST['username'], ':password' => $salted,':salt' => $salt,
				':is_admin' => true));
			$msg = "Account was created successfully!";
		}
	}
}

?>

<!-- HTML form to allow the user to enter registration information -->
<!doctype html>
<html>
	<head>
		<title>Admin Controls</title>
	</head>
	<body>
		<h1>Admin Settings</h1>
		<?php if (isset($msg)) echo "<p>$msg</p>" ?>
		<form method="post" action="admin.php">
			<label for="fname">First Name: </label><input type="text" name="fname" />
			<label for="lname">Last Name: </label><input type="text" name="lname" />
			<label for="username">Username: </label><input type="text" name="username" />
			<label for="password">Password: </label><input type="password" name="password" />
			<label for="confirmPass">Confirm Password: </label><input type="password" name="confirmPass" />
			<input type="submit" name="register" value="Register" />
		</form>
		<form method="post" action="index.php">
			<input name="logout" type="submit" value="Logout" />
		</form>
	</body>
</html>