<?php
	defined('root') or die;
	
	class omcModule {
		public $tag = 'div';
		public $id;
		public $clss;
		public $panes = array();
		
		public function appendPane($ctt) {
			array_push($this->panes, '<div>
				<div class="pane-bd pane-bd-trans closed of-hid dsp-non">
					'.$ctt.'
				</div>
				<div class="cvr dsp-non">
				</div>
			</div>');
		}
		
		public function __toString() {
			$html = '<'.$this->tag;
			
			if ($this->id) {
				$html .= ' id="'.$this->id.'"';
			}
			
			$html .= ' class="omc';
			
			if ($this->clss) {
				$html .= ' '.$this->clss;
			}
			
			$html .= '">
				<div>';
			
			
			foreach($this->panes as $pane) {
				$html .= '
					'.$pane;
			}
			
			$html .= '
				</div>
			</'.$this->tag.'>';
			
			return $html;
		}
	}
?>