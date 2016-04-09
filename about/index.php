<?php
	if (!defined('root')) {
		define('root', '../');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Sat, 9 Apr 2016 22:13:40 GMT');
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	if (!defined('da')) {
		define('da', false);
	}
	
	$manifest = new stdClass;
	
	$manifest->hook = 'about';
	$manifest->ttl = 'About';
	
	$r =& $manifest->rscs;
	$r = [];
	
	//each file is loaded asynchronously
	//however, each group is installed sequentially
	//while each file within the group are installed parallel, i.e. installed as they are downloaded
//grp0
	$r[0] = [];
	
	//images must be defined in a group before css, so that we will replace the url to the cache url
	$r[0][0] = new stdClass;
	$r[0][0]->type = 'img';
	$r[0][0]->url = '/about/imgs/bg_pic.png';
	
	$r[0][1] = new stdClass;
	$r[0][1]->type = 'module';
	$r[0][1]->url = '/shared/modules/lv2/common/';
//grp1
	$r[1] = [];
	
	//sadly no object literals in php
	$r[1][0] = new stdClass;
	$r[1][0]->type = 'cpnList';
	$r[1][0]->cpns = [];
	
	$r[1][0]->cpns[0] = new stdClass;
	$r[1][0]->cpns[0]->name = 'bg_tlight';
	//$r[1][0]->cpns[0]->required = true;
	//no close, define close true if needed
	
	//$shpsAjax->cpns[1] = new stdClass;
	
	//$shpsAjax->cpns[1]->name = 'menu_basic';
	//no required
	$r[1][1] = new stdClass;
	$r[1][1]->type = 'link';
	$r[1][1]->url = '/about/csss/abt.css';
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'html';
	ob_start();//output buffering
	require root.'about/html.php';
	$r[2][0]->html = ob_get_clean();
//grp3
	$r[3] = [];

	$r[3][0] = new stdClass;
	$r[3][0]->type = 'script';
	$r[3][0]->url = '/about/jss/abt.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>