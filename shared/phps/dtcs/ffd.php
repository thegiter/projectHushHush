<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	if (!isset($debug) || !$debug) {
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false)) {
			require_once(root.'shared/phps/get/err_003.php');
		
			die();
		}
	}
?>