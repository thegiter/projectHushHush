<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$tkr = $_POST['tkr'];
	$se = $_POST['se'];
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (!$mysqli) {
		echo 'User / DB Connection Error';//or die(mysql_error());
	} else {//then excute sql query
		//get all defs from the se table
		$result = $mysqli->query('DELETE FROM '.$se.'_tkrs WHERE tkr='.$tkr);
		
		if (!$result) {
			die('error deleting ticker: '.$mysqli->error);
		}
	}
?>