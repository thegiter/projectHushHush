<?php
	if (!defined('root')) {
		die;
	}
	
	class tidyModule {
		public $config = [
			'indent'         => true,
			'output-xhtml'   => true,
			'wrap'           => 0
		];
		
		public static function xhtml($xhtml) {
			// Specify configuration
			$config = ;

			// Tidy
			$tidy = new tidy;
			$tidy->parseString($xhtml, $config, 'utf8');
			$tidy->cleanRepair();

			return $tidy;
		}
	}
?>