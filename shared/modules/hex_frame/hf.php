<?php
	defined('root') or die;
	
	class hexFrameModule {
		public static function getHtml($type) {
			switch ($type) {
				case 'h':
					return '<div class="hex-frame-h lft">
					</div>
					<div class="hex-frame-h tl">
					</div>
					<div class="hex-frame-h tr">
					</div>
					<div class="hex-frame-h rgt">
					</div>
					<div class="hex-frame-h br">
					</div>
					<div class="hex-frame-h bl">
					</div>';
				case 'v':
					return '<div class="hex-frame-v top">
					</div>
					<div class="hex-frame-v tr">
					</div>
					<div class="hex-frame-v br">
					</div>
					<div class="hex-frame-v btm">
					</div>
					<div class="hex-frame-v bl">
					</div>
					<div class="hex-frame-v tl">
					</div>';
				default:
			}
		}
	}
?>