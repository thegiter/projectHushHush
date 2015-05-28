<?php
	defined('root') or die;
	
	class rotateShowModule {
		public $text1_tag = 'span';
		public $text2_tag = 'span';
		public $text1_attr;
		public $text2_attr;
		public $text1_abs = false;
		public $text2_abs = false;
		public $text1;
		public $text2;
		
		public function __toString() {
			$html = '<span class="rotate-show">
				<span class="rs-cnr">
					<span class="rs-front">
						<'.$this->text1_tag.' class="rs-1';
			
			if ($this->text1_abs) {
				$html .= ' rs-abs';
			}
			
			$html .= '"';
				
			if ($this->text1_attr) {
				$html .= ' '.$this->text1_attr;
			}
			
			$html .= '>
							'.$this->text1.'
						</'.$this->text1_tag.'>
						<'.$this->text2_tag.' class="rs-2';
			
			if ($this->text2_abs) {
				$html .= ' rs-abs';
			}
			
			$html .= '"';
					
			if ($this->text2_attr) {
				$html .= ' '.$this->text2_attr;
			}
			
			$html .= '>
							'.$this->text2.'
						</'.$this->text2_tag.'>
					</span>
				</span>
			</span>';
			
			return $html;
		}
	}
?>