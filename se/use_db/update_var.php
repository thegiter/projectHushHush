<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$se = strtolower($_POST['se']);
	
	switch ($se) {
		case 'shse':
		case 'szse':
		case 'nyse':
		case 'nasdaq':
			break;
		default:
			die('invalid stock exchange for table');
	}
	
	$tkr = $_POST['tkr'];
	$defName = strtolower($_POST['def_name']);
	$defValue = $_POST['def_value'];
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if ($mysqli->connect_error) {
		echo 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
	} else if (!$mysqli->query('INSERT INTO '.$se.'_vars(tkr, '.$defName.', '.$defName.'lu)
	VALUES("'.$tkr.'", '.$defValue.', CURRENT_DATE())
	ON DUPLICATE KEY UPDATE '.$defName.'=VALUES('.$defName.'), '.$defName.'lu=VALUES('.$defName.'lu)')) {
		die('tkr var insert / update error');
	}
	
	//after updating user variables, we re-siphon the tkr def
	//it then returns the json data of def obj
	$_POST['refresh'] = 'refresh';
	$_POST['ignore_lu'] = 'ignore';
	require root.'se/siphon/def/db/batch/siphon.php';
?>