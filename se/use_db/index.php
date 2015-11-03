<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	require root.'se/cmm/batch/bs_html.php';
	
	echo '<script src="jss/udb.js" async="async" type="text/javascript"></script>';
	
	require root.'se/cmm/batch/as_html.php'
?>