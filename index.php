<?php
	if (!defined('root')) {
		define('root', '');
	}
	
	$dtc_browsers = array('ie', 'gc');
	require_once root.'shared/dtc/dtc.php';
	
	//as part of the website, the cache validation is not compatible with non supported browsers,
	//therfore put after browser detection
	//check if modified
	require_once root.'shared/cache_ctrl/validate.php';
	
	//not using etag, because apache deflate gzip changes it
	cacheCtrlModule::validate('Sun, 29 May 2016 13:20:06 GMT');
	
	require_once root.'shared/cache_ctrl/front_end.php';//not using far_future now for testing purposes
	
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>


		<!--<meta name="robots" content="noindex,follow" />-->
		<meta name="description" content="Desmond Zhu's personal website. Currently in its beta." />
		<meta name="keywords" content="弑魂,SHPS,Personal website,Desmond Zhu" />
		<meta name="google-site-verification" content="WiIzb9FaspCvU6Lxe-COLQr1_LSfwfWQOpkc5meOHNc" />

		<script src="/jss/installer.js" type="text/javascript">
		</script>
		
		<title>の弑す魂の PS</title>
	</head>
	
	<body id="the-shps-ajax-bd" class="fulbd">
	</body>
</html>