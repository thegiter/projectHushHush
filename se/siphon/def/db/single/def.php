<?php
	if (!defined('root')) {
		define('root', '../../../../../');
	}
	
	//we re-siphon the tkr def
	//it then returns the json data of def obj
	$_POST['refresh'] = 'refresh';
	$_POST['ignore_lu'] = 'ignore';
	require root.'se/siphon/def/db/batch/siphon.php';
?>