shpsCmm.domReady().then(function() {
	var scb = seCmmBatch;
	
	function ldFromDb(se) {
		return shpsCmm.createAjax('post', 'get_defs.php', 'se='+se, 'json').then(function(xhr) {
			return xhr.response;
		});
	}
	
	function submitChange(evt) {
		evt.preventDefault();
		
		shpsCmm.createAjax('post', 'update_def.php', new FormData(this));
	}
	
	Promise.all([
		scb.ldTkrs('SHSE'),
		scb.ldTkrs('SZSE')
	]).then(function() {
		scb.btn.disabled = true;
		
		//start loading def from db
		return Promise.all([
			ldFromDb('shse'),
			ldFromDb('szse')
		]);
	}).then(function(defsObjsArr) {
		var shseObj = defsObjsArr[0];
		var szseObj = defsObjsArr[1];
		
		var tkrRows = scb.getTkrRows();
		
		forEachNodeItem(tkrRows, function(row) {
			var def;
			
			var defRow = document.createElement('tr');
			
			//get tkr
			var tkr = /\s*(\d+)\s*/.exec(row.children[0].textContent)[1];

			if (shseObj[tkr] || szseObj[tkr]) {
				var se;
				
				if (shseObj[tkr]) {
					def = shseObj[tkr];
					se = 'shse';
				} else {
					def = szseObj[tkr];
					se = 'szse';
				}
				
				for (var i = 2; i < scb.hdrCells.length; i++) {
					var td = document.createElement('td');
					
					var defName = scb.defIdxs[i];
					var defValue = def[defName];
					
					//pass data to be styled
					scb.style(td, defName, defValue);
					
					if ((defName == 'car') || (defName == 'cc')) {
						td.innerHTML = '<form method="post">\
							<input type="hidden" name="se" value="'+se+'"/><input type="hidden" name="tkr" value="'+tkr+'"/><input type="hidden" name="def_name" value="'+defName+'"/>\
							<input type="text" name="def_value" value="'+defValue+'"/><button type="submit">Submit</button>\
						</form>';
						
						td.getElementsByTagName('form')[0].addEventListener('submit', submitChange);
					}
					
					defRow.appendChild(td);
				}
			}

			scb.body.appendChild(defRow);
		});
	});
});