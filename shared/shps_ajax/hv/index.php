<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Sat, 25 Jul 2015 17:42:40 GMT');
	
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	$manifest = new stdClass;
	
	$manifest->hook = 'historyView';
	
	$r =& $manifest->rscs;
	$r = [];
	
	//each file is loaded asynchronously
	//however, each group is installed sequentially
	//while each file within the group are installed parallel, i.e. installed as they are downloaded
//grp0
	$r[0] = [];
	
	//images must be defined in a group before css, so that we will replace the url to the cache url
	$r[0][0] = new stdClass;
	$r[0][0]->type = 'link';
	$r[0][0]->url = '/shared/shps_ajax/hv/csss/hv.css';
//grp1
	$r[1] = [];
	
	//sadly no object literals in php
	$r[1][0] = new stdClass;
	$r[1][0]->type = 'html';
	ob_start();//output buffering
	require root.'shared/shps_ajax/hv/html.php';
	$r[1][0]->html = ob_get_clean();
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'script';
	$r[2][0]->url = '/shared/shps_ajax/hv/jss/hv.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>