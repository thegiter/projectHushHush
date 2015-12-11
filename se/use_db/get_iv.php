<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$tkr = $_GET['tkr'];
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (!$mysqli) {
		echo 'User / DB Connection Error';//or die(mysql_error());
	} else {//then excute sql query
		//get all defs from the se table
		$result = $mysqli->query('SELECT afptm FROM shse_defs WHERE tkr='.$tkr);
		
		if (!$result) {
			die('get data from shse defs error');
		} else {
			if ($result->num_rows <= 0) {
				$result = $mysqli->query('SELECT afptm FROM szse_defs WHERE tkr='.$tkr);
				
				if (!$result) {
					die('get data from szse defs error');
				} else {
					if ($result->num_rows <= 0) {
						die('tkr not found');
					}
				}
			}
			
			echo $result->fetch_assoc()['afptm'];
		}
	}
?>