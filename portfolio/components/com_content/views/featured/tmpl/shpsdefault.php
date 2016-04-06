<?php
	if (!defined('root')) {
		die;
	}

	$doc = JFactory::getDocument();
	
	//sadly no object literals in php
	$doc->shpsAjax->hook = 'portfolio';
	$doc->shpsAjax->ttl = 'Portfolio';
	
	$r =& $doc->shpsAjax->rscs;
	
	$rGrpCnt = count($r);

//first grp
	$r[$rGrpCnt] = [];
	$r1 =& $r[$rGrpCnt];
	
	$r1[0] = new stdClass;
	$r1[0]->type = 'img';
	$r1[0]->url = '/portfolio/components/com_content/views/featured/tmpl/imgs/ptfl_ttl.png';
	
	$r1[1] = new stdClass;
	$r1[1]->type = 'link';
	$r1[1]->url = '/shared/lv2/featured/csss/featured.css';
//sec grp
	$r[$rGrpCnt + 1] = [];
	$r2 =& $r[$rGrpCnt + 1];
	
	$r2[0] = new stdClass;
	$r2[0]->type = 'cpnList';
	$r2[0]->cpns = [];
	
	$r2[0]->cpns[0] = new stdClass;
	$r2[0]->cpns[0]->name = 'bg_basic';
	$r2[0]->cpns[0]->required = true;
	
	$r2[0]->cpns[1] = new stdClass;
	$r2[0]->cpns[1]->name = 'bg_tdcubes';
	$r2[0]->cpns[1]->close = true;
	
	$r2[1] = new stdClass;
	$r2[1]->type = 'link';
	$r2[1]->url = '/portfolio/components/com_content/views/featured/tmpl/csss/default.css';
//3rd grp
	$r[$rGrpCnt + 2] = [];
	$r3 =& $r[$rGrpCnt + 2];
	
	$r3[0] = new stdClass;
	$r3[0]->type = 'script';
	$r3[0]->url = '/portfolio/components/com_content/views/featured/tmpl/jss/shpsptflhp.js';
	//define $r[][]->async = false; to turn off async loading for the script
				
	require_once root.'shared/lv2/featured/featured.php';
	
	$ftd = new lv2FtdModule;
	
	$ftd->cnr_id = 'shpsptfl-hp';
	$ftd->caption = true;
	
	echo $ftd;
?>