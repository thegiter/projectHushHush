<?php
	defined('root') or die;
	
	class hexGridModule {
		public $rows = 0;
		public $cols = 0;
		public $type = 'h';
		
		public function __toString() {
			$html = '<div class="hex-grid">';
			
			switch ($this->type) {
				case 'h':
					for ($r = 0; $r < $this->rows; $r++) {
						$html .= '<div class="hex-r">';
						
						for ($c = 0; $c < $this->cols; $c++) {
							$html .= '<div class="hex-h hex-wdt-pusher">
								<div class="hex-hgt-pusher">
									<div class="hex">
									</div>
								</div>
							</div>';
						}
						
						$html .= '</div>';
					}
					
					break;
				case 'v':
					for ($c = 0; $c < $this->cols; $c++) {
						$html .= '<div class="hex-c">';
						
						for ($r = 0; $r < $this->rows; $r++) {
							$html .= '<div class="hex hex-v">
								<div>
								</div>
							</div>';
						}
						
						$html .= '</div>';
					}
					
					break;
				default:
			}
			
			$html .= '</div>';
			
			return $html;
		}
	}
?>