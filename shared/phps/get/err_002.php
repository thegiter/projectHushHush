<?php
	if (!defined('root')) {
		define('root', '../../../');
	}

	require(root.'shared/phps/dtdec_h.php');
		
	echo "\n\n";

	if (!isset($debug) || !$debug) {
		echo "\t\t";
		echo '<script type="text/javascript" src="/shared/jss/jsd.js">
		</script>';
		echo "\n\n";
	}

	require(root.'shared/phps/heads.php');

	echo "\n\n";
		
	$err = '002';
		
	require(root.'shared/errs/tpl_dft.php');
?>