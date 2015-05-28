<?php
	defined('root') or die;
	
	class toTopModule {
		public $cnr_otherClss;
		public $btn_id;
		
		public function __toString() {
			$html = '<div class="to-top-cnr';
			
			if ($this->cnr_otherClss) {
				$html .= ' ' . $this->cnr_otherClss;
			}
			
			$html .= '">
				<div class="sdw-img-wpr">
					<div class="sdw-img dsp-non mblur-opa">
					</div>
				</div>
				<div';

			if ($this->btn_id) {
				$html .= ' id="'.$this->btn_id.'"';
			}
			
			$html .= ' class="to-top clickable">
				</div>
			</div>';
			
			return $html;
		}
	}
?>