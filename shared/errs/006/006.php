<?php
	defined('root') or die;
?>		<meta name="robots" content="noindex,follow" />
		<meta name="google-site-verification" content="WiIzb9FaspCvU6Lxe-COLQr1_LSfwfWQOpkc5meOHNc" />

		<script type="text/javascript" src="/shared/jss/common.js">
		</script>
		<script type="text/javascript" src="/shared/errs/006/jss/006.js">
		</script>
		<script type="text/javascript" src="/shared/jss/gtm.js">
		</script>

		<link rel="stylesheet" type="text/css" href="/shared/errs/006/csss/006.css" charset="utf-8" />
		
		<title>006 Error - の弑す魂の PS</title>
	</head>
	
	<body>
		<div class="bd-wpr shared-css-bg-basic err-006 err-006-real">
			<div class="dsp-tr">
				<div class="dsp-tc">
					<div class="wpr">
						<div class="bg-top fade-in-norm opa-0">
						</div>
						<div class="bg-lft fade-in-norm opa-0">
						</div>
						<div class="bg-rgt fade-in-norm opa-0">
						</div>
						<div class="bg-btm fade-in-norm opa-0">
						</div>
						<div class="cnr">
							<div class="bg-top fade-in-norm opa-0">
							</div>
							<div class="bg-lft fade-in-norm opa-0">
							</div>
							<div class="bg-rgt fade-in-norm opa-0">
							</div>
							<div class="bg-btm fade-in-norm opa-0">
							</div>
							<div class="grad-bg">	<!-- gradient background -->
							</div>
							<section class="top-half vertical-padding">
								<div class="logo">
								</div>
								
								<h3>
									Unable to view this page
								</h3>
								
								<button id="err-006-btn" class="btn">
									More
								</button>
							</section>
							<section id="err-006-btm-half" class="btm-half vsb-hid h-0">
								<div class="grad-bg">
								</div>
								<p>
									Your browser is not currently supported by this page because it is detected as Google Chrome.
								</p>
								<p>
									You can solve the problem by taking the following steps:
								</p>
								<p>
									<?php
										require root.'shared/dtc/rec.php';
										
										$recBrow = new dtcRecModule;
										
										if (isset($dtc_rec) && $dtc_rec != 'gc') {
											$recBrow->bc = $dtc_rec;
										}
										
										$recBrowInfo = $recBrow->getInfo();
									?>
									Go to <span class="bold"><a href="<?php
										echo $recBrowInfo['href'];
									?>" class="tgt-blank" target="_blank" title="<?php
										echo $recBrowInfo['ttl'];
									?>">the <?php
										echo $recBrowInfo['name'];
									?> download page</a> > Free Download > Install > Run <?php
										echo $recBrowInfo['name'];
									?> > Visit this page again with <?php
										echo $recBrowInfo['name'];
									?></span>.
								</p>
								<p class="code">
									Error Code: ERR_006
								</p>
							</section>
						</div>
					</div>
				</div>
			</div>	
			<div class="dsp-tr">
				<div class="err-006-ftr-cnr">
					<?php
						require root.'shared/modules/ftr/sml/footer.php';
					?>
				</div>
			</div>
		</div>
	</body>
</html>