<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';

	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Tue, 29 May 2017 14:42:40 GMT');

	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';

	require_once root.'shared/cache_ctrl/front_end.php';

	require_once root.'shared/cache_ctrl/json.php';

	//server push is render blocking, push only the assets that are required for the initial render
	//requires http/2, which requires https
	$h = 'Link: </shared/modules/story_board/csss/sb.css>; rel=preload; as=style';
	$h .= ', </shared/modules/story_board/jss/sb.js>; rel=preload; as=script';
	header($h);

	$manifest = new stdClass;

	//provide hook only if the module provides a hook in its js
	$manifest->hook = 'story_board';

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
	$r[0][0]->type = 'link';
	$r[0][0]->url = '/shared/modules/story_board/csss/sb.css';
//grp1
	$r[1] = [];

	$r[1][0] = new stdClass;
	$r[1][0]->type = 'script';
	$r[1][0]->url = '/shared/modules/story_board/jss/sb.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>
