var dcp_btn_id = 'err-001-dcp-btn';
var err_001_wnd_img_arr = new Array('edge', 'btm_pnl');

add_imgOnload('/shared/imgs//*replace*/.jpg', err_001_wnd_img_arr, function() {
	if (document.getElementById(dcp_btn_id)) {
		mdf_cls(dcp_btn_id, 'remove', 'vsb-hid');
	}
	else {
		add_eleOnload(dcp_btn_id, function() {
			mdf_cls(dcp_btn_id, 'remove', 'vsb-hid');
		});
	}
});

function wnd() {
	window.showModalDialog('/shared/errs/ppps/wnd.html', '', 'center:yes; dialogHeight:200px; dialogWidth:500px; status:no');
}

addEvt(window, 'load', function() {
	addEvt(document.getElementById(dcp_btn_id), 'click', wnd);
});