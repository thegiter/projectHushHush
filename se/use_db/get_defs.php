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
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		echo 'User Connection Error';//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			echo 'Database Connection Error';//mysql_error();
		} else {//then excute sql query
			//get all defs from the se table
			if (!($defs = @mysql_query('SELECT * FROM '.$_POST['se'].'_defs'))) {
				echo 'get data from db error';
			} else {
				$rsp = new stdClass;
				
				while ($defRow = mysql_fetch_array($defs)) {
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
					
					if ($vars = @mysql_query('SELECT * FROM '.$_POST['se'].'_vars WHERE tkr="'.$defRow['tkr'].'"')) {
						if ($varRow = mysql_fetch_array($vars)) {
							$tkr->glbRank = $varRow['glbrank'];
						}
					}
				}
				
				echo json_encode($rsp);
			}
		}
	}
?>