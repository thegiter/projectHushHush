<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	if (!isset($debug) || !$debug) {
		if ((isset($_SERVER['HTTP_USER_AGENT'])) && ((strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon 2.0') !== false))) {
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon 2.0') !== false) {
				$err = '005';
			}
			else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
				$err = '001';
			}
		
			require_once(root.'shared/phps/get/err_001_005.php');
	
			die();
		}
	}
?>