<?php
	defined('root') or die;
	
	class ulSdwModule {
		public $id;
		public $paddingCls = 'ul-sdw-padding';
		public $otherClss;
		public $content;
		
		public function __toString() {
			$html = '<div ';
			
			if ($this->id) {
				$html .= 'id="'.$this->id.'" ';
			}

			$html .= 'class="ul-sdw '.$this->paddingCls;
			
			if ($this->otherClss) {
				$html .= ' '.$this->otherClss;
			}
			
			$html .= '">';
			
			if ($this->content) {
				$html .= '
					'.$this->content;
			}
			
			$html .= '
			</div>';
			
			return $html;
		}
	}
?>