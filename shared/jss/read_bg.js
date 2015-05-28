var read_bg_lded = false;

imgSet_fct_arr['read_bg'] = function() {
	read_bg_lded = true;
};

the9imgsPreload('/shared/imgs/read_/*replace*/.jpg', false, function() {
	imgSet_fct_arr['read_bg']();
});