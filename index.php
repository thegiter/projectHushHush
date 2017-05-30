<?php
	if (!defined('root')) {
		define('root', '');
	}

	$dtc_browsers = ['ie'];
	require_once root.'shared/dtc/dtc.php';

	//as part of the website, the cache validation is not compatible with non supported browsers,
	//therfore put after browser detection
	//check if modified
	require_once root.'shared/cache_ctrl/validate.php';

	//not using etag, because apache deflate gzip changes it
	cacheCtrlModule::validate('Sun, 28 May 2017 13:20:06 GMT');

	require_once root.'shared/cache_ctrl/front_end.php';//far_future is no longer needed because of service workers

	//server push is render blocking, push only the assets that are required for the initial render
	//requires http/2, which requires https
	$h = 'Link: </shared/csss/default.css>; rel=preload; as=style';
	$h .= ', </jss/installer.js>; rel=preload; as=script';
	header($h);

	require root.'shared/phps/dtdec_x.php';

	echo "\n";

	require root.'shared/phps/heads.php';
?>


		<!--<meta name="robots" content="noindex,follow" />-->
		<meta name="description" content="Desmond Zhu's personal website. Currently in its beta." />
		<meta name="keywords" content="弑魂,SHPS,Personal website,Desmond Zhu,Single Page,Progressive Web App,offline" />
		<meta name="google-site-verification" content="WiIzb9FaspCvU6Lxe-COLQr1_LSfwfWQOpkc5meOHNc" />

		<script src="/jss/installer.js">
		</script>

		<title>SHPS / の弑す魂の PS</title>
	</head>

	<body id="the-shps-ajax-bd" class="pos-abs-ful">
	</body>
</html>
