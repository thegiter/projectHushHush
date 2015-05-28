the9imgsPreload('/shared/errs/imgs/003_cnr_/*replace*/.png', true, function() {
	var cttCnr_id = 'ctt-cnr';

	function cttCnr_init() {
		mdf_cls(cttCnr_id, 'remove', 'vsb-hid');
		onload_fadeIn(cttCnr_id);
	}
	
	if (document.getElementById(cttCnr_id)) {
		cttCnr_init();
	}
	else {
		add_eleOnload(cttCnr_id, function() {
			cttCnr_init();
		});
	}
});

window.addEventListener('load', function() {
	document.getElementById('err-003-btn').addEventListener('click', function() {
		window.open("http://www.google.com/chrome/eula.html");
	}, false);
}, false);