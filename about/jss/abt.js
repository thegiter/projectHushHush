//each page's js provides an hook function to cache the intro and exit functions to shpsAjax
//the name of this hook function must be set in the page's php and passed to shpsAjax through json
//for example, the hook for homepage would be homepage, and stored in shpsAjax.hooks['homepage']
//shpsAjax.hooks['homepage'] is then defined in the hp.js
//'homepage' must be defined as array['hook'] in the php
(function() {
	var cnr_id = 'abt-bd';
							
	var teoCnr = new ttlElmOnload(cnr_id);
	
	teoCnr.addImgs('/about/imgs//*replace*/.png', ['bg_pic']);
	
	teoCnr.useDefault = false;
	
	teoCnr.start();
	
	shpsAjax.hooks['about'] = function(url) {
		shpsAjax.pgMgr.cache[url].obj = {};
		
		var pg = shpsAjax.pgMgr.cache[url].obj;
		
		pg.lded = false;
		
		var shps;
		var sh;
		var cnr;
		
		//the intro function assumes that resources are loaded. which is checked with onload function
		//if you do not check to see resources are loaded first, intro function will cause error if the required resources are not present
		pg.intro = function(argObj) {
			cnr.addEventListener('transitionend', function fadeinEnd(evt) {
				if ((evt.target != this) || (evt.propertyName != 'opacity')) {
					return false;
				}
				
				this.removeEventListener(evt.type, fadeinEnd);

				if (getStyle(this, 'opacity') != 1) {
					return false;
				}
				
				requestAnimationFrame(function() {
					shps.classList.remove('dsp-non');
					sh.classList.remove('dsp-non');
					
	//				shps.offsetHeight;
	//				sh.offsetHeight;
					
					requestAnimationFrame(function() {
						shps.classList.remove('init');
						sh.classList.remove('init');
					});
				});
			});
			
			cnr.classList.remove('opa-0');
			//the operator is used to open and not directly using cpnList, because operator needs to keep track of opened cpns
//			shpsAjax.cpnMgr.operator.open('bg_basic', bg_argObj);
		};
		pg.exit = function() {
			//destroy auto triggered functions
			//remove window on click
		};
		
		var lastElm = null;
		
		pg.hashChange = function(hash) {
			var regEx = /^#([^#]+.*)/;//	this regex means: # at beginning, then 1 or more non # character, then 0 or more of anything

			//test and if found store in var
			hash = regEx.exec(hash);

			if (lastElm != null) {
				lastElm.addEventListener('transitionend', function closeEnd(evt) {
					if ((evt.target != this) || (evt.propertyName != 'transform')) {
						return false;
					}
					
					this.removeEventListener('transitionend', closeEnd);
					
					//previouse we made sure it's an transform transition
					//now we check that this transform transition is for closing by checking the presense of skewed class
					//a skewed elm, is a closed elm
					if (this.classList.contains('skewed')) {
						this.classList.remove('on-top');
					}
				});
				
				lastElm.classList.add('skewed');
				
				//finished closing lastElm, reset lastElm
				lastElm = null;
			}
			
			//if not found exec returns null, not valid ajax url, do nothing
			if (hash !== null) {
				hash = hash[1];
				
				switch(hash) {
					case 'の弑す魂の_ps':
						lastElm = shps;
						
						shps.classList.remove('skewed');
						shps.classList.add('on-top');
						
						break;
					case 'の弑す魂の':
						lastElm = sh;
						
						sh.classList.remove('skewed');
						sh.classList.add('on-top');
						
						break;
					default:
				}
			}
		};
		pg.onload = function(cb) {
			function init() {
				cnr = document.getElementById(cnr_id);
				
				shps = document.getElementById('abt-shps-cnr');
				sh = document.getElementById('abt-sh-cnr');
				
				shpsAjax.lnkMgr.register(shps);
				shpsAjax.lnkMgr.register(sh);
				
				cb();
			}
			
			if (teoCnr.lded) {
				init();
			}
			else {
				teoCnr.callback = init;
			}
		};
	}
})();