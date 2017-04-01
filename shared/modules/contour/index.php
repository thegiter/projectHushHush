<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require_once root.'shared/cache_ctrl/validate.php';

	//not using etag, because apache deflate gzip changes it
	//validate also sets the headers for lastmodified and etag
	cacheCtrlModule::validate('Thu, 17 Mar 2017 14:42:40 GMT');

	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';

	require_once root.'shared/cache_ctrl/front_end.php';

	require_once root.'shared/cache_ctrl/json.php';

	$manifest = new stdClass;

	//provide hook only if the module provides a hook in its js
	$manifest->hook = 'contour';

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
	$r[0][0]->url = '/shared/modules/contour/imgs/tl.png';

	$r[0][1] = new stdClass;
	$r[0][1]->type = 'img';
	$r[0][1]->url = '/shared/modules/contour/imgs/top.png';

	$r[0][2] = new stdClass;
	$r[0][2]->type = 'img';
	$r[0][2]->url = '/shared/modules/contour/imgs/tr.png';

	$r[0][3] = new stdClass;
	$r[0][3]->type = 'img';
	$r[0][3]->url = '/shared/modules/contour/imgs/lft.png';

	$r[0][4] = new stdClass;
	$r[0][4]->type = 'img';
	$r[0][4]->url = '/shared/modules/contour/imgs/rgt.png';

	$r[0][5] = new stdClass;
	$r[0][5]->type = 'img';
	$r[0][5]->url = '/shared/modules/contour/imgs/bl.png';

	$r[0][6] = new stdClass;
	$r[0][6]->type = 'img';
	$r[0][6]->url = '/shared/modules/contour/imgs/btm.png';

	$r[0][7] = new stdClass;
	$r[0][7]->type = 'img';
	$r[0][7]->url = '/shared/modules/contour/imgs/br.png';
//grp1
	$r[1] = [];

	$r[1][0] = new stdClass;
	$r[1][0]->type = 'link';
	$r[1][0]->url = '/shared/modules/contour/csss/contour.css';

	//modules also need to be defined
	//however, the scrl_ind module that omc uses is not a necessary module
//grp2
	$r[2] = [];

	$r[2][0] = new stdClass;
	$r[2][0]->type = 'script';
	$r[2][0]->url = '/shared/modules/contour/jss/contour.js';
	//define $r[][]->async = false; to turn off async loading for the script

	echo json_encode($manifest);
?>
