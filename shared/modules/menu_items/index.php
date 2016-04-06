<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Wed, 6 Apr 2016 14:42:40 GMT');
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';
	
	require_once root.'shared/cache_ctrl/front_end.php';
	
	require_once root.'shared/cache_ctrl/json.php';
	
	$manifest = new stdClass;
	
	//provide hook only if the module provides a hook in its js
	$manifest->hook = 'menu_items';
	
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
	$r[0][0]->type = 'json';
	$r[0][0]->url = '/portfolio/?option=com_content&view=categories&format=ajax';
	//the pName is used to define property name used by db if stored in db
	//as well the property name used by module mgr to store in the module cache for the scripts to access
	$r[0][0]->pName = 'miListPtfl';
	//define $r[][]->async = false; to turn off async loading for the script
	
	$r[0][1] = new stdClass;
	$r[0][1]->type = 'json';
	$r[0][1]->url = '/news/wp-admin/admin-ajax.php';
	//the pName is used to define property name used by db if stored in db
	//as well the property name used by module mgr to store in the module cache
	$r[0][1]->pName = 'miListNews';
	$r[0][1]->param = 'action=get_cats_menu';
	//define $r[][]->async = false; to turn off async loading for the script
//grp1
	$r[1] = [];
	
	$r[1][0] = new stdClass;
	$r[1][0]->type = 'script';
	$r[1][0]->url = '/shared/modules/menu_items/jss/mis.js';
	//$r[1][0]->async = false;//async must be off for this script, because this script has the hook function

	echo json_encode($manifest);
?>