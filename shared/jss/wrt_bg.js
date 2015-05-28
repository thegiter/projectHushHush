var wrt_bg_lded = false;
var wrt_bg_imgName_arr = new Array('tl', 'top', 'tr', 'lft', 'rgt');

imgSet_fct_arr['wrt_bg'] = function() {
	wrt_bg_lded = true;
};

add_imgOnload('/shared/imgs/wrt_/*replace*/.jpg', wrt_bg_imgName_arr, function() {
	imgSet_fct_arr['wrt_bg']();
});