var tools_elmId = 'tools-cnr';

(function() {
//fgCnr onload
	var ttlElm_fgCnr = new ttlElmOnload(tools_elmId);
	
	ttlElm_fgCnr.addScript('/shared/fg_cnr/jss/fg_cnr.js');	// always add script first so it can start loading asap, except after related css

	ttlElm_fgCnr.callback = function() {
		function toolsOnload_img() {													//images loaded, do this function
			var toolsCnr = document.getElementById(tools_elmId);
			
			mdf_cls(toolsCnr, 'remove', 'opa-0');										//fadein the element
		
			// wait for window onload is a bad idea especially on bad servers
			//so we do DOMContentLoaded
			domReadyDo(function() {
				function transEnd_flip(evt) {											//when element is flipped, do this function
					if (evt.propertyName == 'transform') {								//check to make sure is the flip transition
						toolsCnr.removeEventListener('transitionend', transEnd_flip);
					
						mdf_cls(toolsCnr, 'remove', 'tools-trans-delay');
						mdf_cls(toolsCnr, 'add', 'tools-trans-normal');
						
						mdf_cls('tools-wpr', 'add', 'tools-hover');
					}
				}
				
				toolsCnr.addEventListener('transitionend', transEnd_flip, false);		//listen to the flip
				
				mdf_cls(toolsCnr, 'add', 'tools-trans-init');
			});
		}

		if (fg_cnr_lded) {
			toolsOnload_img();
		}
		else {
			add_fct('fg_cnr', toolsOnload_img);
		}
	};

	ttlElm_fgCnr.start();
//end fgCnr onload
})();

//toolsModule is static class due to the fact that it is designed in the way that a page can only have 1 tools module.
//usage:
//	toolsModule.elmOnload(function() {}); inside which you use the methods and properties
//		
//	toolsModule.bindToTop(cnrElmOrId); where cnrElmOrId is either an elm node, or an id of an element, can be the window
function toolsModule() {
}

toolsModule.elmLded = false;

toolsModule.elmOnloadFcts = [function() {
	toolsModule.elmLded = true;
}];

toolsModule.elmOnload = function(fct) {
	if (toolsModule.elmLded) {
		fct();
	}
	else {
		toolsModule.elmOnloadFcts.push(fct);
	}
};

checkElmOnload(tools_elmId, function() {
	toolsModule.elmOnloadFcts.forEach(function(value) {
		value();
	});
});

//toTop methods for tools module
toolsModule.toTopOnload = function() {
};

toolsModule.bindToTop = function(cnrElmOrId) {
	toolsModule.toTopOnload = function() {
		toTopModule('tools-to-top-btn', cnrElmOrId);
	};
};

include('script', '/shared/to_top/jss/to_top.js', undefined, undefined, function() {	//include the script and bind the button to the container
	toolsModule.bindToTop = function(cnrElmOrId) {
		toTopModule('tools-to-top-btn', cnrElmOrId);
	};
	
	toolsModule.toTopOnload();
});
//end toolsModule class