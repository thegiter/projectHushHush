<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		echo 'User Connection Error';//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			echo 'Database Connection Error';//mysql_error();
		} else {//then excute sql query
			//check if table already exist
			if (mysql_num_rows(mysql_query('SHOW TABLES LIKE "advice_updates"')) == 1) {
				die('Table already exist.');
			} else {
				//create table
				if (!@mysql_query('CREATE TABLE advice_updates(
					tkr VARCHAR(6) NOT NULL,
					old_advice VARCHAR(10),
					new_advice VARCHAR(10),
					PRIMARY KEY (tkr)
				);')) {
					die('Could not create table: '.mysql_error());
				};
				
				echo "Table created successfully.";
			}
		}
	}
?>