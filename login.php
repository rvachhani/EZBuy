<?php
session_start();

try {
	$dbname = 'zeni';
	$user = 'root';
	$password = 'root';
	$dbconn = new PDO('mysql:host=locahlost;dbname='.$dbname, $user, $password);
}
catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}

if (isset($_POST['login']) && $_POST['login']=='Login') {
	$salt_stmt = $dbconn->prepare('SELECT salt FROM users WHERE username=:username');
	$salt_stmt->execute(array(':username' => $_POST['username']));
	$result = $salt_stmt->fetch();
	$salt = ($result) ? $result['salt'] : '';
	$salted = hash('sha256', $salt . $_POST['password']);

	$login_stmt = $dbconn->prepare('SELECT username, uid from users WHERE username=:username
		AND password=:password');
	$login_stmt->execute(array(':username' => $_POST['username'], ':pass' => $salted));

	if ($user = $login_stmt->fetch()){
		$_SESSION['username'] = $user['username'];
		$_SESSION['uid'] = $user['uid'];
		//$_SESSION['is_admin'] = $user['is_admin'];
	}
}

?>