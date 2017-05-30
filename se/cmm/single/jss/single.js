const seSingle = {};

(function() {
	const dataScriptP = shpsCmm.extFileMgr.lnked('script', '/se/cmm/jss/se_data.js');

	const ss = seSingle;

	//to initialize, the ajax url must be provided
	ss.init = function(url) {
		ss.url = url;

		shpsCmm.domMgr.domReady().then(function() {
			const irCnr = document.getElementById('ir-cnr');

			//get data structure
			ss.defIdxs = [];

			ss.rows = document.getElementsByTagName('table')[0].getElementsByTagName('tr');

			Array.prototype.forEach.call(ss.rows, function(tr, idx) {
				ss.defIdxs[idx] = tr.children[0].dataset.acro;
			});

			const form = document.getElementById('ticker-form');

			const msgCnr = document.getElementById('msg-cnr');

			form.addEventListener('submit', function(evt) {
				evt.preventDefault();

				msgCnr.textContent = 'Running...';
				msgCnr.dataset.state = 'running';

				Array.prototype.forEach.call(ss.rows, function(tr) {
					tr.children[1].textContent = '';
				});

				const fd = new FormData(form);

				shpsCmm.ajaxMgr.createAjax('POST', ss.url, fd, 'json', undefined, undefined, true).then(function(xhr) {
					//xhr.response is the object containing the defs
					const def = xhr.response;

					dataScriptP.then(function() {
						Array.prototype.forEach.call(ss.rows, function(tr, idx) {
							const td = tr.children[1];

							const defName = ss.defIdxs[idx];
							const defValue = def[defName];

							//pass data to be styled
							seData.style(td, defName, defValue, def);
						});

						msgCnr.textContent = 'Done!';
						msgCnr.dataset.state = 'done';
					});
				});
			});
		});
	};
})();
