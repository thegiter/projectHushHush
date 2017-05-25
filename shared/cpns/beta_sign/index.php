<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';

	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Wed, 18 May 2017 14:42:40 GMT');

	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';

	require_once root.'shared/cache_ctrl/front_end.php';

	require_once root.'shared/cache_ctrl/json.php';

	$manifest = new stdClass;

	$manifest->hook = 'betaSign';

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
	$r[0][0]->url = '/shared/cpns/beta_sign/csss/bs.css';
//grp1
	$r[1] = [];

	$r[1][0] = new stdClass;
	$r[1][0]->type = 'script';
	$r[1][0]->url = '/shared/cpns/beta_sign/jss/bs.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>
