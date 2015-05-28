<?php
	defined('root') or die;
	
	class dtcRecModule {
		public $bc = 'ff';
		
		public function getInfo() {	//default global recommended browser is set here
			$rec = array(
				'ff' => array(
					'name' => 'Firefox',
					'href' => 'https://www.mozilla.org/en-US/firefox/new/',
					'ttl' => 'Open the Firefox download page.'
				),
				'gc' => array(
					'name' => 'Google Chrome',
					'href' => 'http://www.google.com/chrome/eula.html',
					'ttl' => 'Open the Google Chrome EULA page.'
				)
			);
			
			$isValid = false;
			
			foreach ($rec as $key => $value) {
				if ($this->bc == $key) {
					$isValid = true;
					
					break;
				}
			}
			
			if (!$isValid) {
				$this->bc = 'ff';
			}
			
			$info = array(
				'name' => $rec[$this->bc]['name'],
				'href' => $rec[$this->bc]['href'],
				'ttl' => $rec[$this->bc]['ttl']
			);
			
			return $info;
		}
	}
?>