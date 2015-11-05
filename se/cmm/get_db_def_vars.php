<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$se = $_POST['se'];
	$tkr = $_POST['tkr'];
	
	$vars = new stdClass;
	$vars->car = 1;
	$vars->cc = 1;
	$vars->ir = .012;
	$vars->oldAdvice;
	
	$tbl_name = strtolower($se).'_defs';
	
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
			//get tkr vars from the se table
			if (($db_def = @mysql_query('SELECT car, cc, FROM '.$tbl_name.' WHERE tkr="'.$tkr.'"'))) {
				$db_tkr_def = mysql_fetch_array($db_def);
				
				if ($db_tkr_def['car']) {
					$vars->car = $db_tkr_def['car'];
				}
				
				if ($db_tkr_def['cc']) {
					$vars->cc = $db_tkr_def['cc'];
				}
				
				if ($db_tkr_def['advice']) {
					$vars->oldAdvice = $db_tkr_def['advice'];
				}
			}
			
			echo json_encode($vars);
		}
	}
?>