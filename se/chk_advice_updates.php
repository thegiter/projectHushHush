<?php
	if (!defined('root')) {
		define('root', '../');
	}
	
	function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '') {
		$from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
		$subject = "=?UTF-8?B?".base64_encode($subject)."?=";

		$headers = "From: $from_user <$from_email>\r\n".
		"MIME-Version: 1.0" . "\r\n" .
		"Content-type: text/html; charset=UTF-8" . "\r\n";

		return mail($to, $subject, $message, $headers);
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
				if (mysql_num_rows($advice_tbl) <= 0) {
					die('no advice updates found');
				}
				
				$sells = [];
				$buys = [];
				$readies = [];
				$bettings = [];
				
				while ($tkr_advice = mysql_fetch_array($advice_tbl)) {
					switch ($tkr_advice['new_advice']) {
						case 'sell':
							array_push($sells, $tkr_advice);
							
							break;
						case 'buy':
							array_push($buys, $tkr_advice);
							
							break;
						case 'be ready to sell':
							array_push($readies, $tkr_advice);
							
							break;
						case 'betting buy':
							array_push($bettings, $tkr_advice);
							
							break;
						default:
					}
				}
				
				$msg = '<p>
					Dear My Master Boss:
				</p>
				
				<p>
					We have detected the following changes to market today.
				</p>';
				
				function construct_tkrs_msg($arr) {
					$msg = '';
					
					foreach ($arr as $tkrRow) {
						$name_result = mysql_query('SELECT name FROM shse_tkrs WHERE tkr='.$tkrRow['tkr']);
						
						if (mysql_num_rows($name_result) <= 0) {
							$name_result = mysql_query('SELECT name FROM szse_tkrs WHERE tkr='.$tkrRow['tkr']);
						}
						
						$name = mysql_fetch_array($name_result)['name'];
						
						$msg .= '<li>
							<span style="margin-left:5px;margin-right:5px">'.$tkrRow['tkr'].'</span><span style="margin-left:5px;margin-right:5px">'.$name.'</span><span style="margin-left:5px;margin-right:5px">old advice: '.$tkrRow['old_advice'].'</span><span style="margin-left:5px;margin-right:5px">new advice: '.$tkrRow['new_advice'].'</span>
						</li>';
					}
					
					return $msg;
				}
				
				if (count($sells) > 0 ) {
					$msg .= '<h3>
						New sells
					</h3>
					
					<p>
						Our raw rough unrefined advice suggests to sell these stocks:
						
						<ul>
							'.construct_tkrs_msg($sells).'
						</ul>
					</p>';
				}
				
				if (count($buys) > 0) {
					$msg .= '<h3>
						New buys
					</h3>
					
					<p>
						Our raw rough unrefined advice suggests to buy these stocks:
						
						<ul>
							'.construct_tkrs_msg($buys).'
						</ul>
					</p>';
				}

				if (count($readies) > 0 ) {
					$msg .= '<h3>
						New Sells Maturing Soon
					</h3>
					
					<p>
						Our raw rough unrefined advice suggests to be ready to sell these stocks:
						
						<ul>
							'.construct_tkrs_msg($readies).'
						</ul>
					</p>';
				}
				
				if (count($bettings) > 0 ) {
					$msg .= '<h3>
						New Betting Buys
					</h3>
					
					<p>
						Our raw rough unrefined advice suggests to betting buy these stocks:
						
						<ul>
							'.construct_tkrs_msg($bettings).'
						</ul>
					</p>';
				}
				
				mail_utf8('297154048@outlook.com', 'SHPS Automatic Emailer (No reply)', 'no-reply@shps.co.za', 'SHPS SE Update', $msg);
				
				mysql_query('TRUNCATE table advice_updates');
			}
		}
	}
?>