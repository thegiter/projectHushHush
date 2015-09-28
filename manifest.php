<?php
	if (!defined('root')) {
		define('root', '');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Sat, 22 Jun 2015 02:42:40 GMT');
	
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
	$manifest[0][1]->type = 'link';
	$manifest[0][1]->url = '/shared/csss/beta/init_stg.css';
//grp1
	$manifest[1] = [];
	
	$manifest[1][0] = new stdClass;
	$manifest[1][0]->type = 'html';
	ob_start();//output buffering
	require root.'html.php';
	$manifest[1][0]->html = ob_get_clean();
	
	$manifest[1][1] = new stdClass;
	$manifest[1][1]->type = 'script';
	$manifest[1][1]->url = '/shared/jss/common.js';
	$manifest[1][1]->async = false;
	
	//images must be defined in a group before css, so that we will replace the url to the cache url
	$manifest[1][2] = new stdClass;
	$manifest[1][2]->type = 'img';
	$manifest[1][2]->url = '/shared/imgs/beta_sticker.png';
//grp2
	$manifest[2] = [];
	
	$manifest[2][0] = new stdClass;
	$manifest[2][0]->type = 'script';
	$manifest[2][0]->url = '/jss/shps.js';

	$manifest[2][1] = new stdClass;
	$manifest[2][1]->type = 'link';
	$manifest[2][1]->url = '/shared/csss/beta/beta.css';
//grp3
	$manifest[3] = [];
	
	$manifest[3][0] = new stdClass;
	$manifest[3][0]->type = 'script';
	$manifest[3][0]->url = '/shared/jss/beta.js';

	echo json_encode($manifest);
?>