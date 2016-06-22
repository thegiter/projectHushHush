<?php
	if (!defined('root')) {
		define('root', '../../');
	}
?>		<meta name="robots" content="noindex,follow" />
		<meta name="google-site-verification" content="WiIzb9FaspCvU6Lxe-COLQr1_LSfwfWQOpkc5meOHNc" />
		
		<?php
			if ($err == '403') {
				echo '<script type="text/javascript" src="/shared/jss/common.js">
				</script>
				<script type="text/javascript" src="/shared/errs/jss/403.js">
				</script>';
			}
		?>
		
		<script type="text/javascript" src="/shared/jss/gaa.js">
		</script>

		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/<?php
			echo $err;
		?>.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/errs/csss/cmn_dft.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/csss/hr.css"/>
		<link rel="stylesheet" type="text/css" href="/shared/footer/sml/csss/footer.css"/>
		
		<title><?php
			echo $err; 
		?> Error - の弑す魂の PS</title>
	</head>
	
	<body>
		<?php
			if ($err == '004') {
				require_once(root.'shared/phps/dtcs/fpd_fls.php');
			
				echo '\n';
			}
		?>
		<div class="bd-wpr shared-css-bg-basic">								<!-- required for google chrome to show bd bg properly -->
			<?php
				require(root."shared/phps/logo.php");
			?>
			
			<div class="tpl-dft-bnr">
				<span id="err-<?php																	// use span for maxthon consistency, again, maxthon sucks, why would anyone even want to use it, really
						echo $err;
					?>-ico" class="tpl-dft-ico"></span><h3 class="tpl-dft-ttl tahoma unbold"><?php
						switch ($err) {
							case '002':
								echo 'Javascript Disabled';
				
								break;
							case '004':
								echo 'Flash Player Missing';
								
								break;
							case '403':
								echo 'Forbidden';
						}
					?></h3>
			</div>
			<div class="tpl-dft-ctt">
				<div class="tpl-dft-ctt-txt verdana">					<!-- if this div is still not needed when the page structure is settled, it should be removed -->
					<p>
						Thank you for trying to browse<?php
							if ($err == '403') {
								echo ' (or fuck you for trying to hack)';
							}
						?> my site.
					</p>
					<p>
						<?php
							switch ($err) {
								case '002':
									echo 'Unfortunately, this website does not support Javasript-disabled users, and it has been detected that your browser has disabled/blocked Javascript. (Please turn on your browser\'s Javascript and <a href="" title="Reload the page." class="refresh">try again</a>.)
									</p>';
						
									break;
								case '004':
									echo 'Unfortunately, the page you were trying to view contains flash contents which requires a flash player, and it has been detected that you either don\'t have a flash palyer installed or your falsh palyers is way out of date. (Please install/update the flash player and <a href="" title="Reload the page." class="refresh">try again</a>.)
									</p>
							
									<ul class="tpl-dft-lst">
										<li>
											Download <a class="err-dld" title="Download the lastest version of Adobe Flash Player for your browser and operating system. (Go to the download page of Adobe Flash Player\'s official site.)" href="http://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player</a>
										</li>
									</ul>';
									
									break;
								case '403':
									echo 'Unfortunately (or fortunately), you failed.
									</p>
									<p class="tpl-dft-ie">
										<span class="tpl-dft-ie-ttl">If you are not trying to hack my site</span>
										
										<ul>
											<li>
												No, you can not access this URL ('.$_SERVER['REQUEST_URI'].').
											</li>
											<li>
												No, not even if you say "Please".
											</li>
											<li>
												Well, you can go back to <span id="err-403-bak-btn" class="pp lnk clickable">the previous page</span> (if there is one).
											</li>

											or

											<li>
												Visit <a href="/" title="Go to the homepage of の弑す魂の PS." class="lnk">my homepage</a>.
											</li>
										</ul>
										
										<span class="tpl-dft-ie-ttl">Else</span>
										
										<ul>
											<li>
												You suck.
											</li>
											<li>
												Time to try a new job.
											</li>
										</ul>
									</p>';
							}
						?>
		
				</div>
			</div>
			<div class="pos-rel">
				<?php
					require root.'shared/phps/hr.php';
				?>
		
			</div>
			<div class="tpl-dft-cr ta-ctr">
				<?php
					require root.'shared/modules/ftr/sml/footer.php';
				?>
			
			</div>
		</div>
	</body>
</html>