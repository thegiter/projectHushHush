<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	$shpsAjax = new stdClass;

	$shpsAjax->hook = 'bg_tlight';
	$shpsAjax->jss = [
		'/shared/bgs/tlight/jss/bg.js'
	];
	$shpsAjax->csss = [
		'/shared/bgs/tlight/csss/bg.css'
	];
	
	ob_start();//output buffering
	require root.'shared/bgs/tlight/html.php';
	$shpsAjax->html = ob_get_clean();
	
	echo json_encode($shpsAjax);
?>