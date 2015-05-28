(function() {
	var ctt_id = 'err-006-btm-half';
	var btn_id = 'err-006-btn';

	window.addEventListener('load', function() {
		var btn = document.getElementById(btn_id);
		var ctt = document.getElementById(ctt_id);
		
		function toggle() {
			if (btn.innerHTML.indexOf('More') != -1) {
				mdf_cls(ctt, 'remove', 'h-0');
				mdf_cls(ctt, 'add', 'vertical-padding');
				mdf_cls(ctt, 'remove', 'vsb-hid');
				
				btn.innerHTML = 'Less';
			}
			else {
				mdf_cls(ctt, 'add', 'vsb-hid');
				mdf_cls(ctt, 'remove', 'vertical-padding');
				mdf_cls(ctt, 'add', 'h-0');
				
				btn.innerHTML = 'More';
			}
		}
		
		btn.addEventListener('click', toggle, false);
		
		var bg_clss = ['top', 'lft', 'rgt', 'btm'];
		var cntr = 0;
		
		var intId = setInterval(function() {
			cntr++;
			
			if (cntr >= (bg_clss.length)) {
				clearInterval(intId);
			}
			
			var bgs = document.getElementsByClassName('bg-'+bg_clss[cntr-1]);	//returns a NodeList, not an array, therefore no forEach
			
			for (var i = 0; i < bgs.length; i++) {
				mdf_cls(bgs[i], 'remove', 'opa-0');
			}
		}, 300);	//1000 = 1s
	}, false);
})();