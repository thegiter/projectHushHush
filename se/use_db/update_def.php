<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$se = strtolower($_POST['se']);
	
	switch ($se) {
		case 'shse':
		case 'szse':
			break;
		default:
			die('invalid stock exchange for table');
	}
	
	$tkr = $_POST['tkr'];
	$defName = $_POST['def_name'];
	$defValue = $_POST['def_value'];
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		echo 'User Connection Error';//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			echo 'Database Connection Error';//mysql_error();
		} else {//then excute sql query
			if (!@mysql_query('UPDATE '.$se.'_defs
			SET '.$defName.'='.$defValue.'
			WHERE tkr='.$tkr)) {
				die('tkr variable insert / update error');
			}
		}
	}
	
	//after updating user variables, we re-siphon the tkr def
	//it then returns the json data of def obj
	require root.'se/siphon/def/db/batch/siphon.php';
?>