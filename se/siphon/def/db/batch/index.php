<?php
	if (!defined('root')) {
		define('root', '../../../../../');
	}
	
	require root.'se/cmm/batch/bs_html.php';
	
	echo '<script src="/se/siphon/cmm/lib/jss/js_siphon.js" type="text/javascript"></script>';
	echo '<script src="/se/siphon/def/db/batch/jss/bs.js" async="async" type="text/javascript"></script>';
	
	if ($_GET['auto']) {
		echo '<script src="/se/siphon/def/db/batch/jss/auto.js" async="async" type="text/javascript"></script>';
	}
	
	require root.'se/cmm/batch/as_html.php';
?>