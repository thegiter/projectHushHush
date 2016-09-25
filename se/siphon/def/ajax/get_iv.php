<?php
	if ($_GET['pw'] == 'shpssepw1') {
		//set sessions
		session_start();
		
		$_SESSION['authed'] = true;
		$_SESSION['tkr'] = $_GET['tkr'];
		
		//redirect
		header("Location: siphon.php");
	}
?>