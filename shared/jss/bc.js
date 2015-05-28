var bc_bg_imgName_arr = new Array('hd.png', 'bd.jpg', 'ass.png');

add_imgOnload('/shared/imgs/lb_/*replace*/', bc_bg_imgName_arr, function() {
	var bc_cnr_id = 'bc-cnr';

	function bc_init() {
		document.getElementById(bc_cnr_id).style.visibility = 'visible';
		
		onload_fadeIn(bc_cnr_id);
	}
	
	if (document.getElementById(bc_cnr_id)) {
		bc_init();
	}
	else {
		add_eleOnload(bc_cnr_id, function() {
			bc_init();
		});
	}
});