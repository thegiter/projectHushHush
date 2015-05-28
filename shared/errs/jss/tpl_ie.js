function showHide() {
	if (document.getElementById('sh-ctt').className == 'dsp-non') {
		document.getElementById('sh-ctt').className = 'dsp-tc';
		document.getElementById('sh-btn').style.backgroundImage = 'none';
	}
	else {
		document.getElementById('sh-ctt').className = 'dsp-non';
		document.getElementById('sh-btn').style.backgroundImage = '';
	}
}

addEvt(window, 'load', function() {
	addEvt(document.getElementById('sh-btn'), 'click', function() {
		showHide();
	});
	addEvt(document.getElementById('sh-lnk'), 'click', function() {
		showHide();
	});
	
	mdf_cls('cnr', 'remove', 'vsb-hid');
});