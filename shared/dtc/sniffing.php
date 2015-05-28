<?php
	defined('root') or die;

	require root.'shared/phps/dtdec_h.php';
		
	echo "\n\n";

	if (!isset($debug) || !$debug) {
		echo "\t\t";
		
		foreach ($dtc_browsers as $name) {
			echo '<script type="text/javascript" src="/shared/dtc/jss/'.$name.'.js">
			</script>';
		}
		
		//must be after browser detection scripts
		echo '<script type="text/javascript" src="/shared/dtc/jss/js.js">
		</script>';
		echo "\n\n";
	}

	require root.'shared/phps/heads.php';

	echo "\n\n";
		
	$err = '002';
		
	require root.'shared/errs/tpl_dft.php';
?>