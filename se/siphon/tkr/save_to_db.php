<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	$tkrs = json_decode($_POST['tkrs_json']);
	$se = $_POST['se'];
	$append = (isset($_POST['append']) && $_POST['append'] == 'append') ? true : false;

	switch ($se) {
		case 'SHSE':
		case 'SZSE':
		case 'JSE':
		case 'NYSE':
		case 'Nasdaq':
			$se = strtolower($se);

			break;
		default:
			die('invalid stock exchange for table');
	}

	require_once root.'se/cmm/lib/db.php';

	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if ($mysqli->connect_error) {
		echo 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
	} else {//then excute sql query
		//clear table
		if (!$append) {
			$mysqli->query('TRUNCATE TABLE '.$se.'_tkrs');
		}

		// for each tkr, insert into table
		foreach ($tkrs as $tkr) {
			if (!$mysqli->query('INSERT INTO '.$se.'_tkrs(tkr, name) VALUES("'.$tkr->ticker.'", "'.$tkr->name.'") ON DUPLICATE KEY UPDATE name=VALUES(name)')) {
				echo 'insert error '.$tkr->ticker.' '.$tkr->name;
			}
		}

		echo 'success';
	}
?>
