var img_ldg = new Image();

addEvt(img_ldg, 'load', function() {				//this is IMAGE onload! must be assigned before it's src
	if (document.getElementById('cnr')) {
		mdf_cls('cnr', 'remove', 'dsp-non');
	}
	else {
		add_eleOnload('cnr', function() {
			mdf_cls('cnr', 'remove', 'dsp-non');
		});
	}
});

img_ldg.src = '/shared/imgs/ldg_cvr.jpg';

var ldg_after = function() {
	clearTimeout(ldg_inf_toId);
	clearInterval(ldg_anim_mov1_intId);
	clearInterval(ldg_anim_mov2_intId);
	
	mdf_cls('ldg-cnr', 'remove', 'dsp-tbl');
	mdf_cls('ldg-cnr', 'add', 'dsp-non');
};

//timeout for loading
var ldg_toCnr_id = 'ldg-to-cnr';
var ldg_refresh_id = 'ldg-refresh';
var ldg_close_id = 'ldg-close';

var ldg_toId = setTimeout(function() {
	function ldgTo_init() {
		//add listeners
		document.getElementById(ldg_refresh_id).addEventListener('click', refresh, false);
		document.getElementById(ldg_close_id).addEventListener('click', function() {
			window.removeEventListener('load', ldg_onWindowLoad);
			
			ldg_onWindowLoad();
		}, false);
		
		//show cnr
		mdf_cls(document.getElementById(ldg_toCnr_id), 'remove', 'dsp-non');
		mdf_cls(document.getElementById(ldg_toCnr_id), 'remove', 'opa-0');
	}
	
	if (document.getElementById(ldg_toCnr_id)) {
		ldgTo_init();
	}
	else {
		add_eleOnload(ldg_toCnr_id, ldgTo_init);
	}
}, 60000);	//1 min

function ldg_onWindowLoad() {
	//window loads, cancel timeout
	clearTimeout(ldg_toId);
	
	var ldg_fadeOut_opatwn = new OpacityTween(document.getElementById('ldg-cnr'), Tween.regularEaseIn, 100, 0, 2);
	
	ldg_fadeOut_opatwn.onMotionFinished = function() {
		ldg_after();
	};
	
	ldg_fadeOut_opatwn.start();
}

window.addEventListener('load', ldg_onWindowLoad, false);

function ldg_addAfter(fct) {
	var ldg_after_old = ldg_after;

	ldg_after = function() {
		ldg_after_old();
		fct();
	}
}

var ldg_inf_cnter = 0;
var ldg_inf_arr = new Array('Awesomizing the title', 'Hacking CSS', 'Hacking your system', 'Compromising the firewall', 'Beautifying buttons', 'Stylizing texts', 'Planting a virus and stealing a whole lot of data at the same time', 'Unloading the loader', 'Waiting', 'Animating the dead');

var ldg_inf_toId
var ldg_anim_fadeIn_opaTwn
var ldg_anim_fadeOut_opaTwn

add_eleOnload('ldg-inf-2', function() {	
	ldg_inf_arr.shuffle();
	
	var ldg_inf_posRef = document.getElementById('ldg-inf-posref');
	
	ldg_inf_posRef.innerHTML = ldg_inf_arr[ldg_inf_cnter]+'...';
	
	var ldg_inf_1 = document.getElementById('ldg-inf-1');
	var ldg_inf_2 = document.getElementById('ldg-inf-2');
	ldg_anim_fadeIn_opaTwn = new OpacityTween(ldg_inf_1, Tween.regularEaseOut, 0, 100, 2.5);
	ldg_anim_fadeOut_opaTwn = new OpacityTween(ldg_inf_2, Tween.regularEaseOut, 100, 0, 2.5);
	
	setTimeout(function() {
		ldg_inf_toId = ldg_ldgAnim(ldg_inf_1, ldg_inf_2, ldg_inf_posRef);
	}, 500);
});

var ldg_anim_mov1_intId
var ldg_anim_mov2_intId

	
function ldg_ldgAnim(ldg_inf_1, ldg_inf_2, ldg_inf_posRef) {
	var end = ldg_inf_posRef.parentNode.offsetWidth;			// get the pixel where the container ends

	ldg_inf_2.style.left = end+'px';							// set ldg_inf_2 to the end
	ldg_inf_2.innerHTML = ldg_inf_arr[ldg_inf_cnter]+'...';		// set the content of the coming info

	mdf_cls(ldg_inf_2.id, 'remove', 'dsp-non');					// show ldg_inf_2
	
	ldg_inf_1.style.left = ldg_inf_1.offsetLeft+'px';			// set ldg_inf_1's left to get ready to absolute positioning
	ldg_inf_1.style.position = 'absolute';						// absolute positioning ldg_inf_1

	ldg_anim_mov1_intId = setInterval(function() {	// change number of pixel moved as changing interval will have no effect becaues browser still redraws very slowly
		move(ldg_inf_1.id, 'left', 10);
	}, 10);
	ldg_anim_mov2_intId = setInterval(function() {	// MotionTween can not perform a check every time it moves the element. Therefore I used interval to perform checks and stop the move when the user resizes window and cause the destination to arrive early
		move(ldg_inf_2.id, 'left', 10);

		if (ldg_inf_2.offsetLeft <= ldg_inf_posRef.offsetLeft) {
			clearInterval(ldg_anim_mov2_intId);

			ldg_inf_2.style.position = 'static';

			if (ldg_inf_cnter >= 9) {
				ldg_inf_arr.shuffle();
				
				ldg_inf_cnter = 0;
			}
			else {
				ldg_inf_cnter++;
			}
			
			ldg_inf_posRef.innerHTML = ldg_inf_arr[ldg_inf_cnter]+'...';
	
			ldg_inf_toId = setTimeout(function() {
				ldg_ldgAnim(ldg_inf_2, ldg_inf_1, ldg_inf_posRef)
			}, Math.ceil(Math.random()*(10000-4000)+4000));
		}
	}, 10);
	
	ldg_anim_fadeOut_opaTwn.targetObject = ldg_inf_1;			// targetObject for opacity tween, obj for tween
	ldg_anim_fadeOut_opaTwn.onMotionFinished = function() {
		clearInterval(ldg_anim_mov1_intId);
	};
	ldg_anim_fadeIn_opaTwn.targetObject = ldg_inf_2;
	
	ldg_anim_fadeOut_opaTwn.start();
	ldg_anim_fadeIn_opaTwn.start();
}