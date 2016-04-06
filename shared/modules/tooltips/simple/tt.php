<?php
	defined('root') or die;
	
	function create_tooltip($cnrTag, $cnrId, $cnrClss, $cnrAttr, $hvrTag, $hvrId, $hvrClss, $hvrAttr, $ctt, $tooltip) {
		$html = '<'.$cnrTag;
		
		if ($cnrId) {
			$html .= ' id="'.$cnrId.'"';
		}
		
		$html .= ' class="tooltip-cnr '.$cnrClss.'"';
		
		if ($cnrAttr) {
			$html .= ' '.$cnrAttr;
		}
		
		$html .= '>
			<'.$hvrTag;
			
		if ($hvrId) {
			$html .= ' id="'.$hvrId.'"';
		}
		
		$html .= ' class="hvr';
		
		if ($hvrClss) {
			$html .= ' '.$hvrClss;
		}
		
		$html .= '"';
		
		if ($hvrAttr) {
			$html .= ' '.$hvrAttr;
		}
		
		$html .= '>
				'.$ctt.'
			</'.$hvrTag.'>
			
			<div class="tooltip" title="'.$tooltip.'">
				<div>
				</div>
			</div>
		</'.$cnrTag.'>';
		
		return $html;
	}
?>