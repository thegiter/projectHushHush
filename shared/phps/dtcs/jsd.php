<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	if (!isset($debug) || !$debug) {
		if (isset($_COOKIE['fieefb']) && $_COOKIE['fieefb']=='oxkecl23') {
			setcookie('fieefb','');				//header function
		}
		else {
			require(root.'shared/phps/dtcs/btd.php');
		
			if (!detect_bot()) {
				require_once(root.'shared/phps/get/err_002.php');
		
				die();
			}
		}
	}
?>