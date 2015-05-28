function menuBtnModule(elm) {//elm is the btn element
	var bars = elm.children;
	
	elm.addEventListener('click', function() {
		if (elm.dataset.isOpenBtn == 'false') {
			mdf_cls(elm, 'add', 'menu-open-btn');
			mdf_cls(elm, 'remove', 'menu-close-btn');
			
			elm.offsetHeight; //flush css change
			
			//animate top and bottom bars back
			mdf_cls(bars[0], 'remove', 'to-right');
			mdf_cls(bars[2], 'remove', 'to-left');
			
			//fadein middle bar
			mdf_cls(bars[1], 'remove', 'opa-0');
			
			//set to true
			elm.dataset.isOpenBtn = 'true';
		}
		else{
			mdf_cls(elm, 'add', 'menu-close-btn');
			mdf_cls(elm, 'remove', 'menu-open-btn');
			
			elm.offsetHeight; //flush css change
			
			//animate top and bottom bars
			mdf_cls(bars[0], 'add', 'to-right');
			mdf_cls(bars[2], 'add', 'to-left');
			
			//fadeout middle bar
			mdf_cls(bars[1], 'add', 'opa-0');
			
			//set to false
			elm.dataset.isOpenBtn = 'false';
		}
	}, false);
};