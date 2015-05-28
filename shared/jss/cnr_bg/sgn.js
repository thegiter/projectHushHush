var cnr_bg_imgName_arr = new Array('tl', 'top', 'lft');
var cnr_bg_lded = false;

add_fct('cnr_bg', function() {
	cnr_bg_lded = true;
});
add_imgOnload('/shared/imgs/cnr_/*replace*/.jpg', cnr_bg_imgName_arr, function() {
	imgSet_fct_arr['cnr_bg']();
});