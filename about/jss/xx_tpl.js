var xx_tpl_bnrSdw_imgName_arr = new Array('top.png', 'btm.png');
var xx_tpl_bnrSdw_lded = false;
var xx_tpl_bnr_id = 'xx-tpl-bd-cnr';

add_fct('xx_tpl_bnrSdw', function() {
	function xx_tpl_bnr_onLoad() {
		mdf_cls(xx_tpl_bnr_id, 'remove', 'vsb-hid');
		onload_fadeIn(xx_tpl_bnr_id);
	}
	
	if (document.getElementById(xx_tpl_bnr_id)) {
		xx_tpl_bnr_onLoad();
	}
	else {
		add_eleOnload(xx_tpl_bnr_id, function() {
			xx_tpl_bnr_onLoad();
		});
	}
});
add_imgOnload('/about/imgs/xx_tpl/bnr_sdws//*replace*/', xx_tpl_bnrSdw_imgName_arr, function() {
	xx_tpl_bnrSdw_lded = true;
	
	imgSet_fct_arr['xx_tpl_bnrSdw']();
});

var xx_tpl_mainCttCnr_lded = false;
var xx_tpl_mainCttCnr_id = 'xx-tpl-main-ctt-cnr';
var xx_tpl_mainCttCnr
var xx_tpl_mainCttCnr_opaTwn
var xx_tpl_mCC_inited = false;
var xx_tpl_mCC_init = [function() {
	xx_tpl_mCC_inited = true;
}];
var xx_tpl_mCC_bfrInit = new Array();

add_fct('xx_tpl_mainCttCnr', function() {
	function init() {
		xx_tpl_mainCttCnr = document.getElementById(xx_tpl_mainCttCnr_id);
		xx_tpl_mainCttCnr_opaTwn = new OpacityTween(xx_tpl_mainCttCnr, Tween.regularEaseOut, 0, 100, 1.5);

		mdf_cls(xx_tpl_mainCttCnr_id, 'remove', 'vsb-hid');
		
		if (xx_tpl_mCC_bfrInit.length >= 1) {
			xx_tpl_mCC_bfrInit.forEach(function(value/*, index, array*/) {
				value();
			});
		}
		
		xx_tpl_mainCttCnr_opaTwn.start();
		
		xx_tpl_mCC_init.forEach(function(value/*, index, array*/) {
			value();
		});
	}
	
	if (document.getElementById(xx_tpl_mainCttCnr_id)) {
		init();
	}
	else {
		add_eleOnload(xx_tpl_mainCttCnr_id, function() {
			init();
		});
	}
});
the9imgsPreload('/about/imgs/xx_tpl/main_ctt_cnr//*replace*/.png', false, function() {
	xx_tpl_mainCttCnr_lded = true;
	
	imgSet_fct_arr['xx_tpl_mainCttCnr']();
});

var xx_tpl_mainCtt_hr_imgName_arr = new Array('hd', 'ass');
var xx_tpl_mainCtt_hr_lded = false;
var xx_tpl_mainCtt_hr_id = 'xx-tpl-main-ctt-hr';
var xx_tpl_ps = '';
var xx_tpl_mainCtt_hr_show = new Function();
var xx_tpl_url = window.location.pathname;

if (xx_tpl_url == '/about/%E3%81%AE%E5%BC%91%E3%81%99%E9%AD%82%E3%81%AE_ps/') {	// encode in utf-8 is pointless for javascript, however, is still useful when requesting for url
	xx_tpl_ps = 'ps';
	
	xx_tpl_mainCtt_hr_show = function() {
		mdf_cls(xx_tpl_mainCtt_hr_id, 'remove', 'vsb-hid');
	};
}
else if (xx_tpl_url == '/about/%E3%81%AE%E5%BC%91%E3%81%99%E9%AD%82%E3%81%AE/') {
	xx_tpl_mainCtt_hr_show = function() {
		mdf_cls(xx_tpl_mainCtt_hr_id, 'remove', 'dsp-non');
	};
}

add_fct('xx_tpl_mainCtt_hr', function() {
	function init() {
		xx_tpl_mainCtt_hr_show();
		onload_fadeIn(xx_tpl_mainCtt_hr_id);
	}
	
	if (document.getElementById(xx_tpl_mainCtt_hr_id)) {
		init();
	}
	else {
		add_eleOnload(xx_tpl_mainCtt_hr_id, function() {
			init();
		});
	}
});
add_imgOnload('/about/imgs/sh'+xx_tpl_ps+'/hr//*replace*/.jpg', xx_tpl_mainCtt_hr_imgName_arr, function() {
	xx_tpl_mainCtt_hr_lded = true;

	imgSet_fct_arr['xx_tpl_mainCtt_hr']();
});
include('script', '/shared/jss/logo_bg_1.js');
imgPreload('/about/imgs/sh'+xx_tpl_ps+'/base_pic.jpg');