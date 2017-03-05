<?php
	function detect_bot() {
		$shps_bot_list = [
			"Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", 
			"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
			"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
			"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler"
		];

		foreach ($shps_bot_list as $shps_bot) {
			if (preg_match('/'.$shps_bot.'/', $_SERVER['HTTP_USER_AGENT'])) {
				return $shps_bot;
			}
		}

		return false;
	}
?>