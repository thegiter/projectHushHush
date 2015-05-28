<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	$shpsAjax = new stdClass;
	
	//sadly no object literals in php
	$shpsAjax->hook = 'historyView';
	$shpsAjax->jss = [
		'/shared/shps_ajax/hv/jss/hv.js'
	];
	$shpsAjax->csss = [
		'/shared/shps_ajax/hv/csss/hv.css'
	];
	
	ob_start();//output buffering
	require root.'shared/shps_ajax/hv/html.php';
	$shpsAjax->html = ob_get_clean();
	
	echo json_encode($shpsAjax);
?>