//each page's js provides an hook function to cache the intro and exit functions to shpsAjax
//the name of this hook function must be set in the page's php and passed to shpsAjax through json
//for example, the hook for homepage would be homepage, and stored in shpsAjax.hooks['homepage']
//shpsAjax.hooks['homepage'] is then defined in the hp.js
//'homepage' must be defined as array['hook'] in the php
(function() {
	var id = 'bg-tlight';
	//start total element onload
	var teo = new ttlElmOnload(id);
	
	teo.addCss('/shared/bgs/tlight/csss/bg.css');
	teo.addImgs('/shared/bgs/tlight/imgs//*replace*/.png', ['bg_ptn']);
	
	teo.useDefault = false;
	
	teo.start();
	
	shpsAjax.hooks['bg_tlight'] = function(cpnName) {//component name is used to uniquely identify the component
		var cpn = shpsAjax.cpnList[cpnName].obj = {};
		
		var state = 'closed';
		
		var elm;
		
		var lgt_tl;
		var lgt_tr;
		var lgt_btm;
		
		//the intro function assumes that resources are loaded. which is checked with onload function
		//if you do not check to see resources are loaded first, intro function will cause error if the required resources are not present
		cpn.intro = function(argObj) {//callback is optional, but must have a timing that defines when callback happens
			switch (state) {
				case 'closed':
					state = 'opening';
					
					elm.addEventListener('transitionend', function(evt) {
						evt.target.removeEventListener(evt.type, arguments.callee);	//self removing eventListener function
						
						state = 'opened';
						
						if (argObj && argObj.cb && argObj.cbTiming == 'end') {
							argObj.cb();
						}
/*tmp disabled due to performance cost with blend mode and 3d transform
						lgt_tl.classList.remove('dsp-non');
						
						setTimeout(function() {
							lgt_tr.classList.remove('dsp-non');
							
							setTimeout(function() {
								lgt_btm.classList.remove('dsp-non');
							}, 1000);
						}, 1000);*/
					}, false);

					mdf_cls(elm, 'remove', 'opa-0');
						
					if (argObj && argObj.cb && ((argObj.cbTiming == 'start') || (argObj.cbTiming == 'during'))) {
						argObj.cb();
					}
					
					break;
				case 'opening':
					if (argObj && argObj.cb) {
						if (argObj.cbTiming == 'end') {
							elm.addEventListener('transitionend', function introEnd(evt) {
								evt.target.removeEventListener(evt.type, introEnd);	//self removing eventListener function
							
								argObj.cb();
							}, false);
						} else if (argObj.cbTiming == 'during') {
							argObj.cb();
						}
					}
				
					break;
				case 'opened':
					if (argObj && argObj.cb && argObj.cbTiming == 'end') {
						argObj.cb();
					}
			}
		};
		
		cpn.exit = function() {
			state = 'closed';
			
			mdf_cls(elm, 'add', 'opa-0');
/*tmp disabled due to perfomance cost with blend mode and 3d transform
			lgt_tl.classList.add('dsp-non');
			lgt_tr.classList.add('dsp-non');
			lgt_btm.classList.add('dsp-non');*/
		};
		
		cpn.lded = false;
		
		cpn.onload = function(cb) {// cb: callback
			function init() {
				elm = document.getElementById(id);
				
				var lgts = elm.children;
				
				lgt_tl = lgts[0];
				lgt_tr = lgts[1];
				lgt_btm = lgts[2];
				
				cb();
			}

			if (teo.lded) {
				init();
			}
			else {
				teo.callback = init;
			}
		};
	};
})();