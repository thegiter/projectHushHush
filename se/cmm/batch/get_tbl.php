<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	switch ($_POST['se']) {
		case 'SHSE':
		case 'SZSE':
		case 'JSE':
			break;
		default:
			die('invalid stock exchange');
	}
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		echo 'User Connection Error';//or die(mysql_error());
	} else {//then select db
		if (!@mysql_select_db(DB_NAME)) {
			echo 'Database Connection Error';//mysql_error();
		} else {//then excute sql query
			//get all tickers from the se table
			if (!($tkrs = @mysql_query('SELECT * FROM '.strtolower($_POST['se']).'_tkrs'))) {
				echo 'get data from db error';
			} else {
				$rsp = new stdClass;
				$rsp->html = '';
				
				while ($tkr = mysql_fetch_array($tkrs)) {
					$rsp->html .= '<tr>
						<td>
							'.$tkr['tkr'].'
						</td><td>
							'.htmlspecialchars($tkr['name']).'
						</td>
					</tr>';
				}
				
				$rsp->status = 'success';
				
				echo json_encode($rsp);
			}
		}
	}
?>