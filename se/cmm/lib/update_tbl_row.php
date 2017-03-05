<?php
	defined('root') or die;
	
	require_once root.'se/cmm/lib/db_cols.php';
	
	$def_names = [];
	
	foreach ($def_cols as $name => $type) {
		array_push($def_names, $name);
	}
	
	function se_update_tbl_row_siphoned($tbl_name, $tkr, $def) {
		global $def_names;
		
		require_once root.'se/cmm/lib/db.php';
		
		//establish connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		if ($mysqli->connect_error) {
			die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
		} else {
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
				} else if ($def_name == 'lu') {
					$q_string .= 'CURRENT_DATE()';
				} else {
					$value = $def->{$def_name};
					
					if ((!$value) || ($value == 'N/A') || is_nan($value)) {
						$value = 'NULL';
					}
					
					$q_string .= $value;
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
			if (!$mysqli->query($q_string)) {
				die('insert / update def row error
				'.$q_string.'
				'.json_encode($def).'
				'.$mysqli->error);
			}
		}
	}
?>