<?php
	if (!defined('root')) {
		die;
	}
	
	class tdCubeModule {
		public $depth;
		public $front = false;
		public $back = false;
		public $top = false;
		public $btm = false;
		public $lft = false;
		public $rgt = false;
		public $clss;
		public $cnr_tag = 'div';
		public $cnr_attr;
		public $front_ctt;
		public $back_ctt;
		public $top_ctt;
		public $btm_ctt;
		public $lft_ctt;
		public $rgt_ctt;
		
		public function __toString() {
			$html = '<'.$this->cnr_tag.' class="td-cube '.$this->clss.'"';
			
			if ($this->cnr_attr) {
				$html .= ' '.$this->cnr_attr;
			}
			
			$html .= '>
				<div class="depth-pusher">';
				
			if ($this->front) {
				$html .= '
					<div class="front" style="left:calc('.$this->depth.' / 2)">';
					
				if ($this->front_ctt) {
					$html .= '
					'.$this->front_ctt;
				}
				
				$html .= '
					</div>';
			}
			
			if ($this->back) {
				$html .= '
					<div class="back" style="right:calc('.$this->depth.' / 2)">';
				
				if ($this->back_ctt) {
					$html .= '
					'.$this->back_ctt;
				}
				
				$html .= '
					</div>';
			}
			
			$html .= '</div>';//end depth pusher
			
			if ($this->lft) {
				$html .= '
				<div class="lft" style="width:'.$this->depth.'">';
				
				if ($this->lft_ctt) {
					$html .= '
					'.$this->lft_ctt;
				}
				
				$html .= '
				</div>';
			}
			
			if ($this->rgt) {
				$html .= '
				<div class="rgt" style="width:'.$this->depth.'">';
				
				if ($this->rgt_ctt) {
					$html .= '
					'.$this->rgt_ctt;
				}
				
				$html .= '
				</div>';
			}
			
			if ($this->top) {
				$html .= '
				<div class="top" style="padding-bottom:'.$this->depth.'">';
				
				if ($this->top_ctt) {
					$html .= '
					'.$this->top_ctt;
				}
				
				$html .= '
				</div>';
			}
			
			if ($this->btm) {
				$html .= '
				<div class="btm" style="padding-bottom:'.$this->depth.'">';
				
				if ($this->btm_ctt) {
					$html .= '
					'.$this->btm_ctt;
				}
				
				$html .= '
				</div>';
			}
			
			$html .= '</'.$this->cnr_tag.'>';//end td-cube
			
			return $html;
		}
	}
?>