<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$se = $_POST['se'];
	
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
				echo 'insert error';
			}
		}
	}
?>