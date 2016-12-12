<?php
	header('Access-Control-Allow-Origin: http://'.preg_replace('/ses\d+\./', '', $_SERVER['SERVER_NAME']));

	if (!defined('root')) {
		define('root', '../../../../../');
	}
	
	require root.'se/cmm/lib/chk_auth.php';
	
	$se = $_POST['se'];
	$tkr = $_POST['tkr'];
	$refresh = (isset($_POST['refresh']) && ($_POST['refresh'] == 'refresh')) ? true : false;
	$car = 1;
	$cc = 1;
	$tbl_name = strtolower($se).'_defs';
	$old_advice;
	$def;
	
	require_once root.'se/cmm/lib/db.php';
	
	//here, we assume that the table already exist
	//but we don't know if the data for the ticker exist or not
	//if ticker exist and has any of the car or cc values, we will use those from the db for the siphon
	//else if no data for these fields, then we assume no user specified data, and we will use the default ones
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		die('User Connection Error');//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			die('Database Connection Error');//mysql_error();
		} else {//then excute sql query
			//get all tickers from the se table
			if (($db_def = @mysql_query('SELECT lu FROM '.$tbl_name.' WHERE tkr="'.$tkr.'"'))) {
				$db_tkr_def = mysql_fetch_array($db_def);
				
				//check if last update is less than 5 days
				if ($db_tkr_def['lu']) {
					$lu = new DateTime($db_tkr_def['lu']);
					$now = new DateTime('now');
					
					if ($lu->diff($now)->days < 5) {
						//return db data and skip siphon
						if ($db_def = @mysql_query('SELECT * FROM '.$tbl_name.' WHERE tkr="'.$tkr.'"')) {
							$db_tkr_def = mysql_fetch_array($db_def);
							
							$def = new stdClass;
							
							foreach ($db_tkr_def as $ttl => $value) {
								if (($ttl == 'tkr') || is_numeric($ttl)) {
									continue;
								}
								
								$def->{$ttl} = $value;
							}
							
							die(json_encode($def));
						}						
					}
				}
				
				//else if more than 5 days
				//fetch car cc, etc if exist and continue to siphon
				if (($db_def = @mysql_query('SELECT car, cc, advice FROM '.$tbl_name.' WHERE tkr="'.$tkr.'"'))) {
					$db_tkr_def = mysql_fetch_array($db_def);
					
					if ($db_tkr_def['car']) {
						$car = $db_tkr_def['car'];
					}
					
					if ($db_tkr_def['cc']) {
						$cc = $db_tkr_def['cc'];
					}
					
					if ($db_tkr_def['advice']) {
						$old_advice = $db_tkr_def['advice'];
					}
				}
			}
		}
	}
	
	require_once root.'se/siphon/cmm/lib/analyze_data.php';
	
	$def = seAnalyze::getStockDef($se.':'.$tkr, $car, $cc, $refresh);
	
	//upon receiving of the siphoned data, check if siphon successful,
	//if failed, we will respond with failure msg instead of retrying.
	//because retrying could result in php running longer than allowed seconds
	//this has the byproduct of informing the client side of failures, client side can then initiate a retry
	if (is_string($def)) {
		$err = new stdClass;
		
		$err->err = true;
		$err->err_msg = $def;
		
		die(json_encode($err));
	}
	
	//if succes, we will update db, but not the user input values, the user input values must be changed in use_db
	//if store db failed, the script dies automatically
	//if store db succeeded, we will respond with the json encoded def
	require_once root.'se/cmm/lib/update_tbl_row.php';
	
	se_update_tbl_row_siphoned($tbl_name, $tkr, $def);
	
	//we check if advice changed to sell or buy, and update to advice table
	if ((($def->advice == 'buy') || ($def->advice == 'sell') || ($def->advice == 'be ready to sell')) && (!isset($old_advice) || ($def->advice != $old_advice))) {
		//update tbl
		if (!@mysql_query('INSERT INTO advice_updates(tkr, old_advice, new_advice)
		VALUES("'.$tkr.'", "'.$old_advice.'", "'.$def->advice.'")
		ON DUPLICATE KEY UPDATE old_advice=VALUES(old_advice), new_advice=VALUES(new_advice)')) {
			die('insert / update advice updates tbl error');
		}
	}
	
	echo json_encode($def);
?>