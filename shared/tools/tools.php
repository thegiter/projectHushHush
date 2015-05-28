<?php
	defined('root') or die;
	
	class toolsModule {
		public $before;	// must have ; after public variable declarations
		public $after;
		
		public function __toString() {
			require_once root.'shared/fg_cnr/fg_cnr.php';
			
			$fgCnr = new fgCnrModule;
			
			$fgCnr->tag = 'aside';
			$fgCnr->id = 'tools-cnr';
			$fgCnr->posClass = null;
			$fgCnr->otherClasses = 'tools opa-0 tools-trans-delay';
			
			require_once root.'shared/onenote_menu_cnr/omc.php';
			
			$omc = new omcModule;
			
			$omc->clss = 'ctt-cnr';
			
			$toolsCtt = '';
			
			if ($this->before) {
				$toolsCtt .= $this->before.'
						';
			}
			
			require_once root.'shared/to_top/to_top.php';
			
			$toTopBtn = new toTopModule;
			
			$toTopBtn->btn_id = 'tools-to-top-btn';
			$toTopBtn->cnr_otherClss = 'ctr-mrg';
			
			$toolsCtt .= $toTopBtn;
			
			if ($this->after) {
				$toolsCtt .= '
						'.$this->after;
			}
			
			$omc->appendPane($toolsCtt);
			
			$fgCnr->content = $omc;
			
			return '<div id="tools-wpr" class="tools-wpr">
				'.$fgCnr.'
			</div>';
		}
	}
?>