<?php
	if (!defined('root')) {
		die;
	}

	$doc = JFactory::getDocument();

	//sadly no object literals in php
	$doc->shpsAjax->hook = 'portfolio';
	$doc->shpsAjax->ttl = 'Portfolio';

	//indicate only files necessary for page to intro,
	//these files will be cached

	//each file is loaded asynchronously
	//however, each group is installed sequentially
	//while each file within the group are installed parallel, i.e. installed as they are downloaded

	$r =& $doc->shpsAjax->rscs;

	$rGrpCnt = count($r);

//first grp
	$r[$rGrpCnt] = [];
	$r1 =& $r[$rGrpCnt];

	$r1[0] = new stdClass;
	$r1[0]->type = 'cpnList';
	$r1[0]->cpns = [];

	$r1[0]->cpns[0] = new stdClass;
	$r1[0]->cpns[0]->name = 'bg_basic';
	//$r1[0]->cpns[0]->required = true;//this means we either need this cpn in intro, or we need this cpn for another cpn

	$r1[0]->cpns[1] = new stdClass;
	$r1[0]->cpns[1]->name = 'bg_inttile';
	$r1[0]->cpns[1]->close = true;

	$r1[1] = new stdClass;
	$r1[1]->type = 'module';
	$r1[1]->url = '/shared/modules/lv2/common/';

	$r1[2] = new stdClass;
	$r1[2]->type = 'module';
	$r1[2]->url = '/shared/modules/lv2/featured/';

	$r1[3] = new stdClass;
	$r1[3]->type = 'module';
	$r1[3]->url = '/shared/modules/lv2/ctt_ldr/';
//sec grp
	$r[$rGrpCnt + 1] = [];
	$r2 =& $r[$rGrpCnt + 1];

	$r2[0] = new stdClass;
	$r2[0]->type = 'link';
	$r2[0]->url = '/portfolio/components/com_content/views/featured/tmpl/csss/default.css';
//3rd grp
	$r[$rGrpCnt + 2] = [];
	$r3 =& $r[$rGrpCnt + 2];

	$r3[0] = new stdClass;
	$r3[0]->type = 'script';
	$r3[0]->url = '/portfolio/components/com_content/views/featured/tmpl/jss/shpsptflhp.js';
	//define $r[][]->async = false; to turn off async loading for the script
?>
