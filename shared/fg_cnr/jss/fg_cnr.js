var fg_cnr_lded = false;

imgSet_fct_arr['fg_cnr'] = function() {
	fg_cnr_lded = true;
};

the9imgsPreload('/shared/fg_cnr/imgs//*replace*/.png', true, function() {
	imgSet_fct_arr['fg_cnr']();
});