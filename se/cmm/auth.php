<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	//auth pass
	if (isset($_POST['pw']) && $_POST['pw'] == 'sayuneijibei') {
		setcookie('fakljkd', 'oxkecl23', time() + (365 * 24 * 60 * 60), '../');//expire in 1 year
		
		die;
	}
	
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>

		<title>Authenticator</title>
	</head>
	
	<body>
		<form name="login" action="auth.php" method="post" accept-charset="utf-8">
			<ul>
				<li>
					<label for="pw">Password</label><input type="password" name="pw" id="pw" placeholder="Password" required="required"/>
				</li>
				<li>
					<button type="submit">Authenticate</button>
				</li>
			</ul>
		</form>
	</body>
</html>