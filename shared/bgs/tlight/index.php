<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Sun, 26 Jul 2015 14:42:40 GMT');
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	$manifest = new stdClass;
	
	$manifest->hook = 'bg_tlight';
	
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
	$r[0][0]->url = '/shared/bgs/tlight/imgs/bg_ptn.png';
//grp1
	$r[1] = [];
	
	//sadly no object literals in php
	$r[1][0] = new stdClass;
	$r[1][0]->type = 'link';
	$r[1][0]->url = '/shared/bgs/tlight/csss/bg.css';
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'html';
	ob_start();//output buffering
	require root.'shared/bgs/tlight/html.php';
	$r[2][0]->html = ob_get_clean();
//grp3
	$r[3] = [];
	
	$r[3][0] = new stdClass;
	$r[3][0]->type = 'script';
	$r[3][0]->url = '/shared/bgs/tlight/jss/bg.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>