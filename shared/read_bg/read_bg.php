<?php
	defined('root') or die;
	
	class readBgModule {
		public $outterTag = 'div';
		public $outterId;
		public $outterOtherClss;
		public $innerTag = 'div';
		public $innerId;
		public $innerOtherClss;
		public $cttWprId;
		public $cttWprClss;
		public $content;
		
		public function __toString() {
			$html = '<'.$this->outterTag.' class="read-bg-cnr';
			
			if ($this->outterOtherClss) {
				$html .= ' '.$this->outterOtherClss;
			}
			
			$html .='">						<!-- underground layer -->
				<div class="read-lgt">		<!-- light gradient + inner shadow -->
				</div>
				<div';
				
			if ($this->cttWprId) {
				$html .= ' id="'.$this->cttWprId.'"';
			}
			
			if ($this->cttWprClss) {
				$html .= ' class="'.$this->cttWprClss.'"';
			}
			
			$html .= '>						<!-- content + bg wpr -->
					<div class="read-bg">	<!-- bg, drop shadow, bg_ptn, z-index, smaller size, can not use 3d because it creates funny effects, can not create 3d space with container and psv-3d because it would disrupt z-index -->
					</div>
					<'.$this->innerTag.' class="read-cnr';
			
			if ($this->innerOtherClss) {
				$html .= ' '.$this->innerOtherClss;
			}
			
			$html .= '">	<!-- content cnr -->
						'.$this->content.'
					</'.$this->innerTag.'>
				</div>
			</'.$this->outterTag.'>';
			
			return $html; 
		}
		
		public function cttHtml() {
			$html = '<div class="read-bg">	<!-- bg, drop shadow, bg_ptn, z-index, smaller size, can not use 3d because it creates funny effects, can not create 3d space with container and psv-3d because it would disrupt z-index -->
			</div>
			<'.$this->innerTag.' class="read-cnr';
			
			if ($this->innerOtherClss) {
				$html .= ' '.$this->innerOtherClss;
			}
			
			$html .= '">	<!-- content cnr -->
				'.$this->content.'
			</'.$this->innerTag.'>';
				
			return $html;
		}
	}
?>