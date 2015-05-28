<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	$shpsAjax = new stdClass;

	//even though this system cpn does not need to be hooked
	//the hook name must still be defined here as the cpn ldr expects a hook name from resource list
	$shpsAjax->hook = 'basic_bg';
	$shpsAjax->jss = [
		'/shared/bgs/basic/jss/bg.js'
	];
	$shpsAjax->csss = [
		'/shared/bgs/basic/csss/bg.css'
	];
	
	ob_start();//output buffering
	require root.'shared/bgs/basic/html.php';
	$shpsAjax->html = ob_get_clean();
	
	echo json_encode($shpsAjax);
?>