<?php
	defined('root') or die;
	
	class cacheCtrlModule {
		public static function validate($lm, $etag = null) {
			$ifMod = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
			$ifMat = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;

			header('Last-Modified: '.$lm);
			
			//set etag-header
			if ($etag) {
				header('Etag: '.$etag);
			}
			
			//validate
			if ($ifMod && ($ifMod == $lm)) {
				if (!$etag || ($etag == $ifMat)) {
					header('HTTP/1.1 304 Not Modified');
					exit;
				}
			}
		}
	}
?>