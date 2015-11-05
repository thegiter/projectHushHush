<?php
	if (!defined('root')) {
		define('root', '../../../../');
	}

	require_once root.'se/siphon/cmm/lib/siphon_data.php';
	
	echo json_encode(siphon_stock_def_CNY('SZSE:000403', 1.1, 1, .012));
?>