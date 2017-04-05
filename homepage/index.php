<?php
	if (!defined('root')) {
		define('root', '../');
	}

	require_once root.'shared/cache_ctrl/validate.php';

	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Tue, 5 Apr 2016 22:13:40 GMT');

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

	//the manifest only lists resources needed for immediate intro of the page
	//ie. the cnr
	//the rest will be loaded by the scripts

	$r =& $manifest->rscs;
	$r = [];

	//each file is loaded asynchronously
	//however, each group is installed sequentially
	//while each file within the group are installed parallel, i.e. installed as they are downloaded
//grp0
	$r[0] = [];

	//sadly no object literals in php
	$r[0][0] = new stdClass;
	$r[0][0]->type = 'cpnList';//the manifest file defines only the files for page to onload, other files are loaded in js if they are not required for intro anim
	//with the exception of components, components are all defined in the cpnList, and they can be set to required true or false
	//cpnList and modules are loaded as early as possible because they take time load, sometimes they take even more time than images
	$r[0][0]->cpns = [];

	$r[0][0]->cpns[0] = new stdClass;
	$r[0][0]->cpns[0]->name = 'bg_basic';
	$r[0][0]->cpns[0]->required = true;
	//no close, define close true if needed

	//load module before hp css, because hp css has stylings for these modules
	$r[0][1] = new stdClass;
	$r[0][1]->type = 'module';
	$r[0][1]->url = '/shared/modules/ftr/cr/';

	//images must be defined in a group before css, so that we will replace the url to the cache url
	$r[0][2] = new stdClass;
	$r[0][2]->type = 'img';
	$r[0][2]->url = '/homepage/imgs/bg_img.jpg';
//grp1
	$r[1] = [];

	$r[1][0] = new stdClass;
	$r[1][0]->type = 'link';
	$r[1][0]->url = '/homepage/csss/hp.css';
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'html';
	ob_start();//output buffering
	require root.'homepage/html.php';//must do html in manifest, because pg mgr is designed to create a page using html from manifest
	//it was not designed to get react elm from javascript, it could be changed to work like that but probably not worth it.
	$r[2][0]->html = ob_get_clean();
//grp3
	$r[3] = [];

	//even though onenote menu module and menu items are required module for page to work
	//we don't define them in the manifest because we will load them at a later stage in the js
	$r[3][0] = new stdClass;
	$r[3][0]->type = 'script';
	$r[3][0]->url = '/homepage/jss/hp.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>
