<?php
	if (!defined('root')) {
		define('root', '../../');
	}
?>		<meta name="robots" content="noindex,follow" />

		<script type="text/javascript" src="/shared/jss/common.js">
		</script>
		<script type="text/javascript" src="/shared/jss/bsc_twn.js" >
		</script>
		<script type="text/javascript" src="/shared/errs/jss/003.js">
		</script>
		<script type="text/javascript" src="/shared/jss/gaa.js">
		</script>

		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/003.css" charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="/shared/footer/sml/csss/footer.css" charset="utf-8" />

		<title>003 Error - の弑す魂の PS</title>
	</head>
	
	<body class="pos-stt">
		<div id="cnr" class="bd-wpr">
			<div class="dsp-tr">
				<div id="cnr-cel">
					<div id="ctt-cnr" class="ctr-mrg vsb-hid opa-0">
						<div id="cnr-tl">
						</div>
						<div id="cnr-top">
						</div>
						<div id="cnr-tr">
						</div>
						<div id="cnr-lft">
						</div>
						<div id="cnr-ctr">
							<div id="ctt-top">
							</div>
							<div id="ctt-lft">
							</div>
							<div id="ctt-ico">
								<div id="ico">
								</div>
								<div id="ico-btm">
								</div>
							</div>
							<div id="ctt-rgt" class="tahoma fnt-wht">
								<h3 class="no-mrg-top no-mrg-btm">
									Firefox can not load the page
								</h3>
				
								<div class="pos-rel">
									<?php
										require(root.'shared/phps/hr.php');
									?>
				
								</div>
				
								<p>
									The page you requested does not currently support Firefox
								</p>
				
								<div class="pos-rel">
									<?php
										require(root.'shared/phps/hr.php');
									?>
				
								</div>
				
								<ul id="err-003-lst">
									<li>
										It appears that you are using Firefox.
									</li>
									<li>
										If you are not using Firefox, make sure the user agent string in the HTTP header your browser sends does not contain anything "Firefox".
									</li>
									<li>
										It is recommended to use Google Chrome to view the page.
									</li>
								</ul>
				
								<button type="button" id="err-003-btn" class="tahoma" title="Open the Google Chrome EULA page.">Download Google Chrome</button>
							</div>
						</div>
						<div id="cnr-rgt">
						</div>
						<div id="cnr-bl">
						</div>
						<div id="cnr-btm">
						</div>
						<div id="cnr-br">
						</div>
					</div>
				</div>
			</div>
			<div class="dsp-tr">
				<div class="dsp-tc" id="err-003-cr">
					<?php
						require(root.'shared/footer/sml/footer.php');
					?>
					
				</div>
			</div>
		</div>
	</body>
</html>