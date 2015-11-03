var seSingle = {};

(function() {
	var ss = seSingle;
	
	//to initialize, the inflation rate and ajax url must be provided
	ss.init = function(ir, url) {
		ss.ir = ir;
		ss.url = url;
		
		shpsCmm.domReady().then(function() {
			var irCnr = document.getElementById('ir-cnr');

			irCnr.textContent = ss.ir * 100+'%';
			
			var form = document.getElementById('ticker-form');
			
			var msgCnr = document.getElementById('msg-cnr');
			
			var lastDef;
			
			form.addEventListener('submit', function(evt) {
				evt.preventDefault();
				
				msgCnr.textContent = 'Running...';
				msgCnr.dataset.state = 'running';
				
				forEachObjProp(lastDef, function(value, pName) {
					if (pName == 'interest') {
						pName = 'int';
					}
					
					document.getElementById(pName+'-cnr').textContent = '';
				});
				
				var fd = new FormData(form);
				
				fd.append('ir', ss.ir);
				
				shpsCmm.createAjax('POST', ss.url, fd, 'json', undefined, undefined, true).then(function(xhr) {console.log(xhr.response);
					//xhr.response is the object containing the defs
					var def = lastDef = xhr.response;
					
					forEachObjProp(def, function(value, pName) {
						if (pName == 'interest') {
							pName = 'int';
						}
						
						var cnr = document.getElementById(pName+'-cnr');
						
						switch (pName) {
							case 'aomg':
								if (value >= 0) {
									cnr.dataset.positive = '1';
								} else {
									cnr.dataset.positive = '0';
								}
								
								break;
							case 'pc':
								if (value != 1) {
									cnr.dataset.negative = 1;
								} else {
									cnr.dataset.negative = 0;
								}
								
								break;
							case 'cpivr':
								if (value <= -.25) {
									cnr.dataset.indicator = 'buy';
								} else {
									cnr.dataset.indicator = '';
								}
								
								value = value * 100+'%';
								
								break;
							case 'cpcvr':
								if (value > .2) {
									cnr.dataset.indicator = 'sell';
								} else {
									cnr.dataset.indicator = '';
								}
							case 'pow':
								value = value * 100+'%';
								
								break;
							default:
								break;
						}

						cnr.textContent = value;
					});
					
					msgCnr.textContent = 'Done!';
					msgCnr.dataset.state = 'done';
				})
			});
		});
	};
})();