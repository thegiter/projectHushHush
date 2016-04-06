<?php
	if (!defined('root')) {
		die;
	}
	
	class ftrInfoLnksModule {
		//static var can not be function returned value, another variable or object
		//which requires run time
		//objects are instantiated from class, which requires run time
		//thus using array
		public static $lnks = [
			'contact' => [
				'text' => 'Contact Me'
			],
			'terms' => [
				'text' => 'Terms',
				'url' => '#!about/#の弑す魂の_ps/#terms of use/',
				'title' => 'Go to the terms of use page.'
			],
			'policies' => [
				'text' => 'Policies',
				'url' => '#!about/#の弑す魂の_ps/#privacy policy/',
				'title' => 'Go to the privacy policy page.'
			],
			'help' => [
				'text' => 'Help'
			]
		];
		public $clss;
		public $id;
		public $dlm;
		public $disabled;
		
		//disabled is an array containing the names of the disabled links
		//see above for the name of each link
		//dlm is delimiter between the links
		public function __toString() {
			$html = '<ul ';
			
			if ($this->id) {
				$html .= 'id="'.$this->id.'" ';
			}
			
			$html .= 'class="ftr-info-lnks no-style no-indent '.$this->clss.'">
				';
			
			$first = true;
			
			foreach (ftrInfoLnksModule::$lnks as $name => $lnkArr) {
				if ($first) {
					$first = false;
				} else if ($this->dlm) {
					$html .= $dlm;
				}
				
				$html .= '<li><a';
				
				if (isset($lnkArr['url'])) {
					$html .= ' href="'.$lnkArr['url'].'"';
				}
				
				if (isset($lnkArr['title'])) {
					$html .= ' title="'.$lnkArr['title'].'"';
				}
				
				if ($this->disabled && isset($this->disabled[$name])) {
					$html .= ' class="disabled"';
				}
				
				$html .= '>'.$lnkArr['text'].'</a></li>';
			}
			
			$html .= '
			</ul>';
			
			return $html;
		}
	}
?>