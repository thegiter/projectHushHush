var seSingle = {};

(function() {
	var dataScriptP = shpsCmm.lnkExtFile.lnked('script', '/se/cmm/jss/se_data.js');
	
	var ss = seSingle;
	
	//to initialize, the inflation rate and ajax url must be provided
	ss.init = function(ir, url) {
		ss.ir = ir;
		ss.url = url;
		
		shpsCmm.domReady().then(function() {
			var irCnr = document.getElementById('ir-cnr');

			irCnr.textContent = ss.ir * 100+'%';
			
			//get data structure
			ss.defIdxs = [];
			
			ss.rows = document.getElementsByTagName('table')[0].getElementsByTagName('tr');
			
			forEachNodeItem(ss.rows, function(tr, idx) {
				ss.defIdxs[idx] = tr.children[0].dataset.acro;
			});
			
			var form = document.getElementById('ticker-form');
			
			var msgCnr = document.getElementById('msg-cnr');
			
			form.addEventListener('submit', function(evt) {
				evt.preventDefault();
				
				msgCnr.textContent = 'Running...';
				msgCnr.dataset.state = 'running';
				
				forEachNodeItem(ss.rows, function(tr) {
					tr.children[1].textContent = '';
				});

				var fd = new FormData(form);
				
				fd.append('ir', ss.ir);
				
				shpsCmm.createAjax('POST', ss.url, fd, 'json', undefined, undefined, true).then(function(xhr) {
					//xhr.response is the object containing the defs
					var def = xhr.response;
					
					dataScriptP.then(function() {
						forEachNodeItem(ss.rows, function(tr, idx) {
							var td = tr.children[1];
							
							var defName = ss.defIdxs[idx];
							var defValue = def[defName];
							
							//pass data to be styled
							seData.style(td, defName, defValue);
						}); 
						
						msgCnr.textContent = 'Done!';
						msgCnr.dataset.state = 'done';
					});
				});
			});
		});
	};
})();