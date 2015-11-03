<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}
	
	require_once root.'se/siphon/cmm/lib/siphon_data.php';
	
	echo json_encode(siphon_stock_def_CNY($_POST['ticker'], $_POST['car'], $_POST['cc'], $_POST['ir']));
?>