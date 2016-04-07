<?php
	if (!defined('root')) {
		define('root', '');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Wed, 6 Apr 2016 02:42:40 GMT');
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	$manifest = [];
	
	//each file is loaded asynchronously
	//however, each group is installed sequentially
	//while each file within the group are installed parallel, i.e. installed as they are downloaded
//grp0
	$manifest[0] = [];
	
	//sadly no object literals in php
	$manifest[0][0] = new stdClass;
	$manifest[0][0]->type = 'link';
	$manifest[0][0]->url = '/csss/shps.css';
	
	$manifest[0][1] = new stdClass;
	$manifest[0][1]->type = 'script';
	$manifest[0][1]->url = '/shared/jss/common.js';
	$manifest[0][1]->async = false;
//grp1
	$manifest[1] = [];
	
	$manifest[1][0] = new stdClass;
	$manifest[1][0]->type = 'html';
	ob_start();//output buffering
	require root.'html.php';
	$manifest[1][0]->html = ob_get_clean();
//grp2
	$manifest[2] = [];
	
	$manifest[2][0] = new stdClass;
	$manifest[2][0]->type = 'script';
	$manifest[2][0]->url = '/jss/shps.js';

	echo json_encode($manifest);
?>