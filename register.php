<?php
	
	session_start();

	//Connect to the database
	try {
		$dbname = 'zeni';
		$user = 'root';
		$pass = 'root';
		$dbconn = new PDO('mysql:host=localhost;dbname='.$dbname, $user, $pass);
	}
	catch (Exception $e){
		echo "Error: " . $e->getMessage();
	}

	//Check to see if the register button was pressed
	if (isset($_POST['register']) && $_POST['register']=='Register') {

		// Check and make sure that all fields are filled out
		if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['username']) || 
			empty($_POST['password']) || empty($_POST['confirmPass'])) {
			$msg = "Please fill in all form fields";
		}
		//Make sure the passwords entered match
		else if ($_POST['password'] != $_POST['confirmPass']) {
			$msg = "Passwords must match.";
		}
		//Store the user's information in the database
		else {
			//Generate random salt
			$salt = hash('sha256', uniqid(mt_rand(), true));

			//Apply salt to password
			$salted = hash('sha256', $salt.$_POST['password']);

			//Store the salt and password together
			$stmt = $dbconn->prepare("INSERT INTO users (fname, lname, username, password, salt) 
				VALUES (:fname, :lname, :username, :password, :salt)");
			$stmt->execute(array(':fname' => $_POST['fname'], ':lname' => $_POST['lname'],
				':username' => $_POST['username'], ':password' => $salted,':salt' => $salt));
			$msg = "Account was created successfully!";
		}
		//The user should then be redirected to the website homepage

		// !!!! Needs to be finished !!!!!
		// header('Location: _____');
	}

?>

<!-- Form to allow user to enter their registration information -->
<!doctype html>
<html>
	<head>
		<title>Register</title>
	</head>
	<body>
		<h1>New User Registration</h1>
		<?php if (isset($msg)) echo "<p>$msg</p>" ?>
		<form method="post" action="register.php">
			<label for="fname">First Name: </label><input type="text" name="fname" />
			<label for="lname">Last Name: </label><input type="text" name="lname" />
			<label for="username">Username: </label><input type="text" name="username" />
			<label for="password">Password: </label><input type="password" name="password" />
			<label for="confirmPass">Confirm Password: </label><input type="password" name="confirmPass" />
			<input type="submit" name="register" value="Register" />
		</form>
	</body>
</html>