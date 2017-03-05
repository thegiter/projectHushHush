<?php
	if (!defined('root')) {
		define('root', '../../../../../');
	}
	
	require_once root.'se/cmm/lib/chk_auth.php';
	
	$_POST['se'] = $_GET['se'];
	$_POST['tkr'] = $_GET['tkr'];
	
	//we re-siphon the tkr def
	//it then returns the json data of def obj
	$_POST['refresh'] = 'refresh';
	$_POST['ignore_lu'] = 'ignore';
	require root.'se/siphon/def/db/batch/siphon.php';
?>