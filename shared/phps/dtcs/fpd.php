<?php
	// Search through the HTTP_ACCEPT header for the Flash Player mime-type. only work for ie. 11 dec 2009
	//if(strpos($_SERVER['HTTP_ACCEPT'], 'application/x-shockwave-flash') == false) {
	if (isset($_COOKIE['lseikl'])&&$_COOKIE['lseikl']=='oxkecl23') {
	}
	else {
		$err = '004';

		require($root.'shared/phps/dtdec_h.php');

		echo "\n";

		require($root.'shared/phps/heads.php');

		echo "\n\n";
		
		//require($root.'shared/errs/tpl.php');
		
		//die();
	}
?>

	</head>
	
	<body>
		<?php
			require($root.'shared/phps/dtcs/fpd_fls.php');
		?>
		
		<script type="text/javascript" src="/shared/jss/fpd.js" charset="utf-8">
		</script>
	</body>
</html>
<?php
	die();
?>