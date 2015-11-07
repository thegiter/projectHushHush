<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		die('User Connection Error');//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			die('Database Connection Error');//mysql_error();
		} else {//then excute sql query
			//we assume table already exist
			if (!@mysql_query('ALTER TABLE shse_defs
			change cppftmr cpfptmr DECIMAL(32, 30)')) {
				die('shse rename cols error');
			}

			
			if (!@mysql_query('ALTER TABLE szse_defs
			change cppftmr cpfptmr DECIMAL(32, 30)')) {
				die('szse rename cols error');
			}
			
			echo 'Table altered!';
		}
	}
?>