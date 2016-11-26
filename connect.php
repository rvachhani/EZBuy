<?php

	//Connects to the database and stores the connection info in the GLOBALS variable
	function dbconnect($dbhost, $dbname, $dbuser, $dbpwd) {
		try {
			$GLOBALS['dbconn'] = new PDO('mysql:host=' . $dbhost. ';dbname='.$dbname,
				$dbuser, $dbpwd);
		}
		catch (Exception $e) {
			echo "Error " . $e->getMessage();
		}
	}