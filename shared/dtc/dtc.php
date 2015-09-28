<?php
	defined('root') or die;

	if (!isset($debug) || !$debug) {
		if (isset($_COOKIE['fieefb']) && $_COOKIE['fieefb']=='oxkecl23') {
			setcookie('fieefb', '');				//header function
		} else if (isset($_COOKIE['ios39jfra']) && ($_COOKIE['ios39jfra'] !== '')) {
			setcookie('ios39jfra', '');		//this set the header info to set cookie on client side
											//this does not change the $_COOKIE variable on the server side
											//that why it's fine to set it here before the cookies are read,
											//in addition, the cookie must be set here before the pages are sent.
											//else, it give an error "header info already sent"
			
			switch ($_COOKIE['ios39jfra']) {
				case '93jfa':
					require root.'shared/dtc/get/err_001.php';
					
					break;
				case 'd8ajn':
					require root.'shared/dtc/get/err_006.php';
			}
			
			die;
		} else {
			require root.'shared/phps/dtcs/btd.php' ;
		
			if (!detect_bot()) {
				require_once root.'shared/dtc/sniffing.php' ;
		
				die;
			}
		}
	}
?>