<?php
	
	function dbconnect($dbhost, $dbname, $dbuser, $dbpwd) {
		try {
			$GLOBALS['dbconn'] = new PDO('mysql:host=' . $dbhost. ';dbname='.$dbname,
				$dbuser, $dbpwd);
		}
		catch (Exception $e) {
			echo "Error " . $e->getMessage();
		}
	}
?>