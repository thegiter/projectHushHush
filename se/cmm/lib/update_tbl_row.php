<?php
	defined('root') or die;
	
	require_once root.'se/cmm/lib/db_cols.php';
	
	foreach ($def_cols as $name => $type) {
		array_push($def_names, $name);
	}
	
	function se_update_tbl_row_siphoned($tbl_name, $tkr, $def) {
		global $def_names;
		
		require_once root.'se/cmm/lib/db.php';
		
		//establish connection
		if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
			die('User Connection Error');//or die(mysql_error());
		} else {//then select db
			if (!@mysql_select_db(DB_NAME)) {
				die('Database Connection Error');//mysql_error();
			} else {//then excute sql query
				$q_string = 'INSERT INTO '.$tbl_name.'(tkr, ';
				
				foreach ($def_names as $idx => $def_name) {
					$q_string .= $def_name;
					
					if (!($idx >= (count($def_names) - 1)) ) {
						$q_string .= ', ';
					}
				}
				
				$q_string .= ')
				VALUES("'.$tkr.'", ';
				
				foreach ($def_names as $idx => $def_name) {
					if ($def_name == 'advice') {
						$q_string .= '"'.$def->{$def_name}.'"';
					} else {
						$q_string .= $def->{$def_name};
					}
					
					if (!($idx >= (count($def_names) - 1)) ) {
						$q_string .= ', ';
					}
				}
				
				$q_string .= ')
				ON DUPLICATE KEY UPDATE ';
				
				foreach ($def_names as $idx => $def_name) {
					$q_string .= $def_name.'=VALUES('.$def_name.')';
					
					if (!($idx >= (count($def_names) - 1)) ) {
						$q_string .= ', ';
					}
				}
			
				//we assume table already exist
				if (!@mysql_query($q_string)) {
					die('insert / update def row error
					'.$q_string.'
					'.json_encode($def));
				}
			}
		}
	}
?>