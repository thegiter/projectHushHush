<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	if (isset($_GET['se'])) {
		$ses = [$_GET['se']];
	} else {
		$ses = ['shse', 'szse', 'jse', 'nyse', 'nasdaq'];
	}
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if ($mysqli->connect_error) {
		echo 'User / DB Connection Error';//or die(mysql_error());
	} else {//then excute sql query
		foreach ($ses as $se) {
			$result = $mysqli->query('SHOW TABLES LIKE "'.$se.'_defs"');
			
			if (!$result) {
				die('check table query errored: '.$se);
			}
			
			if ($result->num_rows > 0) {
				//drop table
				$result = $mysqli->query('DROP TABLE '.$se.'_defs');
				
				if (!$result) {
					die('drop table failed: '.$se);
				}
			}
			
			//create from scratch
			require_once root.'se/cmm/lib/db_cols.php';
			
			$q = 'CREATE TABLE '.$se.'_defs(
				tkr VARCHAR(6) NOT NULL,';
			
			//def_cols is an array from db_cols.php
			foreach ($def_cols as $name => $type) {
				$q .= '
				'.$name.' '.$type.',';
			}
				
			$q .= '
				PRIMARY KEY (tkr)
			)';
			
			$result = $mysqli->query($q);
				
			if (!$result) {
				die('Could not create table: '.$mysqli->error);
			}
			
			echo 'Table created successfully.';
		}
	}
?>