<?php

// This function runs when the user clicks the login button
function checkCredentials() {

	//connect to the database
	$dbconn = $GLOBALS['dbconn'];

	//Define the salt statement based on the username
	$salt_stmt = $dbconn->prepare('SELECT salt from users WHERE username=:username');
	$salt_stmt->execute(array(':username' => $_POST['username']));
	$result = $salt_stmt->fetch();

	//Gets the salt statement that was originally used with the user's password
	$salt = ($result) ? $result['salt'] : '';

	//Obtain the salted password by combining the user's input with the salt and hash
	$salted = hash('sha256', $salt . $_POST['password']);

	//find the appropriate user in the database
	$login_stmt = $dbconn->prepare('SELECT username, uid FROM users WHERE username=:username AND password=:password');
	$login_stmt->execute(array(':username' => $_POST['username'], ':password' => $salted));

	//If there is a match, set the session username and uid
	if ($user = $login_stmt->fetch()) {
		$_SESSION['username'] = $user['username'];
		$_SESSION['uid'] = $user['uid'];

		//Determine whether the user is an admin and set the session variable accordingly
		$is_admin = $dbconn->prepare('SELECT 1 FROM users WHERE username=:username
			AND is_admin=1');
		$is_admin->execute(array(':username'=>$_SESSION['username']));

		if ($is_admin->fetch()) {
			$_SESSION['is_admin']=true;
		} else {
			$_SESSION['is_admin']=false;
		}

		$msg = 'Login successful';

	} else {
		$_SESSION['err'] = 'Incorrect username or password.';
	}
}