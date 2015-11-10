<?php
	if (!defined('root')) {
		define('root', '../');
	}
	
	//get table rows
	//it no table rows, do nothing
	//else construct email and send email and clear table
	require_once root.'se/cmm/lib/db.php';
	
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		die('User Connection Error');//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			die('Database Connection Error');//mysql_error();
		} else {//then excute sql query
			//get all tickers from the se table
			if (!($advice_tbl = @mysql_query('SELECT * FROM advice_updates'))) {
				die('select table error');
			} else {
				$msg = 'line 1
				
				line 2 after empty line.';
				
				mail('297154048@outlook.com', 'Simple Mail Test', $msg, 'From:no-reply@shps.co.za');

				if (mysql_num_rows($advice_tbl) <= 0) {
					die('no advice updates found');
				}
				
				$sells = [];
				$buys = [];
				
				while ($tkr_advice = mysql_fetch_array($advice_tbl)) {
					if ($tkr_advice['new_advice'] == 'sell') {
						array_push($sells, $tkr_advice);
					} else {
						array_push($buys, $tkr_advice);
					}
				}
				
				$msg = '<p>
					Dear My Master Boss:
				</p>
				<p>
					We have detected the following changes to market today.
				</p>';
				
				function construct_tkrs_msg($arr) {
					foreach ($arr as $tkrRow) {
						$name_result = mysql_query('SELECT name FROM shse_tkrs WHERE tkr='.$tkrRow['tkr']);
						
						if (!$name_result) {
							$name_result = mysql_query('SELECT name FROM szse_tkrs WHERE tkr='.$tkrRow['tkr']);
						}
						
						$name = mysql_fetch_array($name_result)['name'];
						
						$msg .= '<li>
							'.$tkrRow['tkr'].'		'.$name.'		old advice: '.$tkrRow['old_advice'].'		new advice: '.$tkrRow['new_advice'].'
						</li>';
					}
				}
				
				if (count($sells) > 0 ) {
					$msg .= '<h3>
						New sells
					</h3>
					
					<p>
						Our raw rough unrefined advice suggests to sell these stocks:
						
						<ul>';
					
					construct_tkrs_msg($sells);
					
					$msg .= '</ul>
					</p>';
				}
				
				if (count($buys) > 0) {
					$msg .= '<h3>
						New buys
					</h3>
					
					<p>
						Our raw rough unrefined advice suggests to buy these stocks:
						
						<ul>';
					
					construct_tkrs_msg($buys);
					
					$msg .= '</ul>
					</p>';
				}

				mail('297154048@outlook.com', 'SHPS SE Update', $msg, 'From:no-reply@shps.co.za\r\nMIME-Version: 1.0\r\nContent-type: text/html\r\n');
				
//				mysql_query('TRUNCATE table advice_updates');
			}
		}
	}
?>