<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}
	
	session_start();
	
	if (!$_SESSION['authed']) {
		die;
	}
	
	$tkr = $_SESSION['tkr'];
	
	require_once root.'se/siphon/cmm/lib/analyze_data.php';
	
	$def = seAnalyze::getStockDef($tkr, 1, 1, true);//true to refresh
	
	//upon receiving of the siphoned data, check if siphon successful,
	//if failed, we will respond with failure msg instead of retrying.
	//because retrying could result in php running longer than allowed seconds
	//this has the byproduct of informing the client side of failures, client side can then initiate a retry
	if (is_string($def)) {
		die($def);
	}
	
	//if succes, we will update db, but not the user input values, the user input values must be changed in use_db
	//if store db failed, the script dies automatically
	//if store db succeeded, we will respond with the json encoded def
	echo $def->lffptm.'
	'.$def->fptm.'
	'.$def->bp.'
	'.$def->pow;
?>