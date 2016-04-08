include('script', '/shared/jss/wrt_bg.js', 'head');	//required for menu.js to work and therefore must be before menu.js

var mjs_path = '/shared/lv2/menu/jss/menu.js';

if (typeof wrt_bg_lded != 'undefined') {
	include('script', mjs_path, 'head');
}
else {
	var intId = setInterval(function() {
		if (typeof wrt_bg_lded != 'undefined') {					
			include('script', mjs_path, 'head');
			clearInterval(intId);
		}
	}, 10);
}

include('script', '/shared/jss/bc.js', 'head');