<?php
	defined('root') or die;

	class omcModule {
		public $tag = 'div';
		public $id;
		public $clss;
		public $panes = [];

		public function appendPane($ctt) {
			array_push($this->panes, '');
		}

		public function __toString() {
			$html = '';

			return $html;
		}
	}
?>
