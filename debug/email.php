<?php
	if (mail('297154048@outlook.com', 'Email Test', 'This email tests if email is working on the server')) {
		echo 'email accepted';
	} else {
		echo 'email not accepted, email failed';
	};
?>