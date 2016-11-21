<?php
function checkCredentials() {

	$dbconn = $GLOBALS['dbconn'];

	$salt_stmt = $dbconn->prepare('SELECT salt from users WHERE username=:username');
	$salt_stmt->execute(array(':username' => $_POST['username']));
	$result = $salt_stmt->fetch();
	$salt = ($result) ? $result['salt'] : '';

	$salted = hash('sha256', $salt . $_POST['password']);

	$login_stmt = $dbconn->prepare('SELECT username, uid FROM users WHERE username=:username AND password=:password');
	$login_stmt->execute(array(':username' => $_POST['username'], ':password' => $salted));

	if ($user = $login_stmt->fetch()) {
		$_SESSION['username'] = $user['username'];
		$_SESSION['uid'] = $user['uid'];

		$is_admin = $dbconn->prepare('SELECT 1 FROM users WHERE username=:username
			AND is_admin=1');
		$is_admin->execute(array(':username'=>$_SESSION['username']));

		if ($is_admin->fetch()) {
			$_SESSION['is_admin']=true;
		} else {
			$_SESSION['is_admin']=false;
		}

		echo 'Login successful';

	} else {
		$_SESSION['err'] = 'Incorrect username or password.';
	}
}