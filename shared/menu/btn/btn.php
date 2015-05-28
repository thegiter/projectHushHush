<?php
	defined('root') or die;
	
	class menuBtnModule {
		public $tag = 'button';
		public $id;
		public $clss;
		
		public function __toString() {
			$html = '<'.$this->tag;
		
			if ($this->id) {
				$html .= ' id="'.$this->id.'"';
			}
			
			$html .= ' class="menu-btn menu-open-btn';

			if ($this->clss) {
				$html .= ' '.$this->clss;
			}
			
			$html .= '"';
			
			if ($this->tag == 'button') {
				$html .= ' type="button"';
			}
			
			$html .= '>
				<div>
				</div>
				<div>
				</div>
				<div>
				</div>
			</'.$this->tag.'>';
				
			return $html;
		}
	}
?>