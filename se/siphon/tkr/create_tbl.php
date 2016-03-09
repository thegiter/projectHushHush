<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	switch ($_GET['se']) {
		case 'shse':
		case 'szse':
		case 'jse':
		case 'nyse':
		case 'nasdaq':
			break;
		default:
			die('invalid stock exchange for table');
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
			if (mysql_num_rows(mysql_query('SHOW TABLES LIKE "'.$_GET['se'].'_tkrs"')) == 1) {
				die('Table already exist. Modify table in use db.');
			} else {
				//create table
				if (!@mysql_query('CREATE TABLE '.$_GET['se'].'_tkrs(
					tkr VARCHAR(6) NOT NULL,
					name TINYTEXT,
					PRIMARY KEY (tkr)
				);')) {
					die('Could not create table: '.mysql_error());
				};
				
				echo "Table created successfully.";
			}
		}
	}
?>