<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$tkr = $_POST['tkr'];
	$se = strtolower($_POST['se']);
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if ($mysqli->connect_error) {
		echo 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
	} else if (!$mysqli->query('DELETE FROM '.$se.'_tkrs WHERE tkr="'.$tkr.'"')) {//then excute sql query
		die('error deleting ticker: '.$mysqli->error);
	} else {
		echo 'success';
	}
?>