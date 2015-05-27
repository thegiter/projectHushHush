<?php
	if (!defined('root')) {
		define('root', '../');
	}
	
	$debug = true;

	if (!defined('da')) {
		define('da', false);
	}

	require root.$_GET['url'];
?>