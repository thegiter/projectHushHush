//each page's js provides an hook function to cache the intro and exit functions to shpsAjax
//the name of this hook function must be set in the page's php and passed to shpsAjax through json
//for example, the hook for homepage would be homepage, and stored in shpsAjax.hooks['homepage']
//shpsAjax.hooks['homepage'] is then defined in the hp.js
//'homepage' must be defined as array['hook'] in the php
(function() {
	var cnr_id = 'abt-bd';
	var wpr_id = 'abt-pgs-wpr';
	
	var iCM = shpsCmm.iCMgr;
	
	var teoCnr = new ttlElmOnload(cnr_id);

	iCM.getObjUrl('/about/imgs/bg_pic.png', 'img').then(function(ouObj) {
		teoCnr.addImgs(ouObj.objUrl, ['']);
	
		teoCnr.useDefault = false;
	
		teoCnr.start();
	}, function() {
		console.log('get bg_pic obj url rejected, bg_pic does not exist in indexed db.');
	});
	
	shpsAjax.hooks['about'] = function(url) {
		shpsAjax.pgMgr.cache[url].obj = {};
		
		var pg = shpsAjax.pgMgr.cache[url].obj;
		
		pg.lded = false;
		
		var shps;
		var sh;
		var cnr;
		var wpr;
		var pgs;
		
		function rszScrlUd() {
			//because each page is moved back 100%, but the left one does not affect scroll width
			//but the right one does
			//since each page is same as clientWidth, and there are 2 pages
			//and we just want to scroll to the middle, we use a simplified version of calculation
			wpr.scrollLeft = wpr.clientWidth / 2;
		}
		
		//the intro function assumes that resources are loaded. which is checked with onload function
		//if you do not check to see resources are loaded first, intro function will cause error if the required resources are not present
		pg.intro = function(argObj) {
			cnr.addEventListener('transitionend', function fadeinEnd(evt) {
				if ((evt.target != this) || (evt.propertyName != 'opacity') || (getStyle(this, 'opacity') != 1)) {
					return false;
				}
				
				this.removeEventListener(evt.type, fadeinEnd);
				
				requestAnimationFrame(function() {
					forEachNodeItem(pgs, function(pg) {
						pg.classList.remove('dsp-non');
					});
					
	//				shps.offsetHeight;
	//				sh.offsetHeight;
					
					requestAnimationFrame(function() {
						//when you set overflow to hidden, you can still scroll element with js
						//as opppsed to when you leave overflow with auto
						//the content overflows, therefore there is no scrolling
						//and obviously with scroll, both user and js can scroll element
						//in this case, the initial scroll position is not correct, and so we set scroll to correct init position
						rszScrlUd();
						
						requestAnimationFrame(function() {
							forEachNodeItem(pgs, function(pg) {
								pg.classList.remove('init');
							});
							
							if (argObj && argObj.cb && (argObj.cbTiming == 'end')) {
								argObj.cb();
							}
						});						
					});
				});
			});
			
			requestAnimationFrame(function() {
				cnr.classList.remove('dsp-non');
				
				requestAnimationFrame(function() {
					cnr.classList.remove('opa-0');
				});
			});
			//the operator is used to open and not directly using cpnList, because operator needs to keep track of opened cpns
//			shpsAjax.cpnMgr.operator.open('bg_basic', bg_argObj);
		};
		
		var scrlDur = .2;//sec

		//exit is only trigger at swapping if there is cpnsNdd that is closed
		pg.exit = function(argObj) {
			var mid = wpr.clientWidth / 2;
			
			function pgsNon() {
				shps.classList.add('dsp-non');
				sh.classList.add('dsp-non');
				
				cnr.classList.add('opa-0');
			}
			
			if (argObj && argObj.instant) {
				//add display none to diable transitions
				cnr.classList.add('dsp-non');
				
				pgsNon();
			} else {
				if (wpr.scrollLeft != mid) {
					transScroll(wpr, mid, true, scrlDur);//true to horizontal
				}
				
				shps.addEventListener('transitionend', function translatexEnd(evt) {
					if ((evt.target != this) || (evt.propertyName != 'transform')) {
						return false;
					}
					
					shps.removeEventListener(evt.type, translatexEnd);

					if (argObj && argObj.cb && (argObj.cbTiming == 'end')) {
						cnr.addEventListener('transitionend', function fadeoutEnd(evt) {
							if ((evt.target != this) || (evt.propertyName != 'opacity') || (getStyle(this, 'opacity') != 0)) {
								return false;
							}
							
							cnr.removeEventListener(evt.type, fadeoutEnd);
							
							argObj.cb();
						});
					}
					
					pgsNon();
				});
			}

			forEachNodeItem(pgs, function(pg) {
				pg.classList.add('skewed');
				pg.classList.add('init');
			});
		};
		
		var lm = shpsAjax.lnkMgr;
		
		var open = false;
		var crtHash;
		var crtPg;
		var swipeClick = false;
		
		function scrlToElm(elm, cb) {
			if (wpr.scrollLeft != elm.offsetLeft) {
				transScroll(wpr, elm.offsetLeft, true, scrlDur, cb);//true to horizontal
			}
		}
		
		function emptyScHdlr() {
		}
		
		pg.hashChange = function(hash) {
			var regEx = /^#([^#]+.*)/;//	this regex means: # at beginning, then 1 or more non # character, then 0 or more of anything

			//test and if found store in var, else store null
			hash = regEx.exec(hash);

			//if not found exec returns null, not valid ajax url, do nothing
			if (hash !== null) {
				hash = hash[1];
				
				if (hash == crtHash) {
					return true;
				}
				
				//find out which element to scroll to
				//find out list of elms associated with this url from lnkmgr
				//then cross reference the elms with the pgs
				if (lm.registry['about/#'+hash]) {//if hash found within the registry, meaning valid hash
					//update crtHash
					crtHash = hash;
					
					forEachNodeItem(pgs, function(pg) {
						if (lm.registry['about/#'+hash].indexOf(pg) != -1) {//find the pg that corresponds to the hash in the registry
							//update
							crtPg = pg;
						}
					});
				} else {
					return false;
				}
				
				//if is swipeClick triggered hashChange, we update crtHash and pg, then do nothing and let css take over
				if (swipeClick) {
					return swipeClick = false;
				}
			} else {
				if (open) {
					open = false;
					
					crtHash = null;
					crtPg = null;
					
					//disable swipe click
					shpsCmm.swipeClick.rmv(wpr, emptyScHdlr);

					//disable scroll snap points
					wpr.classList.remove('scrl-x');
						
					//scroll back to middle, then reenable rszScrlUd
					transScroll(wpr, (wpr.scrollWidth - wpr.clientWidth) / 2, true, scrlDur, function() {
						optElmRszLsnr.add(wpr, rszScrlUd);
					});//true to horizontal
						
					//close all
					forEachNodeItem(pgs, function(elm) {
						//example of detecting specific type of transition (transform)
						/*elm.addEventListener('transitionend', function closeEnd(evt) {
							if ((evt.target != this) || (evt.propertyName != 'transform')) {
								return false;
							}
							
							this.removeEventListener('transitionend', closeEnd);
							
							//previously we made sure it's an transform transition
							//now we check that this transform transition is for closing by checking the presense of skewed class
							//a skewed elm, is a closed elm
							if (this.classList.contains('skewed')) {
								this.classList.remove('on-top');
							}
						});*/
						
						elm.classList.add('skewed');
					});
				}
				
				return true;
			}
			
			//if is open
			if (open) {
				//scroll to elm
				scrlToElm(crtPg);
			} else {
				open = true;
				
				//disable rszScrlUd
				optElmRszLsnr.rmv(wpr, rszScrlUd);
				
				//open all, scroll to elm, then enable scroll to user
				forEachNodeItem(pgs, function(pg) {
					pg.classList.remove('skewed');
				});
				
				//scroll to elm, then enable scroll snap points
				scrlToElm(crtPg, function() {
					//only enable swipeClick when in the open state
					//when closed, there can still be a lot of scrolling happening,
					//and we do not want to trigger swipeclick accidentally
					shpsCmm.swipeClick.add(wpr, function() {
						return {h: wpr.getElementsByTagName('a').length};
					}, function(idxsObj) {
						var elm = wpr.getElementsByTagName('a')[idxsObj.h];
						
						//if the elm to swipe click is not crtHash
						if (elm != crtPg) {
							//we disable transScroll and let css scroll snap point take over
							swipeClick = true;
						}
						
						return elm;
					}, emptyScHdlr);
					
					wpr.classList.add('scrl-x');
				});
			}
		};
		
		//so that the scroll position would update when swapin
		pg.swapOut = function() {
			crtHash = null;
		};
		
		pg.onload = function(cb) {
			function init() {
				cnr = document.getElementById(cnr_id);
				wpr = document.getElementById(wpr_id);
				pgs = wpr.getElementsByTagName('a');
				
				shps = document.getElementById('abt-shps-cnr');
				sh = document.getElementById('abt-sh-cnr');
				
				forEachNodeItem(pgs, function(elm) {
					lm.register(elm);
				});
				
				//init resize scroll update
				optElmRszLsnr.add(wpr, rszScrlUd);
				
				cb();
			}
			
			if (teoCnr.lded) {
				init();
			} else {
				teoCnr.callback = init;
			}
		};
	};
})();