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
			change t4qaom t12maom DECIMAL(7, 4),
			change slqom lt12maom DECIMAL(7, 4),
			change tlqom slt12maom DECIMAL(7, 4),
			change flqom tlt12maom DECIMAL(7, 4),
			change tlomr tlomr DECIMAL(33, 30),
			change t4qaroe t12maroe DECIMAL(7, 4),
			change slqroe lt12maroe DECIMAL(7, 4),
			change tlqroe slt12maroe DECIMAL(7, 4),
			change flqroe tlt12maroe DECIMAL(7, 4),
			change tlroer tlroer DECIMAL(33, 30)')) {
				die('shse rename cols error');
			}
			
			if (!@mysql_query('ALTER TABLE szse_defs
			change t4qaom t12maom DECIMAL(7, 4),
			change slqom lt12maom DECIMAL(7, 4),
			change tlqom slt12maom DECIMAL(7, 4),
			change flqom tlt12maom DECIMAL(7, 4),
			change tlomr tlomr DECIMAL(33, 30),
			change t4qaroe t12maroe DECIMAL(7, 4),
			change slqroe lt12maroe DECIMAL(7, 4),
			change tlqroe slt12maroe DECIMAL(7, 4),
			change flqroe tlt12maroe DECIMAL(7, 4),
			change tlroer tlroer DECIMAL(33, 30)')) {
				die('szse rename cols error');
			}
			
			if (!@mysql_query('ALTER TABLE shse_defs
			DROP lqom,
			DROP lqroe')) {
				die('shse remove cols error');
			}
			
			if (!@mysql_query('ALTER TABLE szse_defs
			DROP lqom,
			DROP lqroe')) {
				die('szse remove cols error');
			}
			
			echo 'Table altered!';
		}
	}
?>