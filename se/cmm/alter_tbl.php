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
			add (
				lqom DECIMAL(7, 4),
				slqom DECIMAL(7, 4),
				tlqom DECIMAL(7, 4),
				flqom DECIMAL(7, 4),
				t4qaom DECIMAL(7, 4),
				tlomr DECIMAL(7, 4),
				lqroe DECIMAL(7, 4),
				slqroe DECIMAL(7, 4),
				tlqroe DECIMAL(7, 4),
				flqroe DECIMAL(7, 4),
				t4qaroe DECIMAL(7, 4),
				tlroer DECIMAL(7, 4)
			)')) {
				die('shse add cols error');
			}
			
			if (!@mysql_query('ALTER TABLE szse_defs
			add (
				lqom DECIMAL(7, 4),
				slqom DECIMAL(7, 4),
				tlqom DECIMAL(7, 4),
				flqom DECIMAL(7, 4),
				t4qaom DECIMAL(7, 4),
				tlomr DECIMAL(7, 4),
				lqroe DECIMAL(7, 4),
				slqroe DECIMAL(7, 4),
				tlqroe DECIMAL(7, 4),
				flqroe DECIMAL(7, 4),
				t4qaroe DECIMAL(7, 4),
				tlroer DECIMAL(7, 4)
			)')) {
				die('szse add cols error');
			}
			
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