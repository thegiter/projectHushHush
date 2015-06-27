<?php
	defined('root') or die;
	
	//1 day max age for front end html
	//it allows the client 1 day of using old data and not update
	header('Cache-Control: max-age=86400');
?>