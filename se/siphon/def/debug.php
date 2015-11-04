<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	$ctt = file_get_contents('http://www.gurufocus.com/term/mktcap/SHSE:600000/Market%2BCap/');
	echo $ctt;					
	preg_match('/data_value"\>CN¥(.+) Mil/', $ctt, $matches);
	echo json_encode($matches);	
	echo $matches[0];
echo $matches[1];	
	require_once root.'se/siphon/cmm/lib/siphon_data.php';
	
	echo json_encode(siphon_stock_def_CNY('SHSE:000403', 1.1, 1, .012));
?>