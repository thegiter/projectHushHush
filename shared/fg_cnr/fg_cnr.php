<?php
	defined('root') or die;
	
	class fgCnrModule {
		public $tag = 'div';
		public $id;
		public $posClass = 'fgcnr-pos';
		public $paddingClass = 'fgcnr-padding';
		public $otherClasses;
		public $content;
		
		public function __toString() {
			$html = '<'.$this->tag;
			
			if ($this->id) {
				$html .= ' id="'.$this->id.'"';
			}
			
			$html .= ' class="'.$this->paddingClass;
			
			if ($this->posClass) {
				$html .= ' '.$this->posClass;
			}
			
			if ($this->otherClasses) {
				$html .= ' '.$this->otherClasses;
			}
			
			$html .='">
				<div class="fgcnr-tl">
				</div>
				<div class="fgcnr-top">
				</div>
				<div class="fgcnr-tr">
				</div>
				<div class="fgcnr-lft">
				</div>
				<div class="fgcnr-ctr">
				</div>
				<div class="fgcnr-rgt">
				</div>
				<div class="fgcnr-bl">
				</div>
				<div class="fgcnr-btm">
				</div>
				<div class="fgcnr-br">
				</div>';
			
			if ($this->content) {
				$html .= '
				'.$this->content;
			}

			$html .= '
			</'.$this->tag.'>';
			
			return $html;
		}
	}
?>