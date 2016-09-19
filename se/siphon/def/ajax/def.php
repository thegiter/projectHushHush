<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}
	
	require_once root.'se/siphon/cmm/lib/analyze_data.php';
	
	echo json_encode(seAnalyze::getStockDef($_POST['ticker'], $_POST['car'], $_POST['cc'], true));//true to refresh
?>