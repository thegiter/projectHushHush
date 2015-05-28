<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	if (!isset($debug) || !$debug) {
		require_once(root.'shared/phps/dtcs/ied.php');
		require_once(root.'shared/phps/dtcs/jsd.php');
	}

	header('HTTP Status Code: HTTP/1.1 403 Forbidden');
	
	require(root.'shared/phps/dtdec_x.php');

	echo "\n";
	
	require(root.'shared/phps/heads.php');
	
	echo "\n\n";

	$err = '403';

	require(root.'shared/errs/tpl_dft.php');
?>