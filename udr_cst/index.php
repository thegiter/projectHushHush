<?php
	if (!defined('root')) {
		define('root', '../');
	}
	if (!defined('da')) {		// special allowance for uc, because it will be access by mod rewrite
		define('da', false);
	}

	require_once(root.'shared/phps/dtcs/ied.php');
	//require_once(root.'shared/phps/dtcs/ffd.php');
	require_once(root.'shared/phps/dtcs/jsd.php');	//put brs dtcs before this
	//require(root.'shared/phps/dtcs/fpd.php');
	require(root.'shared/phps/dtdec_x.php');
	
	echo "\n";
	
	require(root.'shared/phps/heads.php');
?>


		<meta name="description" content="弑魂's personal site. Currently under construction." />
		<meta name="keywords" content="弑魂,PS,Personal Site,under construction" />
		<meta name="robots" content="noindex,follow" />

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript">
		</script>
		<script src="/shared/jss/common.js" type="text/javascript">
		</script>
		<script type="text/javascript" src="/shared/jss/bsc_twn.js">
		</script>
		<script src="/shared/jss/anim.js" type="text/javascript">		<!-- anim is required for ldg.js -->
		</script>
		<script src="/shared/jss/ldg.js" type="text/javascript">
		</script>
		<script src="/udr_cst/jss/uc.js" type="text/javascript">
		</script>
		<script type="text/javascript" src="/shared/jss/gaa.js">
		</script>

		<link rel="stylesheet" type="text/css" href="/udr_cst/csss/uc.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/ldg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/cnr_bg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo_sml.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/cr.css" charset="utf-8" />

		<title>Under Construction - の弑す魂の PS</title>
	</head>
	<body>
		<?php
			require(root.'shared/phps/ldg.php');
		?>
		
		<div id="cnr" class="bd-wpr dsp-non">
			<div id="right">					<!-- part of bg, load first -->
			</div>
			<div id="left">
				<?php
					require(root.'shared/phps/cnr_bg.php');
				?>
				
				<div id="uc-note">
					<div id="note-txt-wpr" class="z-idx-99">
						<div id="note-hdr">
							<?php
								require(root.'shared/phps/logo_sml.php');
							?>
						
							<p class="no-mrg-top no-mrg-btm fnt-wht kaiti fnt-18" id="note-stt" xml:lang="zh">
								不在放荡中变坏 就在沉默中变态
							</p>
						</div>
						
						<p id="note-p1" class="fnt-18 arial shadowed">
							The site's launch date is undetermined. Chances are, the site never gets launched.
						</p>
						<p id="note-p2" class="fnt-18 arial shadowed">
							If you'd like to recieve an email notification when my site launches, subscribe your email below.
						</p>
				
						<form method="post" action="" class="fnt-wht verdana" id="sub-frm">
							<span class="va-mid">Your Email: </span><input type="text" maxlength="256" name="email" class="ipt-txt va-mid" id="ipt-ure"/><div class="tick va-mid" id="val-tick"></div><br/>		<!-- use email as the name for consistency with other websites -->
							<input type="submit" value="Subscribe" class="dsp-non"/>
						
							<object class="uc-btn" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10.0.42.34">
								<param name="movie" value="/udr_cst/flss/uc_btn.swf" />
								<param name="swliveconnect" value="true" />
								<param name="menu" value="false" />
								<param name="quality" value="best" />
								<param name="wmode" value="transparent" />
							
								<embed src="/udr_cst/flss/uc_btn.swf" class="uc-btn" swliveconnect="true" menu="false" quality="best" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
								</embed>
							</object>
						
							<div class="flt-clr">
							</div>
						</form>
						
						<p id="sub-msg" class="bold">
						</p>
						
						<div id="uc-cr">
							<?php
								require(root.'shared/phps/cr.php');
							?>
							</div>
					</div>
				</div>
			</div>
			<div id="ctr-cnr" class="ctr-mrg z-idx-98">
				<div id="site-ico-ver-ali">
					<div id="site-ico" class="ctr-mrg">
					</div>
					
					<h2 id="ttl" class="arial no-mrg-top">
						UNDER <span class="yellow">CONSTRUCTION</span>
					</h2>
					
					<p class="fnt-wht ctr-mrg arial fnt-24" id="words">
						Yo, the site ahead is currently under construction.<br />
						<span class="yellow">Detour!</span>
					</p>
				</div>
			</div>
		</div>
		<div id="msg-cnr" class="topmost vsb-hid">
			<div id="msg-ctt" class="tahoma">
				<div class="bold fnt-red">
					Error
				</div>
				
				<span id="msg"></span>
			</div>
		</div>
	</body>
</html>