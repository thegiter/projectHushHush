<?php
	if (!defined('root')) {
		die;
	}

	class lv2FtdModule {
		public $ttl = 'Featured';
		public $cnr_id;
		public $caption = false;

		public function __toString() {
			$html = '<div id="'.$this->cnr_id.'" class="ftd-cnr ftd-cnr-init dsp-non"> <!-- ftd stands for featured -->
					<div class="ftd-ctt-cnr fade-in-norm opa-0 vsb-hid"><!--can not use dsp non, because it must has height and width for ctt to generate-->
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
				$caption_html = 'These mordern art blocks are dynamically generated. It is different every time you instantiate the page. Refresh the page to see what I mean.';

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
			</div><!-- #featured container -->';

			return $html;
		}
	}
?>
