var abt_cmm_imgName_arr = new Array('nrm_shadow.png', 'hvr_shadow.png', 'base.jpg');
var abt_cmm_lded = false;

add_fct('abt_cmm', function() {
});

add_imgOnload('/about/imgs/abt_pnl_/*replace*/', abt_cmm_imgName_arr, function() {
	abt_cmm_lded = true;
	
	imgSet_fct_arr['abt_cmm']();
});

var abt_img_pnk = new Image();

abt_img_pnk.addEventListener('load', function() {
	function abt_shps_imgOnload() {
		var abt_shps_lnk_id = 'abt-shps-lnk';
		
		function abt_shps_lnk_init() {
			mdf_cls(abt_shps_lnk_id, 'remove', 'vsb-hid');
			onload_fadeIn(abt_shps_lnk_id);
		}
		
		if (document.getElementById(abt_shps_lnk_id)) {
			abt_shps_lnk_init();
		}
		else {
			add_eleOnload(abt_shps_lnk_id, function() {
				abt_shps_lnk_init();
			});
		}
	}
	
	if (abt_cmm_lded) {
		abt_shps_imgOnload();
	}
	else {
		add_fct('abt_cmm', function() {
			abt_shps_imgOnload();
		});
	}
}, false);

abt_img_pnk.src = '/about/imgs/abt_pnl_pnk.jpg';

var abt_img_blu = new Image();

abt_img_blu.addEventListener('load', function() {
	function abt_sh_imgOnload() {
		var abt_sh_lnk_id = 'abt-sh-lnk';
		
		function abt_sh_lnk_init() {
			mdf_cls(abt_sh_lnk_id, 'remove', 'vsb-hid');
			onload_fadeIn(abt_sh_lnk_id);
		}
		
		if (document.getElementById(abt_sh_lnk_id)) {
			abt_sh_lnk_init();
		}
		else {
			add_eleOnload(abt_sh_lnk_id, function() {
				abt_sh_lnk_init();
			});
		}
	}
	
	if (abt_cmm_lded) {
		abt_sh_imgOnload();
	}
	else {
		add_fct('abt_cmm', function() {
			abt_sh_imgOnload();
		});
	}
}, false);

abt_img_blu.src = '/about/imgs/abt_pnl_blu.jpg';

include('script', '/shared/jss/logo_bg_1.js');