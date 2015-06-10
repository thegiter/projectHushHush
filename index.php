<?php
	if (!defined('root')) {
		define('root', '');
	}
	if (!defined('da')) {
		define('da', false);
	}

	$dtc_browsers = array('ie', 'gc');
	require_once root.'shared/dtc/dtc.php';
	
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>


		<meta name="description" content="Desmond's personal website. Currently in its beta." />
		<meta name="keywords" content="弑魂,SHPS,Personal website,Desmond" />
		<meta name="robots" content="index,follow" />
		<meta name="google-site-verification" content="WiIzb9FaspCvU6Lxe-COLQr1_LSfwfWQOpkc5meOHNc" />
		
		<!--must be above all local scripts
		not async because it must be loaded before other js-->
		<script src="/shared/jss/common.js" type="text/javascript">
		</script>
		
		<link rel="stylesheet" type="text/css" href="/csss/shps.css"/><!-- put first because this stylesheet is most important -->
		<!-- followed by the javascripts -->
		<script src="/jss/shps.js" type="text/javascript" async="async"><!--		these jss are order-sensitive -->
		</script>
		
		<link rel="stylesheet" type="text/css" href="/shared/footer/cr/csss/cr.css"/>
		
		<link rel="stylesheet" type="text/css" href="/shared/csss/beta/init_stg.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/csss/beta/beta.css"/>
		<!-- beta js is put after beta css, because html5 parsing dictates that js must wait for preceding css, so we group relevant css and js together
			and make sure css precedes the js, this ensures that the css is always downloaded, which contains important stylings -->
		<script src="/shared/jss/beta.js" type="text/javascript" async="async">			
		</script>
		
		<title>の弑す魂の PS</title>
	</head>
	
	<body class="fulbd">
		<div id="psd-bd-cnr">	<!-- pseudo body container -->
			<aside id="psd-menu-cnr">
				<div id="psd-menu-scrl-cnr">
				</div>
				<div class="sdw">
				</div>
			</aside>
			
			<section id="psd-vp-cnr" class="psd-vp-cnr"><!--pseudo viewport container-->
				<div id="psd-bg-cnr">
				</div>
				<div id="psd-ctt-scrl-cnr" class="psd-ctt-scrl-cnr">
					<div>
					</div>
				</div>

				<main id="psd-ctt-cnr" role="main">
				</main>
			</section>
			
			<aside>
			</aside>
		</div>
		<div id="shps-logo-cnr">
			<h1 id="shps-logo">
				<a><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						S
					</div>
				</span><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						H
					</div>
				</span><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						P
					</div>
				</span><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						S
					</div>
				</span></a>
			</h1>
		</div>

		<?php
			require root.'shared/phps/beta.php';
		?>

	</body>
</html>