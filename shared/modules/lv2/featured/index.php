<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Thu, 7 Apr 2016 14:42:40 GMT');
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	$manifest = new stdClass;
	
	//provide hook only if the module provides a hook in its js
	$manifest->hook = 'lv2_featured';
	
	$r =& $manifest->rscs;
	$r = [];
	
	//indicate only files necessary for module to work,
	//these files will be cached
	
	//each file is loaded asynchronously
	//however, each group is installed sequentially
	//while each file within the group are installed parallel, i.e. installed as they are downloaded
//grp0
	$r[0] = [];
	
	$r[0][0] = new stdClass;
	$r[0][0]->type = 'img';
	$r[0][0]->url = '/shared/modules/lv2/featured/imgs/ptn.jpg';
	
	$r[0][1] = new stdClass;
	$r[0][1]->type = 'img';
	$r[0][1]->url = '/shared/modules/lv2/featured/imgs/bg/ttl_bd_sdw.jpg';
	
	$r[0][2] = new stdClass;
	$r[0][2]->type = 'img';
	$r[0][2]->url = '/shared/modules/lv2/featured/imgs/bg/ttl_ass.png';
	
	$r[0][3] = new stdClass;
	$r[0][3]->type = 'img';
	$r[0][3]->url = '/shared/modules/lv2/featured/imgs/bg/caption_hd.png';
	
	$r[0][4] = new stdClass;
	$r[0][4]->type = 'img';
	$r[0][4]->url = '/shared/modules/lv2/featured/imgs/bg/caption_bd_sdw.jpg';
//grp1
	$r[1] = [];
	
	$r[1][0] = new stdClass;
	$r[1][0]->type = 'link';
	$r[1][0]->url = '/shared/modules/lv2/featured/csss/ftd.css';
	
	//modules also need to be defined
	//however, the scrl_ind module that omc uses is not a necessary module
	
	//html needs to be generated in php on the server side
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'script';
	$r[2][0]->url = '/shared/modules/lv2/featured/jss/ftd.js';

	echo json_encode($manifest);
?>