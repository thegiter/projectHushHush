<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	$tkrs = json_decode($_POST['tkrs_json']);
	$se = $_POST['se'];

	switch ($se) {
		case 'SHSE':
		case 'SZSE':
			$se = strtolower($se);
			
			break;
		default:
			die('invalid stock exchange for table');
	}
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		echo 'User Connection Error';//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			echo 'Database Connection Error';//mysql_error();
		} else {//then excute sql query
			// for each tkr, insert into table
			foreach ($tkrs as $tkr) {
				if (!@mysql_query('INSERT INTO '.$se.'_tkrs(tkr, name) VALUES("'.$tkr->ticker.'", "'.$tkr->name.'") ON DUPLICATE KEY UPDATE name=VALUES(name)')) {
					echo 'insert error';
				}
			}

			echo 'success';
		}
	}
?>