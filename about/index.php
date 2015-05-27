<?php
	if (!defined('root')) {
		define('root', '../');
	}

	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	if (!defined('da')) {
		define('da', false);
	}
	
	$shpsAjax = new stdClass;
	
	//sadly no object literals in php
	$shpsAjax->hook = 'about';
	$shpsAjax->ttl = 'About';
	$shpsAjax->jss = [
		'/about/jss/abt.js'
	];
	$shpsAjax->csss = [
		'/about/csss/abt.css'
	];
	$shpsAjax->cpns = [];
	
	$shpsAjax->cpns[0] = new stdClass;
	
	$shpsAjax->cpns[0]->name = 'bg_tlight';
	//$shpsAjax->cpns[0]->required = true;
	//no close, define close true if needed

	//$shpsAjax->cpns[1] = new stdClass;
	
	//$shpsAjax->cpns[1]->name = 'menu_basic';
	//no required
	
	ob_start();//output buffering
	require root.'about/html.php';
	$shpsAjax->html = ob_get_clean();
	
	echo json_encode($shpsAjax);
?>