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
					
					<div class="ftd-ttl-bg-hd-cnr">
						<div class="ftd-ttl-bg-hd">
						</div>
					</div>
					<div class="ftd-ttl-bg-bd-cnr">
						<div class="ftd-ttl-bg-bd-pstnr"> <!-- pstnr stands for positioner -->
							<div class="ftd-ttl-bg-bd">
								<h3 class="ftd-ttl">
									'.$this->ttl.'
								</h3>
							</div><div class="ftd-ttl-bg-ass">
							</div>
						</div>
					</div>';

			if ($this->caption) {
				$caption_html = 'Halle Berry is hot (Place holder caption)';
						
				$html .= '<div class="ftd-caption-bg-cnr">
					<div class="ftd-caption-bg-bd">
						<div class="ftd-caption-bg-hd">
						</div>
						
						'.$caption_html.'
					</div>
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