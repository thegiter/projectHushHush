<?php
	if (!defined('root')) {
		die;
	}

	$doc = JFactory::getDocument();
	
	//sadly no object literals in php
	$doc->shpsAjax->hook = 'portfolio';
	$doc->shpsAjax->ttl = 'Portfolio';
	
	$doc->shpsAjax->csss[count($doc->shpsAjax->csss)] = '/portfolio/components/com_content/views/featured/tmpl/csss/default.css';
	$doc->shpsAjax->jss[count($doc->shpsAjax->jss)] = '/portfolio/components/com_content/views/featured/tmpl/jss/shpsptflhp.js';
	
	$cpnsCnt = count($doc->shpsAjax->cpns);
	
	$doc->shpsAjax->cpns[$cpnsCnt] = new stdClass;
	
	$doc->shpsAjax->cpns[$cpnsCnt]->name = 'bg_basic';
	$doc->shpsAjax->cpns[$cpnsCnt]->required = true;
	//no close, define close true if needed
				
	require_once root.'shared/lv2/featured/featured.php';
	
	$ftd = new lv2FtdModule;
	
	$ftd->cnr_id = 'shpsptfl-hp';
	$ftd->caption = true;
	
	echo $ftd;
?>