<?php
	defined('root') or die;

	require root.'shared/phps/dtdec_h.php';

	echo "\n";

	require root.'shared/phps/heads.php';
	
	echo "\n\n";
	
	if (isset($debug) && $debug) {
		$err = $_GET['err'];
	}
	else {
		$err = '001';
	}

	require root.'shared/errs/tpl_ie.php';
?>