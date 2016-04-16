<?php
	defined('root') or die;
	
	class psdMenu {
		public static function createMiHtml($cnrId, $cnrClss, $iconHtml, $lblTxt) {
			$html = '<div ';
			
			if ($cnrId) {
				$html .= 'id="'.$cnrId.'" ';
			}
			
			$html .= 'class="icon-wpr';
			
			if ($cnrClss) {
				$html .= ' '.$cnrClss;
			}
			
			require_once root.'shared/modules/hex_frame/hf.php';
			
			$html .= '">
				<span class="icon-cnr">
					'.hexFrameModule::getHtml('h').'
					<div class="icon">';
			
			if ($iconHtml) {
				$html .= $iconHtml;
			}
			
			$html .= '</div>
				</span><span class="lbl">
					'.$lblTxt.'
				</span>				
			</div>';
			
			return $html;
		}
	}
?>