shpsCmm.domReady().then(function() {
	var form = document.getElementById('se-form');
	var iframe = document.getElementById('se-iframe');
	
	var ifWdw = iframe.contentWindow;
	
	var msgCnr = document.getElementById('msg-cnr');
	
	var se;
	
	form.addEventListener('submit', function(evt) {
		evt.preventDefault();
		
		var fd = new FormData(form);
		
		switch (se = fd.get('se')) {
			case 'SHSE':
				ifWdw.location.href = 'ld_pg.php?se='+se+'&cursor='+fd.get('cursor');
				
				break;
			case 'SZSE':
				ifWdw.location.href = 'tl/szse.html';
				
				break;
			default:
				return false;
		}
	});
	
	var btn = document.getElementById('sp-btn');
	var trCntr = 0; var tkrCntr = 0;
	function recuringGetTkrs(cnr, tkrsArr) {//for when there are too many tickers
		//now, we loop through each table row, starting from the second row, and construct our data
		var theRows = cnr.getElementsByTagName('tr');

		for (var i = 0; i < theRows.length; i++) {
			//for each row, the first cell contains ticker symbol, the 7th contains company name
			var ticker = {};
			
			var cells = theRows[i].getElementsByTagName('td');

			var matches = /\s*(\d+)\s*/.exec(cells[0].textContent);
			
			var name = cells[6].textContent;
			
			if (matches && /[\S]+/.exec(name)) {//if found digits and name is not empty
				ticker.ticker = matches[1];
				ticker.name = name;
				
				tkrsArr.push(ticker);
			}
			
			cnr.removeChild(theRows[i]);
		}
		
		if (cnr.getElementsByTagName('tr').length > 0) {
			recuringGetTkrs(cnr, tkrsArr);
		}
	}
	
	btn.addEventListener('click', function() {
		var ifDoc = ifWdw.document;
		var tickers = [];
		
		switch (se) {
			case 'SHSE':
				//get all tables
				var tbls = ifDoc.getElementsByTagName('table');
				
				//loop through to find the right table
				//the right table has the first table row containing the headings
				var theTbl;
				
				forEachNodeItem(tbls, function(tbl) {
					if (tbl.getElementsByTagName('tr')[0].getElementsByTagName('td')[0].textContent.search('证券代码') != -1) {
						theTbl = tbl;
					}
				});
				
				//now, we loop through each table row, starting from the second row, and construct our data
				var theRows = theTbl.getElementsByTagName('tr');
				
				for (var i = 1; i < theRows.length; i++) {
					//for each row, the first cell contains a link of ticker symbol, the second contains company name
					var ticker = {};
					
					var cells = theRows[i].getElementsByTagName('td');
					
					ticker.ticker = /\s*(\d+)\s*/.exec(cells[0].getElementsByTagName('a')[0].textContent)[1];
					ticker.name = cells[1].textContent;
					
					tickers.push(ticker);
				}
				
				break;
			case 'SZSE':
				//get the table
				var theTbl = ifDoc.getElementsByTagName('table')[0];
				
				recuringGetTkrs(theTbl.getElementsByTagName('tbody')[0], tickers);
				
				break;
			default:
				return false;
		}

		msgCnr.textContent = 'Uploading...';
				
		//tickers now contain all the ticker data for the page
		//we upload the data to server
		shpsCmm.createAjax('post', 'save_to_db.php', 'tkrs_json='+JSON.stringify(tickers)+'&se='+se).then(function(xhr) {
			//check when server respond if successful
			if (xhr.responseText == 'success') {
				msgCnr.textContent = 'Done!';
			} else {
				msgCnr.textContent = 'Failed. Response: '+xhr.responseText;
			}
		});
	});
});