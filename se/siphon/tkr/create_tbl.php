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
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if ($mysqli->connect_error) {
		echo 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
	else {//then excute sql query
		//check if table already exist
		$result = $mysqli->query('SHOW TABLES LIKE "'.$_GET['se'].'_tkrs"');
		
		if ($result->num_rows >= 1) {
			die('Table already exist. Modify table in use db.');
		} else if (!$mysqli->query('CREATE TABLE '.$_GET['se'].'_tkrs(
			tkr VARCHAR(6) NOT NULL,
			name TINYTEXT,
			PRIMARY KEY (tkr)
		);')) {//create table
			die('Could not create table: '.$mysqli->error);
		}
			
		echo "Table created successfully.";
	}
?>