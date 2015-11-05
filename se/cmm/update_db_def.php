<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	$se = $_POST['se'];
	$tkr = $_POST['tkr'];
	$def_j = $_POST['def'];
	$old_advice = $_POST['old_advice'];
	
	$tbl_name = strtolower($se).'_defs';
	
	if (!($def = json_decode($def_j))) {
		die('siphon failed');
	}
	
	//if succes, we will store in db, but not the user input values, the user input values must be changed in use_db
	//if store db failed, the script dies automatically
	//if store db succeeded, we will respond with the json encoded def
	require_once root.'se/cmm/lib/update_tbl_row.php';
	
	se_update_tbl_row_siphoned($tbl_name, $tkr, $def);
	
	//we check if advice changed to sell or buy, and update to advice table
	if ((($def->advice == 'buy') || ($def->advice == 'sell')) && (!isset($old_advice) || ($def->advice != $old_advice))) {
		//update tbl
		if (!@mysql_query('INSERT INTO advice_updates(tkr, old_advice, new_advice)
		VALUES("'.$tkr.'", "'.$old_advice.'", "'.$def->advice.'")
		ON DUPLICATE KEY UPDATE old_advice=VALUES(old_advice), new_advice=VALUES(new_advice)')) {
			die('insert / update advice updates tbl error');
		}
	}
	
	echo $def_j;
?>