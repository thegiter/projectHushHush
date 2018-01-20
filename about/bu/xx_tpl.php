<?php
	if (!defined('root')) {
		define('root', '../');
	}

	require_once root.'shared/phps/dtcs/ied.php';
	//require(root.'shared/phps/dtcs/ffd.php');
	require_once root.'shared/phps/dtcs/jsd.php';	//put brs dtcs before this
	//require(root.'shared/phps/dtcs/fpd.php');
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
	
	$xx = $_GET['xx'];
	
	function get_plainTtl($xx) {
		switch ($xx) {
			case 'shps':
				echo 'の弑す魂の PS';
				break;
			case 'sh':
				echo 'The Owner';
		}
	};
	
	function get_styleTtl($xx) {
		switch ($xx) {
			case 'shps':
				echo '<span class="kaiti" xml:lang="zh">の弑す魂の</span> PS';
				break;
			case 'sh':
				echo 'The Owner';
		}
	}
?>


		<meta name="description" content="The about <?php
			get_plainTtl($xx);
		?> page.<?php
			if ($xx == 'shps') {
				echo ' Also containing the site\'s terms and policies.';
			}
		?> Currently in its beta." />
		<meta name="keywords" content="弑魂,PS,About<?php
			if ($xx == 'shps') {
				echo ',terms,policies,copyright';
			}
		?>" />
		<meta name="robots" content="index,follow" />
		
		<script src="/shared/jss/common.js" type="text/javascript">
		</script>
		<script type="text/javascript" src="/shared/jss/bsc_twn.js">
		</script>
		<script type="text/javascript" src="/shared/jss/bc.js">
		</script>
		<script type="text/javascript" src="/about/jss/xx_tpl.js">
		</script>
		<?php
			if ($xx == 'shps') {
				echo '<script type="text/javascript" src="/about/jss/shps.js">
				</script>
				';
			}
		?>

		<script src="/shared/jss/anim.js" type="text/javascript">
		</script>
		<script src="/shared/jss/beta.js" type="text/javascript">			<!-- these jss are order-sensitive -->
		</script>
		<script type="text/javascript" src="/shared/jss/gaa.js">			<!-- must be last -->
		</script>
		
		<link rel="stylesheet" type="text/css" href="/about/csss/xx_tpl.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo_bg_1.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/bc_init_hid.css" charset="urf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/bc.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/about/csss/<?php
			echo $xx;
		?>.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/hr.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/footer/sml/csss/footer.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/beta/init_stg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/beta/beta.css" charset="utf-8" />

		<title>About <?php
			get_plainTtl($xx);
		?> - の弑す魂の PS</title>
	</head>
	
	<body>
		<div id="xx-tpl-bd-wpr" class="bd-wpr dsp-tbl w-100 h-100">
			<div class="dsp-tr">
				<div id="xx-tpl-hd" class="dsp-tc">
					<div class="z-idx--99 pos-abs w-100 h-100">
						<?php
							require root.'shared/phps/logo_bg_1.php';
						?>
						
					</div>
					<div class="xx-tpl-ctt-cnr">
						<?php
							require root.'shared/phps/logo.php';

							echo "\n\t\t\t";

							require root.'shared/phps/bc.php';
						?>
						
					</div>
				</div>
			</div>
			<div class="xx-tpl-bnr-bg dsp-tr va-mid">							<!-- puts the row in the middle of the table -->
				<div id="xx-tpl-bd-cnr" class="xx-tpl-bnr-pic vsb-hid pos-rel">
					<div class="pos-rel w-100 h-100">
						<div id="xx-tpl-bnr-sdw-top">
						</div>
						<div class="xx-tpl-bnr-gloss">
						</div>
						<div class="xx-tpl-bnr-bvl top-0">
						</div>
						<div class="xx-tpl-bnr-bvl btm-0">
						</div>
						<div class="dsp-tbl pos-abs w-100 h-100">										<!-- this table is needed for vertical alignment -->
							<div id="xx-tpl-main-ctt-wpr" class="dsp-tc w-100 h-100 ta-ctr va-mid">
								<div id="xx-tpl-main-ctt-cnr" class="pos-rel vsb-hid xx-tpl-ctt-cnr">
									<div id="xx-tpl-main-ctt-cnr-tl" class="pos-abs">
									</div>
									<div id="xx-tpl-main-ctt-cnr-top" class="pos-abs">
									</div>
									<div id="xx-tpl-main-ctt-cnr-tr" class="pos-abs">
									</div>
									<div id="xx-tpl-main-ctt-cnr-lft" class="pos-abs">
									</div>
									<div id="xx-tpl-avatar">
									</div><?php
										if ($xx == 'sh') {
											echo '<div id="xx-tpl-main-ctt-hr" class="dsp-non">
												<div id="xx-tpl-main-ctt-hr-hd">
												</div>
												<div id="xx-tpl-main-ctt-hr-ass">
												</div>
											</div><div id="sh-main-ctt-cnr">
											';
										}
									?><h2 id="xx-tpl-h2">
										About <?php
											get_styleTtl($xx);
										?>
									</h2>
								
									<?php
										switch ($xx) {
											case 'shps':
												echo '<div id="xx-tpl-main-ctt-hr" class="vsb-hid pos-rel clr-dbl-aft">
													<div id="xx-tpl-main-ctt-hr-hd">
													</div>
													<div id="xx-tpl-main-ctt-hr-ass">
													</div>
												</div>
											
												<p>
													The site development started sometime in 2009. As a hobby, I coded the site pages letter by letter following tutorials on the web. This is how this site was founded. It is now designed and developed solely by myself and comprises of XHTML, CSS, PHP, JavaScript and incorporates WordPress, and Joomla!.
												</p>
												<p>
													Now, I am hoping this site can be a place online where I hold my portfolio as I attempt to become a professional web developer.
												</p>';
										
												break;
											case 'sh':			//should put these descriptions seperately for easy editing as they would need to be updated as the site developes
												echo '<ul class="no-style">
													<li>Name:	Desmond</li>
													Gender:	male<br />
													Age:	'.strval(date('Y')-1989).'
												</ul>
												<p>
													Hi, I am an aspiring web developer with very basic design and Photoshop knowledge. I am the founder, owner, developer, and probable future maintainer of <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS. 
												</p>
											</div>';
										}
									?>
								
									<div id="xx-tpl-main-ctt-cnr-rgt" class="pos-abs">
									</div>
									<div id="xx-tpl-main-ctt-cnr-bl" class="pos-abs">
									</div>
									<div id="xx-tpl-main-ctt-cnr-btm" class="pos-abs">
									</div>
									<div id="xx-tpl-main-ctt-cnr-br" class="pos-abs">
									</div>
									<?php
										if ($xx == 'shps') {
											echo '<div id="xx-tpl-tap-anim-blk-cvr" class="dsp-non">
											</div>
											';
										}
									?>
								</div>
								<div id="xx-tpl-menu-pnl-wpr">
									<div id="xx-tpl-menu-pnl">
										<ul id="xx-tpl-menu-cnr" class="xx-tpl-ctt-cnr no-style no-indent">
											<?php
												require_once root.'shared/mis/mis.php';
												
												$menu_html = '';								//clear variable $menu_html in case another menu was produced and $menu_html is not empty
												
												foreach ($mis as $mi => $mi_ppt) {																//ppt: properties, not the microsoft shit
													$menu_html .= '<li><a href="'.$mi_ppt['href'].'">'.$mi_ppt['name'].'</a></li>';
												}
						
												echo $menu_html;
											?>

										</ul>
									</div>
								</div>
							</div>
						</div>
						<div id="xx-tpl-bnr-sdw-btm">
						</div>
						<?php
							if ($xx == 'shps') {
								echo '<div id="shps-tap-ctt-cnr" class="dsp-non">
									Loading...
								</div>
								<div id="xx-tpl-ctt-switcher-cnr" class="fade-in-norm dsp-non opa-0">
									<div class="xx-tpl-ctt-switcher-menu-bvl-lft">
									</div>
									<div class="xx-tpl-ctt-switcher-menu-bvl-btm">
									</div>
									<div class="xx-tpl-ctt-switcher-blk-cvr">
									</div>
									<div id="xx-tpl-ctt-switch-on-anim-cnr" class="dsp-non">
										<div id="xx-tpl-ctt-switch-on-anim">
										</div>
										<div id="xx-tpl-ctt-switch-on-anim-bg">
											<div class="xx-tpl-ctt-switcher-menu-bvl-lft">
											</div>
											<div class="xx-tpl-ctt-switcher-menu-bvl-btm">
											</div>
											<div class="xx-tpl-ctt-switcher-blk-cvr">
											</div>
										</div>
									</div>
									<a href="#terms of use" id="xx-tpl-ctt-switch-on">
									</a>
									<a href="#" id="xx-tpl-ctt-switch-off" class="xx-tpl-ctt-cnr dsp-non">
									</a>
									<ul id="xx-tpl-ctt-switcher-tabs-cnr" class="no-style no-indent">
										';

								$xx_tpl_tabs = array('Terms of Use', 'Privacy Policy', 'Copyright');
								
								foreach ($xx_tpl_tabs as $key => $ti) {
									echo '<li class="pos-rel vsb-hid';
										
									if ($key === 0) {
										echo ' shps-ctt-switcher-tab-active';
									}
									else {
										echo ' shps-ctt-switcher-tab-inactive';
									}
									
									echo '">
										<div class="xx-tpl-ctt-switcher-bg">';
									
									if ($key === 0) {
										echo '
											<div class="xx-tpl-ctt-switcher-tab-rgt">
												<div class="xx-tpl-ctt-switcher-tab-rgt-blk-cvr">
												</div>
											</div>';
									}
									
									echo '
											<div class="xx-tpl-ctt-switcher-menu-bvl-btm">
											</div>
											<div class="xx-tpl-ctt-switcher-blk-cvr">
											</div>
										</div>
										<a href="#'.strtolower($ti).'" class="xx-tpl-ctt-cnr">'.$ti.'</a>
									</li>';
								}
									
								echo '
										<div id="xx-tpl-ctt-switcher-tab-lft">
											<div id="xx-tpl-ctt-switcher-tab-lft-blk-cvr">
											</div>
										</div>
										<div id="xx-tpl-ctt-switcher-tab-rgt" class="xx-tpl-ctt-switcher-tab-rgt">
											<div class="xx-tpl-ctt-switcher-tab-rgt-blk-cvr">
											</div>
										</div>
									</ul>
								</div>
								';
							}
						?>
					</div>
				</div>
			</div>
			<div class="dsp-tr">
				<div id="xx-tpl-ass-cnr">
					<div id="xx-tpl-big-lgt-top-lft" class="xx-tpl-big-lgts">
					</div>
					<div id="xx-tpl-big-lgt-top-rgt" class="xx-tpl-big-lgts">
					</div>
					<div class="xx-tpl-space-balancer pos-rel">
						<?php
							require root.'shared/phps/logo.php';

							echo "\n\t\t\t";

							require root.'shared/phps/bc.php';
						?>

					</div>
					<div id="xx-tpl-big-lgt-btm-cnr">
						<div id="xx-tpl-big-lgt-btm" class="xx-tpl-big-lgts">
						</div>
					</div>
					<div id="xx-tpl-fs-cnr" class="xx-tpl-ctt-cnr">
						<?php
							if ($xx == 'shps') {
								echo '<a id="shps-cr-cnr" class="cr-lnk" href="#copyright">Copyright &#169; 2009-2109 <span class="kaiti cr-si" xml:lang="zh">の弑す魂の</span> PS. All rights reserved.</a> | <ul class="dsp-inl fs-ul no-style no-indent"><li><a>Contact Me</a></li> - <li><a id="shps-fs-btn-terms" href="#terms of use">Terms</a></li> - <li><a id="shps-fs-btn-pvc" href="#privacy policy">Privacy</a></li> - <li><a>Help</a></li></ul>';
							}
							else {
								require root.'shared/footer/sml/footer.php';
							}
						?>
						
					</div>
				</div>
			</div>
		</div>
		<div id="xx-tpl-blk-cvr-cnr" class="dsp-tbl w-100 h-100">
			<div class="dsp-tr">
				<div class="dsp-tc w-100 xx-tpl-hd-blk-cvr">
					<div class="xx-tpl-space-balancer">
						<?php
							require root.'shared/phps/logo.php';

							echo "\n\t\t\t";

							require root.'shared/phps/bc.php';
						?>
						
					</div>
				</div>
			</div>
			<div class="dsp-tr va-mid">
				<div id="xx-tpl-bd-blk-cvr" class="dsp-tc w-100">
				</div>
			</div>
			<div class="dsp-tr">
				<div class="dsp-tc xx-tpl-hd-blk-cvr w-100 ta-ctr">
					<div class="xx-tpl-space-balancer">
						<?php
							require root.'shared/phps/logo.php';

							echo "\n\t\t\t";

							require root.'shared/phps/bc.php';
						?>
						
					</div>
				</div>
			</div>
		</div>
		
		<?php
			require root.'shared/phps/beta.php';
		?>

	</body>
</html>