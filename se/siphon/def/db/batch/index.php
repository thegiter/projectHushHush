<?php
	if (!defined('root')) {
		define('root', '../../../../../');
	}
	
	require root.'se/cmm/lib/chk_auth.php';
	
	require root.'se/cmm/batch/bs_html.php';
	
	//decide whether refreshing is needed, refresh every 7 days
	//check last refresh date from file
	if (isset($_GET['force_refresh']) && ($_GET['force_refresh'] == 'force_refresh')) {
		echo '<script src="/se/siphon/def/db/batch/jss/force_refresh.js" type="text/javascript"></script>';
	} else {
		define('F_NAME', 'last_refresh.txt');
	
		$file = fopen(F_NAME, 'r');
		
		if ($file == false) {
			//skip, something went wrong, so we dont make anychanges
			//we would however, want to leave a note to indicate this, but nothing should go wrong though
			//because we are just opening a file
		} else {
			//read file
			$f_size = filesize(F_NAME);
			
			//if size is 0, file has not initailized
			if ($f_size <= 0) {
				$date = 0;
			} else {
				$date = fread($file, $f_size);
			}
			
			fclose($file);
			
			//convert to date, when last refreshed, the time() value is saved to file
			//we simply convert that back to date, and compare with the current time() value
			$last_time = strtotime($date);
			$now_time = time(); // or your date as well
			
			//check difference, in days
			$time_diff = $now_time - $last_time;
			$date_diff = floor($time_diff/(60*60*24));
			
			//if date difference is greater than 7 days
			if ($date_diff > 7) {
				//include the refresher js
				echo '<script src="/se/siphon/def/db/batch/jss/refresh.js" type="text/javascript"></script>';
			}
		}
	}
	
	if (isset($_GET['ignore_lu']) && ($_GET['ignore_lu'] == 'ignore')) {
		echo '<script src="/se/siphon/def/db/batch/jss/ignore_lu.js" type="text/javascript"></script>';
	}
	
	echo '<script src="/se/siphon/cmm/lib/jss/js_siphon.js" type="text/javascript"></script>';
	echo '<script src="/se/siphon/def/db/batch/jss/bs.js" async="async" type="text/javascript"></script>';
	
	if ($_GET['auto']) {
		echo '<script src="/se/siphon/def/db/batch/jss/auto.js" async="async" type="text/javascript"></script>';
	}
	
	require root.'se/cmm/batch/as_html.php';
?>