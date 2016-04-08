<?php
	if (!defined('root')) {
		die;
	}
	
	class lv2FtdModule {
		public $ttl = 'Featured';
		public $cnr_id;
		public $caption = false;
		
		public function __toString() {
			$html = '<div class="ftd-wpr">
				<div id="'.$this->cnr_id.'" class="ftd-cnr fade-in-norm opa-0"> <!-- ftd stands for featured -->
					<div class="ftd-ctt-cnr">
						<div class="dsp-tr">
						</div>
					</div>

					<div class="ftd-ttl-bg-bd">
						<div class="ftd-ttl-bg-ass">
						</div>
						
						<h3 class="ftd-ttl">
							'.$this->ttl.'
						</h3>
					</div>';

			if ($this->caption) {
				$caption_html = 'These mordern art blocks are dynamically generated. It is different every time you initiate the page. Refresh the page to see what I mean.';
						
				$html .= '<div class="ftd-caption-bg-bd">
					<div class="ftd-caption-bg-hd">
					</div>
					
					'.$caption_html.'
				</div>';
			}

			$html .= '<div class="ftd-bg-side-ptn-top">
			</div>
			<div class="ftd-bg-side-ptn-btm">
			</div>
		</div><!-- #featured container -->
	</div>';
	
			return $html;
		}
	}
?>