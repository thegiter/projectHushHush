<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';

	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Sat, 13 May 2017 14:42:40 GMT');

	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';

	require_once root.'shared/cache_ctrl/front_end.php';

	require_once root.'shared/cache_ctrl/json.php';

	$manifest = new stdClass;

	//provide hook only if the module provides a hook in its js
	$manifest->hook = 'lv2_ctt_ldr';

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
	$r[0][0]->type = 'module';
	$r[0][0]->url = '/shared/modules/js_frameworks/iv_mgr/';

	$r[0][1] = new stdClass;
	$r[0][1]->type = 'img';
	$r[0][1]->url = '/shared/modules/lv2/ctt_ldr/imgs/bg.jpg';
//1
	$r[1] = [];

	$r[1][0] = new stdClass;
	$r[1][0]->type = 'link';
	$r[1][0]->url = '/shared/modules/lv2/ctt_ldr/csss/cl.css';
//2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'script';
	$r[2][0]->url = '/shared/modules/lv2/ctt_ldr/jss/cl.js';
	//define $r[][]->async = false; to turn off async loading for the script

	//modules also need to be defined
	//however, the scrl_ind module that omc uses is not a necessary module

	//html needs to be generated in php on the server side

	echo json_encode($manifest);
?>
