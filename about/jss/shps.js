var shps_cttSwitcher_imgName_arr = new Array('switch/on/nrm', 'switch/off/nrm', 'switch/off/hvr', 'switch/on/hvr', 'tab/rgt/base', 'tab/rgt/blk_cvr', 'tab/lft/base', 'tab/lft/blk_cvr');
var shps_cttSwitcher_lded = false;
var shps_ids = {};

shps_ids.cttSwitcher_cnr = 'xx-tpl-ctt-switcher-cnr';

add_fct('shps_cttSwitcher', function() {
	function init() {
		mdf_cls(shps_ids.cttSwitcher_cnr, 'remove', 'dsp-non');
		mdf_cls(shps_ids.cttSwitcher_cnr, 'remove', 'opa-0');		//removing this triggers the opacity transition anim
	}
	
	if (document.getElementById(shps_ids.cttSwitcher_cnr)) {
		init();
	}
	else {
		add_eleOnload(shps_ids.cttSwitcher_cnr, init);
	}
});
add_imgOnload('/about/imgs/shps/menu//*replace*/.png', shps_cttSwitcher_imgName_arr, function() {
	shps_cttSwitcher_lded = true;
	
	imgSet_fct_arr['shps_cttSwitcher']();
});

var shps_tap_show = new Function();

add_eleOnload(shps_ids.cttSwitcher_cnr, function() {
	var shps_elms = {};

	shps_elms.cttSwitcherTabsCnr = document.getElementById('xx-tpl-ctt-switcher-tabs-cnr');
	shps_elms.cttSwitcher_tab_rgt = document.getElementById('xx-tpl-ctt-switcher-tab-rgt');
	shps_elms.cttSwitcher_tab_lft = document.getElementById('xx-tpl-ctt-switcher-tab-lft');

	var shps_activeTab_cls = 'shps-ctt-switcher-tab-active';
	
	function get_shps_curActLi() {
		return document.getElementsByClassName(shps_activeTab_cls)[0];
	}
	
	function get_shps_actLiPos(side) {
		var actLi = get_shps_curActLi();
		
		switch (side) {
			case 'rgt':
				return shps_elms.cttSwitcherTabsCnr.offsetWidth-actLi.offsetLeft-actLi.offsetWidth;
				
				break;
			case 'lft':
				return actLi.offsetLeft;
		}
	}
	
	function shps_tab_move(side){
		var actLi = get_shps_curActLi();
		
		switch (side) {
			case 'rgt':
				shps_elms.cttSwitcher_tab_rgt.style.right = 0;
				
				actLi.appendChild(shps_elms.cttSwitcher_tab_rgt);
				
				break;
			case 'lft':
				actLi.appendChild(shps_elms.cttSwitcher_tab_lft);
		}
	}
	
	shps_elms.cttSwitcher_actLi = get_shps_curActLi();
	shps_elms.cttSwitcher_lis = shps_elms.cttSwitcherTabsCnr.getElementsByTagName('li');
	shps_elms.cttSwitcher_1stLi = shps_elms.cttSwitcherTabsCnr.getElementsByTagName('li')[0];
	shps_elms.cttSwitcherCnr = document.getElementById(shps_ids.cttSwitcher_cnr);
	
	var shps_on_swapBg = new Object();
	
	shps_on_swapBg.onMotionFinished = function() {
		for (i = 0; i <= shps_elms.cttSwitcher_lis.length-1; i++) {
			mdf_cls(shps_elms.cttSwitcher_lis[i], 'remove', 'vsb-hid');
		}

		shps_tab_move('rgt');
		
		if (shps_elms.cttSwitcher_actLi == shps_elms.cttSwitcher_1stLi) {
			shps_elms.cttSwitcher_tab_lft.style.display = 'none';
		}
		else {
			shps_tab_move('lft');
			
			shps_elms.cttSwitcher_tab_lft.style.display = 'block';
		}
		
		shps_elms.cttSwitcherCnr.style.width = 'auto';
	};
	
	var shps_tap_ajax
	
	try {
		// Opera 8.0+, Firefox, Safari
		shps_tap_ajax = new XMLHttpRequest();
	}
	catch (e) {
		// Internet Explorer Browsers
		try {
			shps_tap_ajax = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			shps_tap_ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}

	var shps_tap_ctt_arr = new Array();
	
	shps_elms.tap_cttCnr = document.getElementById('shps-tap-ctt-cnr');
	
	var shps_tap_cttCnr_opaTwn = new OpacityTween(shps_elms.tap_cttCnr, Tween.regularEaseOut, 0, 0, 0);
	var shps_tabTtl
	var shps_cttSwitcher_state
	
	xx_tpl_mCC_bfrInit.push(function() {
		if ((typeof(shps_cttSwitcher_state) !== 'undefined') && (shps_cttSwitcher_state == ('opening' || 'on'))) {
			xx_tpl_mainCttCnr_opaTwn.toOpacity = 10;
			xx_tpl_mainCttCnr_opaTwn.duration = 0.15;
		}
	});
	
	function shps_updateTap() {
		mdf_cls(shps_elms.tap_cttCnr, 'add', 'dsp-non');

		shps_elms.tap_cttCnr.innerHTML = shps_tap_ctt_arr[shps_tabTtl];

		shps_elms.tap_ttl = document.getElementById('shps-tap-ttl');
		
		shps_elms.tap_ttl.style.marginRight = shps_elms.cttSwitcherCnr.offsetWidth-shps_elms.cttSwitcherTabsCnr.offsetLeft+16-parseInt(window.getComputedStyle(shps_elms.tap_ttl.parentNode, null).getPropertyValue("margin-right"), 10)+'px';

		shps_tap_cttCnr_opaTwn.rewind();

		if (shps_cttSwitcher_state == ('on' || 'opening')) {
			mdf_cls(shps_elms.tap_cttCnr, 'remove', 'dsp-non');

			shps_tap_cttCnr_opaTwn.continueTo(90, 1);
		}
	}
	
	shps_tap_ajax.onreadystatechange = function() {
		if (shps_tap_ajax.readyState == 4) {
			shps_tap_ctt_arr[shps_tabTtl] = shps_tap_ajax.responseText;

			shps_updateTap();
		}
	}
	
	function shps_updateTap_init() {
		shps_tabTtl = shps_elms.cttSwitcher_actLi.getElementsByClassName('xx-tpl-ctt-cnr')[0].textContent.replace(/ /g, '_').toLowerCase();	//innerText is the ie equivalent but only works for block elements

		if (shps_tap_ctt_arr[shps_tabTtl] == undefined) {
			if (shps_tap_ajax.readyState != 0) {
				shps_tap_ajax.abort();
			}

			var param = 'refuri='+window.location.pathname;
			
			shps_tap_ajax.open('POST', '/about/の弑す魂の_ps/terms_and_policies/'+shps_tabTtl+'/', true);
			//Send the proper header information along with the request
			shps_tap_ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			shps_tap_ajax.send(param);
			
			mdf_cls(shps_elms.tap_cttCnr, 'remove', 'dsp-non');
			
			shps_elms.tap_cttCnr.innerHTML = 'Loading...';
		}
		else {
			shps_updateTap();
		}
	}
	
	var shps_cttSwitcher_anim_onFinished_fct = new Function();
	var shps_cttSwitcher_anim_onFinished = new Object();
	
	shps_cttSwitcher_anim_onFinished.onMotionFinished = function() {
		shps_cttSwitcher_state = 'on';
		
		mdf_cls(shps_elms.tap_cttCnr, 'add', 'xx-tpl-ctt-cnr');
		
		shps_updateTap_init();
		
		shps_cttSwitcher_anim_onFinished_fct();
	}
	
	var shps_cttSwitcher_tab_rgt_sizTwn = new Tween(shps_elms.cttSwitcher_tab_rgt.style, 'width', Tween.regularEaseOut, 0, 0, 0, 'px');
	var shps_cttSwitcher_ttl_animWidth
	var shps_cttSwitcher_sizTwn = new Tween(shps_elms.cttSwitcherCnr.style, 'width', Tween.regularEaseIn, 44, 0, 0, 'px');

	shps_cttSwitcher_sizTwn.onMotionFinished = function() {
		shps_cttSwitcher_tab_rgt_sizTwn.addListener(shps_on_swapBg);
		shps_cttSwitcher_tab_rgt_sizTwn.addListener(shps_cttSwitcher_anim_onFinished);
		
		shps_cttSwitcher_tab_rgt_sizTwn.func = Tween.regularEaseOut;
		
		shps_cttSwitcher_tab_rgt_sizTwn.continueTo(16, 16/shps_cttSwitcher_ttl_animWidth);
	};
	
	shps_ids.tap_bc = 'xx-tpl-tap-anim-blk-cvr';
	
	var shps_tap_bc_opaTwn = new OpacityTween(document.getElementById(shps_ids.tap_bc), Tween.regularEaseOut, 0, 0, 0);
	var shps_tap_anim_drt = 2;
	var shps_mainCttCnr_opaTwn_onFinished = new Object();
	
	shps_mainCttCnr_opaTwn_onFinished.onMotionFinished = function() {
		mdf_cls(xx_tpl_mainCttCnr_id, 'add', 'xx-tpl-ctt-cnr');
		mdf_cls(shps_ids.tap_bc, 'remove', 'dsp-non');
		
		shps_tap_bc_opaTwn.continueTo(0, getStyle(shps_ids.tap_bc, 'opacity')*shps_tap_anim_drt);
	};
	
	var shps_cS_on_mCC_prep = new Function();
	var shps_cS_on_mCC_init = new Function();
	var shps_cS_off_mCC_prep = new Function();
	var shps_cS_off_mCC_init = new Function();
	
	function shps_cS_mCC_init() {			// cS: cttSwitcher
		shps_cS_on_mCC_prep = function() {
			xx_tpl_mainCttCnr_opaTwn.removeListener(shps_mainCttCnr_opaTwn_onFinished);

			shps_tap_bc_opaTwn.onMotionFinished = function() {
				mdf_cls(xx_tpl_mainCttCnr_id, 'remove', 'xx-tpl-ctt-cnr');
				mdf_cls(shps_ids.tap_bc, 'add', 'dsp-non');

				xx_tpl_mainCttCnr_opaTwn.continueTo(10, (getStyle(xx_tpl_mainCttCnr_id, 'opacity')-0.1)*shps_tap_anim_drt);
			};
		};
		shps_cS_on_mCC_init = function() {
			if (xx_tpl_mainCttCnr.className.search(/xx-tpl-ctt-cnr/) != -1) {
				mdf_cls(shps_ids.tap_bc, 'remove', 'dsp-non');
				
				shps_tap_bc_opaTwn.continueTo(5, (0.05-getStyle(shps_ids.tap_bc, 'opacity'))*shps_tap_anim_drt);
			}
			else {
				xx_tpl_mainCttCnr_opaTwn.continueTo(10, (getStyle(xx_tpl_mainCttCnr_id, 'opacity')-0.1)*shps_tap_anim_drt);
			}
		};
		shps_cS_off_mCC_prep = function() {
			shps_tap_bc_opaTwn.onMotionFinished = function() {
				mdf_cls(shps_ids.tap_bc, 'add', 'dsp-non');
			};
		};
		shps_cS_off_mCC_init = function() {
			if (xx_tpl_mainCttCnr.className.search(/xx-tpl-ctt-cnr/) != -1) {
				mdf_cls(shps_ids.tap_bc, 'remove', 'dsp-non');
			
				shps_tap_bc_opaTwn.continueTo(0, getStyle(shps_ids.tap_bc, 'opacity')*shps_tap_anim_drt);
			}
			else {
				xx_tpl_mainCttCnr_opaTwn.addListener(shps_mainCttCnr_opaTwn_onFinished);
				xx_tpl_mainCttCnr_opaTwn.continueTo(100, (1-getStyle(xx_tpl_mainCttCnr_id, 'opacity'))*shps_tap_anim_drt);
			}
		};
	}
	
	if (xx_tpl_mCC_inited) {
		shps_cS_mCC_init();
	}
	else {
		xx_tpl_mCC_init.push(function() {
			shps_cS_mCC_init();
		});
	}
	
	var shps_fs_btn_off_fct = new Function();
	
	shps_elms.switch_on_anim = document.getElementById('xx-tpl-ctt-switch-on-anim');
	shps_ids.cttSwitch_on = 'xx-tpl-ctt-switch-on';
	shps_elms.cttSwitch_on = document.getElementById(shps_ids.cttSwitch_on);
	shps_ids.switch_on_anim_cnr = 'xx-tpl-ctt-switch-on-anim-cnr';
	
	var shps_switch_on_anim_sizTwn = new Tween(shps_elms.switch_on_anim.style, 'width', Tween.regularEaseOut, 0, 0, 0, '%');
	
	shps_switch_on_anim_sizTwn.onMotionFinished = function() {
		shps_elms.cttSwitch_on.href = '#'+shps_elms.cttSwitcher_actLi.getElementsByClassName('xx-tpl-ctt-cnr')[0].textContent.toLowerCase();
		shps_elms.cttSwitch_on.style.display = 'block';
		
		mdf_cls(shps_ids.switch_on_anim_cnr, 'add', 'dsp-non');
		mdf_cls(shps_ids.switch_on_anim_cnr, 'remove', 'dsp-tbl');
		
		shps_fs_btn_off_fct();
		
		shps_cttSwitcher_state = 'off';
	};
	
	shps_elms.switch_on_anim_cnr = document.getElementById(shps_ids.switch_on_anim_cnr);
	
	var shps_cttSwitcherCnr_off_anim = new Object();

	shps_cttSwitcherCnr_off_anim.onMotionFinished = function() {
		shps_switch_on_anim_sizTwn.continueTo(100, shps_elms.switch_on_anim_cnr.offsetWidth/shps_cttSwitcher_ttl_animWidth);
	};
	
	var shps_cttSwitcherCnr_openWidth
	
	shps_ids.cttSwitch_off = 'xx-tpl-ctt-switch-off';
	shps_elms.cttSwitch_off = document.getElementById(shps_ids.cttSwitch_off);
	
	function shps_off_swapBg() {
		for (i = 0; i <= shps_elms.cttSwitcher_lis.length-1; i++) {
			mdf_cls(shps_elms.cttSwitcher_lis[i], 'add', 'vsb-hid');
		}
	}
	
	var shps_fs_btn_on_fct = new Function();
	
	shps_tap_show = function(evt) {
		var tab_ttl = window.location.hash;
	
		tab_ttl = tab_ttl.replace('#', '');
		
		if (tab_ttl == '') {
			if (shps_cttSwitcher_state == ('on' || 'opening')) {
				shps_cttSwitcher_state = 'closing';
			
				shps_cttSwitcher_sizTwn.stop();
				shps_cttSwitcher_tab_rgt_sizTwn.removeListener(shps_on_swapBg);
				shps_cttSwitcher_tab_rgt_sizTwn.removeListener(shps_cttSwitcher_anim_onFinished);
				
				shps_cS_off_mCC_prep();
				
				mdf_cls(shps_elms.tap_cttCnr, 'remove', 'xx-tpl-ctt-cnr');
				mdf_cls(shps_elms.tap_cttCnr, 'add', 'dsp-non');

				if (shps_elms.cttSwitcher_1stLi == shps_elms.cttSwitcher_actLi) {
					shps_cttSwitcherCnr_openWidth = shps_elms.cttSwitcherCnr.offsetWidth-shps_elms.cttSwitcherTabsCnr.offsetLeft-shps_elms.cttSwitcherTabsCnr.offsetWidth+get_shps_actLiPos('rgt');
				}
				else{
					shps_cttSwitcherCnr_openWidth = shps_elms.cttSwitcherCnr.offsetWidth-shps_elms.cttSwitcherTabsCnr.offsetLeft;

					shps_elms.cttSwitcher_tab_lft.style.display = 'none';
					
					shps_elms.cttSwitcherTabsCnr.appendChild(shps_elms.cttSwitcher_tab_rgt);
					
					shps_elms.cttSwitcher_tab_rgt.style.right = shps_elms.cttSwitcherTabsCnr.offsetWidth+'px';
				}
				
				shps_elms.cttSwitch_off.style.display = 'none';
				
				if (shps_elms.cttSwitcherCnr.style.width == 'auto') {
					shps_elms.switch_on_anim_cnr.style.width = shps_cttSwitcherCnr_openWidth+'px';
				}
				else {
					shps_elms.switch_on_anim_cnr.style.width = shps_elms.cttSwitcherCnr.offsetWidth+'px';
					shps_elms.cttSwitcherCnr.style.width = 'auto';
				}
				
				shps_switch_on_anim_sizTwn.suffixe = '';
				
				shps_switch_on_anim_sizTwn.rewind();
				
				shps_switch_on_anim_sizTwn.suffixe = '%';
				
				mdf_cls(shps_ids.switch_on_anim_cnr, 'add', 'dsp-tbl');
				mdf_cls(shps_ids.switch_on_anim_cnr, 'remove', 'dsp-non');
				
				shps_off_swapBg();
				
				shps_elms.switch_on_anim.style.backgroundPosition = shps_elms.switch_on_anim_cnr.offsetWidth-44+6+'px center';
				
				shps_cttSwitcher_ttl_animWidth = shps_cttSwitcherCnr_openWidth+16;

				shps_cttSwitcher_tab_rgt_sizTwn.addListener(shps_cttSwitcherCnr_off_anim);
				
				shps_cttSwitcher_tab_rgt_sizTwn.func = Tween.regularEaseIn;
				
				shps_cttSwitcher_tab_rgt_sizTwn.continueTo(0, 16/shps_cttSwitcher_ttl_animWidth);
				
				shps_cS_off_mCC_init();
				
				//hashchange event is non cancelable
				/*if (evt && (evt.type == 'hashchange')) {
					evt.preventDefault();
				}*/
			}
			
			return;
		}

		var shps_newLi_ttlCnr // a new undefined var for later checking if found
		
		for (i = 0; i <= shps_elms.cttSwitcher_lis.length-1; i++) {
			shps_elms.tmp_ttlCnr = shps_elms.cttSwitcher_lis[i].getElementsByClassName('xx-tpl-ctt-cnr')[0];

			if (shps_elms.tmp_ttlCnr.textContent.toLowerCase() == tab_ttl) {
				shps_newLi_ttlCnr = shps_elms.tmp_ttlCnr;
				
				break;	// break out of the for loop
			}
		}
		
		if (shps_newLi_ttlCnr == undefined) {
			return;
		}
		
		if (shps_cttSwitcher_state == 'on') {
			shps_newLi_ttlCnr.addEventListener('click', returnFalse, true);
		
			var shps_tmp_li = shps_newLi_ttlCnr.parentNode;
			
			mdf_cls(shps_tmp_li, 'add', 'shps-ctt-switcher-tab-active');
			mdf_cls(shps_tmp_li, 'remove', 'shps-ctt-switcher-tab-inactive');
			mdf_cls(shps_elms.cttSwitcher_actLi, 'remove', 'shps-ctt-switcher-tab-active');
			mdf_cls(shps_elms.cttSwitcher_actLi, 'add', 'shps-ctt-switcher-tab-inactive');
			
			shps_tab_move('rgt');
			
			if (shps_tmp_li == shps_elms.cttSwitcher_1stLi) {
				shps_elms.cttSwitcher_tab_lft.style.display = 'none';
			}
			else {
				shps_tab_move('lft');
				
				shps_elms.cttSwitcher_tab_lft.style.display = 'block';
			}
			
			shps_elms.cttSwitcher_actLi.getElementsByClassName('xx-tpl-ctt-cnr')[0].removeEventListener('click', returnFalse, true);
			
			shps_fs_btn_off_fct();
			
			shps_elms.cttSwitcher_actLi = shps_tmp_li;
			
			shps_fs_btn_on_fct();
			
			shps_updateTap_init();
		}
		else {
			if (shps_cttSwitcher_state == ('opening' || 'closing')) {
				shps_cttSwitcher_anim_onFinished_fct = function() {
					shps_tap_show();
					
					shps_cttSwitcher_anim_onFinished_fct = new Function();
				};
				
				if (shps_cttSwitcher_state == 'opening') {
					return;
				}
			}
			else {
				var shps_newLi = shps_newLi_ttlCnr.parentNode;
				
				if (shps_elms.cttSwitcher_actLi !== shps_newLi) {
					shps_newLi_ttlCnr.addEventListener('click', returnFalse, true);
					
					mdf_cls(shps_newLi, 'add', 'shps-ctt-switcher-tab-active');
					mdf_cls(shps_newLi, 'remove', 'shps-ctt-switcher-tab-inactive');
					mdf_cls(shps_elms.cttSwitcher_actLi, 'remove', 'shps-ctt-switcher-tab-active');
					mdf_cls(shps_elms.cttSwitcher_actLi, 'add', 'shps-ctt-switcher-tab-inactive');
					
					shps_elms.cttSwitcher_actLi.getElementsByClassName('xx-tpl-ctt-cnr')[0].removeEventListener('click', returnFalse, true);

					shps_elms.cttSwitcher_actLi = shps_newLi;
				}
			}
			
			shps_cttSwitcher_state = 'opening';

			shps_cttSwitcher_tab_rgt_sizTwn.removeListener(shps_cttSwitcherCnr_off_anim);
			
			shps_cS_on_mCC_prep();
			
			shps_elms.cttSwitch_off.style.display = 'block';
			shps_elms.cttSwitch_on.style.display = 'none';
			
			shps_elms.cttSwitcher_actLi = get_shps_curActLi();
			
			shps_fs_btn_on_fct();
			
			if (shps_elms.cttSwitcher_1stLi == shps_elms.cttSwitcher_actLi) {
				shps_cttSwitcherCnr_openWidth = shps_elms.cttSwitcherCnr.offsetWidth-shps_elms.cttSwitcherTabsCnr.offsetLeft-shps_elms.cttSwitcherTabsCnr.offsetWidth+get_shps_actLiPos('rgt');
				
				shps_tab_move('rgt');
			}
			else{
				shps_cttSwitcherCnr_openWidth = shps_elms.cttSwitcherCnr.offsetWidth-shps_elms.cttSwitcherTabsCnr.offsetLeft;

				shps_elms.cttSwitcher_tab_rgt.style.right = shps_elms.cttSwitcherTabsCnr.offsetWidth+'px';
			}
			
			var shps_cttSwitcherCnr_animWidth = shps_cttSwitcherCnr_openWidth-shps_elms.cttSwitcherCnr.offsetWidth;
			
			shps_cttSwitcher_ttl_animWidth = shps_cttSwitcherCnr_animWidth+16;
			
			shps_elms.cttSwitcher_tab_rgt.style.width = '0';
			shps_elms.cttSwitcher_tab_rgt.style.display = 'block';

			shps_cttSwitcher_sizTwn.rewind();
			shps_cttSwitcher_sizTwn.continueTo(shps_cttSwitcherCnr_openWidth, shps_cttSwitcherCnr_animWidth/shps_cttSwitcher_ttl_animWidth);
			
			shps_cS_on_mCC_init();
		}
	};
	
	window.addEventListener('hashchange', shps_tap_show, true);
	
	add_eleOnload('xx-tpl-fs-cnr', function() {
		var shps_actLi_ttl
		
		shps_elms.cr_cnr = document.getElementById('shps-cr-cnr');
		shps_elms.fs_btn_terms = document.getElementById('shps-fs-btn-terms');
		shps_elms.fs_btn_pvc = document.getElementById('shps-fs-btn-pvc');
		
		function get_fs_btn() {
			shps_actLi_ttl = shps_elms.cttSwitcher_actLi.getElementsByClassName('xx-tpl-ctt-cnr')[0].textContent;
			
			switch (shps_actLi_ttl) {
				case 'Terms of Use':
					return shps_elms.fs_btn_terms;
				case 'Privacy Policy':
					return shps_elms.fs_btn_pvc;
				case 'Copyright':
					return shps_elms.cr_cnr;
			}
		}
		
		shps_fs_btn_on_fct = function() {
			var tmp_btn = get_fs_btn();

			tmp_btn.removeAttribute('href');
		};
		shps_fs_btn_off_fct = function() {
			var tmp_btn = get_fs_btn();
			
			tmp_btn.href = '#'+shps_actLi_ttl.toLowerCase();
		};
	});
});

window.addEventListener('load', function() {	// must use this anonymous function for the function below to be excuted? Maybe not
	shps_tap_show();
}, false);