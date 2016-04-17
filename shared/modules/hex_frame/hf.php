<?php
	defined('root') or die;
	
	class hexFrameModule {
		public static function getHtml($type) {
			switch ($type) {
				case 'h':
					return '<div class="hex-frame-h lft">
					</div>
					<div class="hex-frame-h tl-pstnr">
						<div class="tl">
						</div>
					</div>
					<div class="hex-frame-h tr-pstnr">
						<div class="tr">
						</div>
					</div>
					<div class="hex-frame-h rgt">
					</div>
					<div class="hex-frame-h br-pstnr">
						<div class="br">
						</div>
					</div>
					<div class="hex-frame-h bl-pstnr">
						<div class="bl">
						</div>
					</div>';
				case 'v':
					return '<div class="hex-frame-v top">
					</div>
					<div class="hex-frame-v tr-pstnr">
						<div class="tr">
						</div>
					</div>
					<div class="hex-frame-v br-pstnr">
						<div class="br">
						</div>
					</div>
					<div class="hex-frame-v btm">
					</div>
					<div class="hex-frame-v bl-pstnr">
						<div class="bl">
						</div>
					</div>
					<div class="hex-frame-v tl-pstnr">
						<div class="tl">
						</div>
					</div>';
				default:
			}
		}
	}
?>