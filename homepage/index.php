<?php
	if (!defined('root')) {
		define('root', '../');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Wed, 15 Aug 2015 22:13:40 GMT');
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	$manifest = new stdClass;
	
	$manifest->hook = 'homepage';
	$manifest->ttl = 'Homepage';
	
	$manifest->settings = new stdClass;
	
	$manifest->settings->psdMenuNoExp = true;
	
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
	$r[0][0]->url = '/homepage/imgs/nn.png';
	
	$r[0][1] = new stdClass;
	$r[0][1]->type = 'img';
	$r[0][1]->url = '/homepage/imgs/bg_img.jpg';
	
	$r[0][2] = new stdClass;
	$r[0][2]->type = 'img';
	$r[0][2]->url = '/homepage/imgs/base_ptn.jpg';
	
	$r[0][3] = new stdClass;
	$r[0][3]->type = 'img';
	$r[0][3]->url = '/homepage/imgs/main_pic.jpg';
	
	$r[0][4] = new stdClass;
	$r[0][4]->type = 'img';
	$r[0][4]->url = '/homepage/imgs/abstract.png';
	
	$r[0][5] = new stdClass;
	$r[0][5]->type = 'link';
	$r[0][5]->url = '/shared/onenote_menu_cnr/csss/omc.css';
	
	$r[0][6] = new stdClass;
	$r[0][6]->type = 'link';
	$r[0][6]->url = '/shared/menu/btn/csss/btn.css';
	
	$r[0][7] = new stdClass;
	$r[0][7]->type = 'link';
	$r[0][7]->url = '/shared/scroll_indicator/csss/si.css';
//grp1
	$r[1] = [];
	
	//sadly no object literals in php
	$r[1][0] = new stdClass;
	$r[1][0]->type = 'cpnList';
	$r[1][0]->cpns = [];
	
	$r[1][0]->cpns[0] = new stdClass;
	$r[1][0]->cpns[0]->name = 'bg_basic';
	$r[1][0]->cpns[0]->required = true;
	//no close, define close true if needed
	
	$r[1][1] = new stdClass;
	$r[1][1]->type = 'link';
	$r[1][1]->url = '/homepage/csss/hp.css';
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'html';
	ob_start();//output buffering
	require root.'homepage/html.php';
	$r[2][0]->html = ob_get_clean();
//grp3
	$r[3] = [];

	$r[3][0] = new stdClass;
	$r[3][0]->type = 'script';
	$r[3][0]->url = '/homepage/jss/hp.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>