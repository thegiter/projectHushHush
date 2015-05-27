(function() {
	function wrt_bg_onload() {
		function menu_init() {
			var mi_bg_id = 'mi-bg-cnr';
			var mi_crtPg = document.getElementById('menu-crt-itm');
			
			if (mi_crtPg) {
				var mi_bg = document.getElementById(mi_bg_id);
	
				mi_bg.style.left = mi_crtPg.offsetLeft+'px';
				mi_bg.style.top = mi_crtPg.offsetTop+'px';
				mi_bg.style.width = mi_crtPg.offsetWidth+'px';
				mi_bg.style.height = mi_crtPg.offsetHeight+'px';
				mi_bg.style.display = 'block';
	
				var menu_lnks = document.getElementById('menu-lst').getElementsByTagName('a');
				var mi_bg_pos_twn = new Tween(mi_bg.style, 'left', Tween.backEaseOut, mi_bg.offsetLeft, mi_bg.offsetLeft, 1, 'px');
				var mi_bg_siz_twn = new Tween(mi_bg.style, 'width', Tween.regularEaseOut, mi_bg.offsetWidth, mi_bg.offsetWidth, 1, 'px');
	
				for (var i = 0; i <= menu_lnks.length-1; i++) {
					menu_lnks[i].addEventListener('mouseover', function() {
						var mi = this.parentNode;

						mi_bg_pos_twn.continueTo(mi.offsetLeft, 1);
						mi_bg_siz_twn.continueTo(mi.offsetWidth, 1);
					}, false);
					menu_lnks[i].addEventListener('mouseout', function() {
						mi_bg_pos_twn.continueTo(mi_crtPg.offsetLeft, 1);
						mi_bg_siz_twn.continueTo(mi_crtPg.offsetWidth, 1);
					}, false);
				}
			}
		}

		if (wdwLoaded) {
			menu_init();
		}
		else {
			window.addEventListener('load', function() {
				menu_init();
			}, false);
		}
	}

	if (wrt_bg_lded) {
		wrt_bg_onload();
	}
	else {
		add_fct('wrt_bg', function() {
			wrt_bg_onload();
		});
	}
})();