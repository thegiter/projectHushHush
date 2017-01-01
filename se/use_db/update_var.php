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
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		echo 'User Connection Error';//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			echo 'Database Connection Error';//mysql_error();
		} else {//then excute sql query
			if (!@mysql_query('INSERT INTO '.$se.'_vars(tkr, '.$defName.', '.$defName.'lu)
			VALUES('.$tkr.', '.$defValue.', CURRENT_DATE())
			ON DUPLICATE KEY UPDATE '.$defName.'=VALUES('.$defName.'), '.$defName.'lu=CURRENT_DATE()')) {
				die('tkr variable insert / update error');
			}
		}
	}
	
	//after updating user variables, we re-siphon the tkr def
	//it then returns the json data of def obj
	$_POST['refresh'] = 'refresh';
	$_POST['ignore_lu'] = 'ignore';
	require root.'se/siphon/def/db/batch/siphon.php';
?>