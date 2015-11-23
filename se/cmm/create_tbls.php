<?php
	if (!defined('root')) {
		define('root', '../../');
	}

	$ses = ['shse', 'szse', 'jse'];
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (!$mysqli) {
		echo 'User / DB Connection Error';//or die(mysql_error());
	} else {//then excute sql query
		foreach ($ses as $se) {
			$result = $mysqli->query('SHOW TABLES LIKE "'.$se.'_defs"');
			
			if (!$result) {
				die('check table query errored: '.$se);
			}
			
			if ($result->num_rows > 0) {
				//drop table
				$result = $mysqli->query('DROP TABLE "'.$se.'_defs"');
				
				if (!$result) {
					die('drop table failed: '.$se);
				}
			}
			
			//create from scratch
			$result = $mysqli->query('CREATE TABLE '.$se.'_defs(
				tkr VARCHAR(6) NOT NULL,
				mc DECIMAL(40, 30),
				bps DECIMAL(7, 4),
				so INT,
				ce DECIMAL(40, 30),
				der DECIMAL(6, 4),
				debt DECIMAL(40, 30),
				cap DECIMAL(40, 30),
				lyni DECIMAL(40, 30),
				t12mni DECIMAL(40, 30),
				lyie DECIMAL(40, 30),
				lypii DECIMAL(40, 30),
				lyltd DECIMAL(40, 30),
				lystd DECIMAL(40, 30),
				lyd DECIMAL(40, 30),
				lye DECIMAL(40, 30),
				lycap DECIMAL(40, 30),
				lypcr DECIMAL(32, 30),
				lyoi DECIMAL(40, 30),
				t12moi DECIMAL(40, 30),
				lyom DECIMAL(7, 4),
				slyom DECIMAL(7, 4),
				tlyom DECIMAL(7, 4),
				aomg DECIMAL(33, 30),
				t12maom DECIMAL(7, 4),
				lt12maom DECIMAL(7, 4),
				slt12maom DECIMAL(7, 4),
				tlt12maom DECIMAL(7, 4),
				wacodr DECIMAL(34, 30),
				lyroe DECIMAL(7, 4),
				slyroe DECIMAL(7, 4),
				tlyroe DECIMAL(7, 4),
				aroeg DECIMAL(33, 30),
				t12maroe DECIMAL(7, 4),
				lt12maroe DECIMAL(7, 4),
				slt12maroe DECIMAL(7, 4),
				tlt12maroe DECIMAL(7, 4),
				pci DECIMAL(40, 30),
				pa DECIMAL(32, 30),
				fe DECIMAL(40, 30),
				pfi DECIMAL(40, 30),
				apfi DECIMAL(40, 30),
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
				igr DECIMAL(32, 30),
				fp DECIMAL(33, 30),
				fptm DECIMAL(33, 30),
				prcv DECIMAL(33, 30),
				prlyv DECIMAL(33, 30),
				iv DECIMAL(33, 30),
				cp DECIMAL(7, 3),
				cpivr DECIMAL(32, 30),
				cpfptmr DECIMAL(32, 30),
				pow DECIMAL(32, 30),
				advice VARCHAR(32),
				PRIMARY KEY (tkr)
			)');
				
			if (!$result) {
				die('Could not create table: '.$mysqli->error);
			}
			
			echo 'Table created successfully.';
		}
	}
?>