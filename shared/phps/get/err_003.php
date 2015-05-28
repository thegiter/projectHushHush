<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	if (!isset($debug) || !$debug) {
		require_once(root.'shared/phps/dtcs/jsd.php');
	}

	require(root.'shared/phps/dtdec_x.php');
	
	echo "\n";
	
	require(root.'shared/phps/heads.php');
	
	echo "\n\n";
	
	require(root.'shared/errs/003.php');
?>