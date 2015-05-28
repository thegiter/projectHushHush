<?php
	//refuri uses window.location.hostname
	if (!isset($_POST['refuri']) || ($_POST['refuri'] != $_SERVER['SERVER_NAME'])) {
		die;
	}
?>