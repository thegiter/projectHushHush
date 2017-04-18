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

			var fd = new FormData(this);
			var se = fd.get('se');
			var tkr = fd.get('tkr');

			shpsCmm.ajaxMgr.createAjax('post', 'update_var.php', fd, 'json', undefined, undefined, true).then(function(xhr) {
				//after receiving updates on user var, it attempts to re-siphon the def
				//however, due to network issues, the attempt may fail
				//if succeed, we update the data
				if (xhr.response) {
					var row = evt.target.parentNode.parentNode;

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
			var nyseObj = defsObjsArr[0];
			var nasObj = defsObjsArr[1];

			var tkrRows = scb.getTkrRows();

			Array.prototype.forEach.call(tkrRows, function(row) {
				let def;

				var defRow = document.createElement('tr');

				//get tkr
				var tkr = /\s*([A-Za-z\.]+)\s*/.exec(row.children[0].textContent)[1];

				if (nyseObj[tkr] || nasObj[tkr]) {
					var se;

					if (nyseObj[tkr]) {
						def = nyseObj[tkr];
						se = 'NYSE';
					} else {
						def = nasObj[tkr];
						se = 'Nasdaq';
					}

					updateRow(defRow, se, tkr, def);
				}

				scb.body.appendChild(defRow);
			});
		});
	});
})();
