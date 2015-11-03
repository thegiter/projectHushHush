<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}
	
	switch ($_GET['se']) {
		case 'shse':
		case 'szse':
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
			if (mysql_num_rows(mysql_query('SHOW TABLES LIKE "'.$_GET['se'].'_defs"')) == 1) {
				die('Table already exist. Modify table in use db.');
			} else {
				//create table
				if (!@mysql_query('CREATE TABLE '.$_GET['se'].'_defs(
					tkr VARCHAR(6) NOT NULL,
					mc DECIMAL(40, 30),
					bps DECIMAL(7, 4),
					so INT,
					ce DECIMAL(40, 30),
					der DECIMAL(6, 4),
					debt DECIMAL(40, 30),
					cap DECIMAL(40, 30),
					lyni DECIMAL(40, 30),
					lyie DECIMAL(40, 30),
					lypii DECIMAL(40, 30),
					lyltd DECIMAL(40, 30),
					lystd DECIMAL(40, 30),
					lyd DECIMAL(40, 30),
					lye DECIMAL(40, 30),
					lycap DECIMAL(40, 30),
					lypcr DECIMAL(32, 30),
					lyom DECIMAL(7, 4),
					slyom DECIMAL(7, 4),
					tlyom DECIMAL(7, 4),
					aomg DECIMAL(33, 30),
					apcr DECIMAL(32, 30),
					cpii DECIMAL(40, 30),
					wacodr DECIMAL(34, 30),
					interest DECIMAL(40, 30),
					fi DECIMAL(40, 30),
					lyroe DECIMAL(6, 4),
					slyroe DECIMAL(6, 4),
					tlyroe DECIMAL(6, 4),
					aroeg DECIMAL(33, 30),	
					afi DECIMAL(40, 30),
					fe DECIMAL(40, 30),
					car DECIMAL(4, 2),
					lper DECIMAL(7, 4),
					aper DECIMAL(33, 30),
					lpbr DECIMAL(7, 4),
					apbr DECIMAL(33, 30),
					gtlp DECIMAL(35, 30),
					lpgc BOOLEAN,
					gtap DECIMAL(35, 30),
					apgc BOOLEAN,
					cc BOOLEAN,
					pc BOOLEAN,
					anios DECIMAL(40, 30),
					fp DECIMAL(33, 30),
					fptm DECIMAL(33, 30),
					prcv DECIMAL(33, 30),
					iv DECIMAL(33, 30),
					cpivr DECIMAL(32, 30),
					pow DECIMAL(32, 30),
					advice VARCHAR(10),
					PRIMARY KEY (tkr)
				);')) {
					die('Could not create table: '.mysql_error());
				};
				
				echo "Table created successfully.";
			}
		}
	}
?>