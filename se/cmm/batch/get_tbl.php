<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	switch ($_POST['se']) {
		case 'SHSE':
		case 'SZSE':
		case 'JSE':
		case 'NYSE':
		case 'Nasdaq':
			break;
		default:
			die('invalid stock exchange');
	}
	
	require_once root.'se/cmm/lib/db.php';
	
	//establish connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if ($mysqli->connect_error) {
		echo 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
	} else {//then excute sql query
		//get all tickers from the se table
		if (!$tkrs = $mysqli->query('SELECT * FROM '.strtolower($_POST['se']).'_tkrs')) {
			echo 'get data from db error';
		} else {
			$tkrs->data_seek(0);
			
			$rsp = new stdClass;
			$rsp->html = '';
			
			while ($tkr = $tkrs->fetch_assoc()) {
				$rsp->html .= '<tr>
					<td data-se="'.$_POST['se'].'">
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
?>