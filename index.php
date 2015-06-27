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
	cacheCtrlModule::validate('Sat, 20 Jun 2015 13:20:06 GMT');
	
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>


		<meta name="robots" content="noindex,follow" />
		<meta name="description" content="Desmond Zhu's personal website. Currently in its beta." />
		<meta name="keywords" content="弑魂,SHPS,Personal website,Desmond Zhu" />
		<meta name="robots" content="index,follow" />
		<meta name="google-site-verification" content="WiIzb9FaspCvU6Lxe-COLQr1_LSfwfWQOpkc5meOHNc" />

		<script src="/jss/installer.js" type="text/javascript" async="async">
		</script>
		
		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/002.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/cmn_dft.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/csss/hr.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/footer/sml/csss/footer.css"/>
		
		<title>002 Error - の弑す魂の PS</title>
	</head>
	
	<body class="fulbd">
		<div class="bd-wpr shared-css-bg-basic">								<!-- required for google chrome to show bd bg properly -->
			<?php
				require root.'shared/phps/logo.php';
			?>
			
			<div class="tpl-dft-bnr">
				<span id="err-002-ico" class="tpl-dft-ico"></span><h3 class="tpl-dft-ttl tahoma unbold">
					Javascript Disabled
				</h3>
			</div>
			<div class="tpl-dft-ctt">
				<div class="tpl-dft-ctt-txt verdana">					<!-- if this div is still not needed when the page structure is settled, it should be removed -->
					<p>
						Thank you for trying to browse my site.
					</p>
					<p>
						Unfortunately, this website does not support Javasript-disabled users, and it has been detected that your browser has disabled/blocked Javascript. (Please turn on your browser\'s Javascript and <a href="" title="Reload the page." class="refresh">try again</a>.)
					</p>
				</div>
			</div>
			<div class="pos-rel">
				<?php
					require root.'shared/phps/hr.php';
				?>
		
			</div>
			<div class="tpl-dft-cr ta-ctr">
				<?php
					require root.'shared/footer/sml/footer.php';
				?>
			
			</div>
		</div>
	</body>
</html>