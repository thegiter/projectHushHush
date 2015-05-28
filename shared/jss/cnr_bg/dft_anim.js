var cnr_bg_imgName_arr = new Array('tl', 'top', 'lft');
var cnr_bg_lded = false;

add_fct('cnr_bg', function() {
	function init(id) {
		mdf_cls(id, 'remove', 'dsp-non');							// optional dsp non, small alteration

		document.getElementById(id).style.visibility = 'visible';	// Some containers need to maintain size and therefore use vsb-hid instead. vsb hid is not the same as opa 0, opa 0 mean it's transparent, but it's still there and functional, eg, links will still be clickable, but vsb 0 will make it disappear, much the same as dsp non except it still occupies the space.
		
		onload_fadeIn(id);
	}
	
	var metas = document.getElementsByTagName('meta');				// metas is a nodeList and not an array, therefore can not use method forEach
	var meta
	
	for (var i = 0; i <= metas.length-1; i++) {
		meta = metas[i];
		
		if (meta.name === 'cnr_bg_dftAnim_ids') {
			meta.content.split(' ').forEach(function(value) {
				if (document.getElementById(value)) {
					init(value);
				}
				else {
					add_eleOnload(value, function() {
						init(value);
					});
				}
			});
			
			break;
		}
	}
});
add_imgOnload('/shared/imgs/cnr_/*replace*/.jpg', cnr_bg_imgName_arr, function() {
	cnr_bg_lded = true;

	imgSet_fct_arr['cnr_bg']();
});