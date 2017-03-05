<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	switch ($_POST['se']) {
		case 'shse':
		case 'szse':
		case 'nyse':
		case 'nasdaq':
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
		//get all defs from the se table
		if (!$defs = $mysqli->query('SELECT * FROM '.$_POST['se'].'_defs')) {
			echo 'get data from db error';
		} else {
			$rsp = new stdClass;
			
			$defs->data_seek(0);
			
			while ($defRow = $defs->fetch_assoc()) {
				$rsp->{$defRow['tkr']} = new stdClass;
				$tkr = $rsp->{$defRow['tkr']};
				
				foreach ($defRow as $ttl => $value) {
					if (($ttl == 'tkr') || is_numeric($ttl)) {
						continue;
					}
					
					$tkr->{$ttl} = $value;
				}
				
				//get vars
				$tkr->glbRank = 1;
				
				if ($vars = $mysqli->query('SELECT * FROM '.$_POST['se'].'_vars WHERE tkr="'.$defRow['tkr'].'"')) {
					$vars->data_seek(0);
					
					if ($varRow = $vars->fetch_assoc()) {
						$tkr->glbRank = $varRow['glbrank'];
					}
				}
			}
			
			echo json_encode($rsp);
		}
	}
?>