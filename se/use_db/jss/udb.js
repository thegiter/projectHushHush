(function() {
	const dataScriptP = shpsCmm.extFileMgr.lnked('script', '/se/cmm/jss/se_data.js');

	shpsCmm.domMgr.domReady().then(function() {
		const scb = seCmmBatch;

		function ldFromDb(se) {
			return shpsCmm.ajaxMgr.createAjax('post', 'get_defs.php', 'se='+se, 'json').then(function(xhr) {
				return xhr.response;
			});
		}

		function updateRow(row, se, tkr, def) {
			dataScriptP.then(function() {
				for (let i = 2; i < scb.hdrCells.length; i++) {
					var td = document.createElement('td');

					var defName = scb.defIdxs[i];
					var defValue = def[defName];

					//pass data to be styled
					seData.style(td, defName, defValue, def);

					if ((defName == 'car') || (defName == 'cc') || (defName == 'glbRank')) {
						td.innerHTML = '<form method="post">\
							<input type="hidden" name="se" value="'+se+'"/><input type="hidden" name="tkr" value="'+tkr+'"/><input type="hidden" name="def_name" value="'+defName+'"/>\
							<input type="text" name="def_value" value="'+defValue+'"/><button type="submit">Submit</button>\
						</form>';

						td.getElementsByTagName('form')[0].addEventListener('submit', submitChange);
					}

					row.appendChild(td);
				}
			});
		}

		function submitChange(evt) {
			evt.preventDefault();

			scb.tMsgCnrs.textContent = '';

			const fd = new FormData(this);
			const se = fd.get('se');
			const tkr = fd.get('tkr');

			shpsCmm.ajaxMgr.createAjax('post', 'update_var.php', fd, 'json', undefined, undefined, true).then(function(xhr) {
				//after receiving updates on user var, it attempts to re-siphon the def
				//however, due to network issues, the attempt may fail
				//if succeed, we update the data
				if (xhr.response) {console.log(evt.target, evt.target.parentNode, evt.target.parentNode.parentNode);
					const row = evt.target.parentNode.parentNode;

					//reset row
					row.innerHTML = '';

					updateRow(row, se, tkr, xhr.response);
				} else {//display error
					scb.tMsgCnrs.textContent = 'siphon error.';
				}
			});
		}

		Promise.all([
			scb.ldTkrs('NYSE'),
			scb.ldTkrs('Nasdaq')
		]).then(function() {
			scb.btn.disabled = true;

			//start loading def from db
			return Promise.all([
				ldFromDb('nyse'),
				ldFromDb('nasdaq')
			]);
		}).then(function(defsObjsArr) {
			const NYSE_OBJ = defsObjsArr[0],
						NAS_OBJ = defsObjsArr[1];

			const TKR_ROWS = scb.getTkrRows();

			//to fix performance issues, we will add each row to document fragment first
			//then move the entire fragment to the webpage
			const DOC_FRAG = new DocumentFragment();

			Array.prototype.forEach.call(TKR_ROWS, function(row) {
				const DEF_ROW = document.createElement('tr');

				//get tkr
				const TKR = /\s*([A-Za-z\.]+)\s*/.exec(row.children[0].textContent)[1];

				if (NYSE_OBJ[TKR] || NAS_OBJ[TKR]) {
					let def, se;

					if (NYSE_OBJ[TKR]) {
						def = NYSE_OBJ[TKR];
						se = 'NYSE';
					} else {
						def = NAS_OBJ[TKR];
						se = 'Nasdaq';
					}

					updateRow(DEF_ROW, se, TKR, def);
				}

				//add to doc frag
				DOC_FRAG.appendChild(DEF_ROW);
			});

			//add doc frag to dom
			scb.body.appendChild(DOC_FRAG);
		});
	});
})();
