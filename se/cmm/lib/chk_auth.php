<?php
	defined('root') or die;
	
	if (isset($_POST['ee25d6'])) {
		session_id($_POST['ee25d6']);
		session_start();
		
		if ($_SESSION['logged_in'] != true) {
			die;
		}
	} else if (!isset($_COOKIE['fakljkd']) || $_COOKIE['fakljkd'] != 'oxkecl23') {
		//please login
		die;
	} else {
		session_start();
		
		$_SESSION['logged_in'] = true;
		
		setcookie('ee25d6', session_id());
	}
?>