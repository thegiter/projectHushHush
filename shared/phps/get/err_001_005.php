<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	if (!isset($debug) || !$debug) {
		require_once(root.'shared/phps/dtcs/jsd.php');
	}

	require(root.'shared/phps/dtdec_h.php');

	echo "\n";

	require(root.'shared/phps/heads.php');
	
	echo "\n\n";
	
	if ($debug) {
		$err = $_GET['err'];
	}

	require(root.'shared/errs/tpl_ie.php');
?>