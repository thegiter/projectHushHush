<?php
	if (!defined('root')) {
		define('root', '../../');
	}
?>		<meta name="robots" content="noindex,follow" />

		<script src="/shared/jss/common.js" type="text/javascript">			<!-- place before any other javascripts -->
		</script>
		<script type="text/javascript" src="/shared/jss/bsc_twn.js">
		</script>
		<script src="/shared/errs/jss/<?php
			echo $err;
		?>.js" type="text/javascript">
		</script>
		<script src="/shared/errs/jss/tpl_ie.js" type="text/javascript">
		</script>
		<script type="text/javascript" src="/shared/jss/gaa.js">
		</script>

		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/<?php
			echo $err;
		?>.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/read_bg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/cmn_ie.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/hr.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo_sml.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/footer/sml/csss/footer.css" charset="utf-8" />
		
		<title><?php
			echo $err;
		?> Error - の弑す魂の PS</title>
	</head>
	
	<body class="bd-wpr shared-css-bg-basic">
		<div id="cnr" class="vsb-hid">
			<?php
				require(root.'shared/phps/read_bg.php');
				
				switch ($err) {
					case '001':
						$err_layout_tbl = $err_layout_tr = $err_layout_tc = 'div';
					
						break;
					case '005':
						$err_layout_tbl = 'table';
						$err_layout_tr = 'tr';
						$err_layout_tc = 'td';
				}
			?>

			<<?php
				echo $err_layout_tbl;
			?> class="dsp-tbl pos-rel w-100">
				<<?php
					echo $err_layout_tc;
				?> id="err-<?php
					echo $err;
				?>-ico" class="tpl-ie-ico">
				</<?php
					echo $err_layout_tc;
				?>><<?php
					echo $err_layout_tc;
				?> class="dsp-tc va-top">
					<h3 class="tpl-ie-ttl">
						<?php
							switch ($err) {
								case '001':
									echo 'Internet Explorer';
								
									break;
								case '005':
									echo 'This program';
							}
						?> cannot display the webpage
					</h3>
				
					<div class="tpl-ie-hr-cnr">
						<?php
							require(root.'shared/phps/hr.php');
						?>
					
					</div>
					<div class="tpl-ie-ctt-txt">
						<?php
							if ($err=='005') {
								echo '<p id="err-005-mlc-ttl">
									Most likely causes:
								</p>
							
								<ul id="err-005-mlc-lst">
									<li>
										<span>Your browser identifies itself as Maxthon 2.0.</span>
									</li>
									<li>
										<span>There might be a typing error in the address.</span>
									</li>
									<li>
										<span>The website is encountering problems.</span>
									</li>
								</ul>';
								echo "\n";	// special characters (the new line character in this case) can only be escaped inside double quotes
							}
						?>
						<p>
							What you can try:
						</p>
					
						<ul class="tpl-ie-wyt-lst no-style no-indent" id="err-<?php
							echo $err;
						?>-wyt-lst">
							<?php
								if ($err == '005') {
									echo '<li>
										Try the latest version of Maxthon. (Download the newest version of Maxthon from <a href="http://www.maxthon.com" target="_blank" title="Open the Maxthon 3 homepage." class="tpl-ie-dld">its homepage</a>)
									</li>';
									echo "\n";
								}
							?>
							<li<?php
								if ($err == '001') {
									echo ' class="fnt-wht"';
								}
							?>>
								<?php
									require root.'shared/dtc/rec.php';
									
									$recBrow = new dtcRecModule;
									
									if (isset($dtc_rec) && $dtc_rec != 'ie') {
										$recBrow->bc = $dtc_rec;
									}
									
									$recBrowInfo = $recBrow->getInfo();
								?>
								Use <?php
									echo $recBrowInfo['name'];
								?> instead. (Download <a href="<?php
									echo $recBrowInfo['href'];
								?>" target="_blank" title="<?php
									echo $recBrowInfo['ttl'];
								?>" class="tpl-ie-dld tgt-blank"><?php
									echo $recBrowInfo['name'];
								?></a>)
							</li>
							<li>
								<?php
									switch ($err) {
										case '001':
											echo '<button type="button" id="err-001-dcp-btn" class="vsb-hid">Diagnose Connection Problems</button>';
										
											break;
										case '005':
											echo 'Retype the address.';
									}
								?>
							
							</li>
							<?php
								if ($err == '005') {
									echo '<li>
										<span id="err-005-bak-lnk" class="blu-lnk clickable">Go back to the previous page.</span>
									</li>';
									echo "\n";
								}
							?>
						</ul>
					
						<<?php
							echo $err_layout_tbl;
						?> class="mor-inf-cnr dsp-tbl">
							<<?php
								echo $err_layout_tr;
							?> class="dsp-tr">
								<<?php
									echo $err_layout_tc;
								?> class="dsp-tc va-mid mor-inf-btn-cnr">
									<div class="mor-inf-btn">
										<div id="sh-btn" class="clickable">
										</div>
									</div>
								</<?php
									echo $err_layout_tc;
								?>><<?php
									echo $err_layout_tc;
								?> class="dsp-tc va-mid">
									<span id="sh-lnk" class="blu-lnk clickable">More information</span>
								</div>
							</<?php
								echo $err_layout_tr;
							?>>
							<<?php
								echo $err_layout_tr;
								
								if ($err == '005') {
									echo ' id="err-005-mic"';	//mic stands for mor-inf-ctt
								}
							?> class="dsp-tr">
								<<?php
									echo $err_layout_tc;
								?> class="dsp-tc">
								</<?php
									echo $err_layout_tc;
								?>><<?php
									echo $err_layout_tc;
								?> id="sh-ctt" class="dsp-non">
									<p>
										This problem can be caused by a variety of issues, including:
									</p>

									<ul>
										<li>
											<span>You are using <?php
												switch ($err) {
													case '001':
														echo 'Internet Explorer';
												
														break;
													case '005':
														echo 'Maxthon 2.0';
												}
											?>.</span>
										</li>
										<li>
											<span>You are not using <?php
												switch ($err) {
													case '001':
														echo 'Internet Explorer';
										
														break;
													case '005':
														echo 'Maxthon 2.0';
												}
											?>, but your browser <?php
												switch ($err) {
													case '001':
														echo 'is pretending to be Internet Explorer';
											
														break;
													case '005':
														echo 'has sent a header containing User-Agent info similar to Maxthon 2.0\'s';
												}
											?>.</span>
										</li>
										<li>
											<span>Shit happened.</span>
										</li>
									</ul>
									<?php
										if ($err == '001') {
											echo '<h4 id="err-001-olu">
												For offline users
											</h4>
							
											<p>
												Not for you, you are not offline.
											</p>';
											echo "\n";
										}
									?>
								</<?php
									echo $err_layout_tc;
								?>>
							</<?php
								echo $err_layout_tr;
							?>>
						</<?php
							echo $err_layout_tbl;
						?>>
					</div>
				</<?php
					echo $err_layout_tc;
				?>>
			</<?php
				echo $err_layout_tbl;
			?>>
			
			<h1 class="img-cnr tpl-ie-logo no-mrg-top no-mrg-btm">
				<a href="/" class="dsp-ib" title="Go to the homepage.">
					<img src="/shared/imgs/tsp.gif" alt="の弑す魂の PS" class="logo-sml" />
				</a>
			</h1>
		</div>
		<div class="tpl-ie-cr">
			<?php
				require root.'shared/footer/sml/footer.php';
			?>
			
		</div>
	</body>
</html>