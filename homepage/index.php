<?php
	if (!defined('root')) {
		define('root', '../');
	}

	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	header('Last-Modified: Wed, 17 Jun 2015 22:13:40 GMT');
	header('ETag: "shpshpv1"');
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	$shpsAjax = new stdClass;
	
	//sadly no object literals in php
	$shpsAjax->hook = 'homepage';
	$shpsAjax->ttl = 'Homepage';
	
	$shpsAjax->settings = new stdClass;
	
	$shpsAjax->settings->psdMenuNoExp = true;
	
	$shpsAjax->jss = [
		'/homepage/jss/hp.js'
	];
	$shpsAjax->csss = [
		'/homepage/csss/hp.css'
	];
	$shpsAjax->cpns = [];
	
	$shpsAjax->cpns[0] = new stdClass;
	
	$shpsAjax->cpns[0]->name = 'bg_basic';
	$shpsAjax->cpns[0]->required = true;
	//no close, define close true if needed
	
	ob_start();//output buffering
	require root.'homepage/html.php';
	$shpsAjax->html = ob_get_clean();
	
	echo json_encode($shpsAjax);
?>