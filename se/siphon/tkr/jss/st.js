shpsCmm.domReady().then(function() {
	const MAX_NUM_TKRS = 1000;

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
			case 'JSE':
				ifWdw.location.href = 'tl/jse.html';
				
				break;
			case 'NYSE':
				ifWdw.location.href = 'tl/nyse.html';
				
				break;
			case 'Nasdaq':
				ifWdw.location.href = 'tl/nasdaq.html';
				
				break;
			default:
				return false;
		}
	});
	
	var btn = document.getElementById('sp-btn');
	var trCntr = 0;
	var tkrCntr = 0;
	
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
	
	function upload(tickers, se, append) {
		msgCnr.textContent = 'Uploading...';

		if (tickers.length > MAX_NUM_TKRS) {
			var first = tickers.splice(0, MAX_NUM_TKRS);
			var rest = tickers;
			tickers = first;
		}

		var appendParam = '';
		
		if (append) {
			appendParam = '&append=append';
		}
		
		//tickers now contain all the ticker data for the page
		//we upload the data to server
		shpsCmm.createAjax('post', 'save_to_db.php', 'tkrs_json='+encodeURIComponent(JSON.stringify(tickers))+'&se='+se+appendParam).then(function(xhr) {
			//check when server respond if successful
			if (xhr.responseText == 'success') {
				msgCnr.textContent = 'Done!';
				
				if (rest) {
					upload(rest, se, true);
				}
			} else {
				msgCnr.textContent = 'Failed. Response: '+xhr.responseText;
			}
		});
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
				
				upload(tickers, se);
				
				break;
			case 'SZSE':
				//get the table
				var theTbl = ifDoc.getElementsByTagName('table')[0];
				
				recuringGetTkrs(theTbl.getElementsByTagName('tbody')[0], tickers);
				
				upload(tickers, se);
				
				break;
			case 'JSE':
				var trs = ifDoc.getElementsByTagName('table')[1].getElementsByTagName('tbody')[0].children;
				
				//loop through each row
				//first row is heading, start from 2nd row
				for (var i = 1; i < trs.length; i++) {
					var tr = trs[i];
					//first cell is name, second is tkr
					//if name is not like ZAdigits
					//create ticker
					var tkr = /\S+/.exec(tr.children[1].children[0].textContent)[0];
					var sName = tr.children[0].children[0].textContent;
					
					if ((tkr.length == 3) && !(/((ZA\d+)|\bPref\b|\bprf\b|\bprefs\b|\bNPL\b|REDEEMABLEPREF|\%|\bETF\b|\d(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)|(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)\d)/i.exec(sName))) {
						tickers.push({
							ticker: tkr,
							name: sName
						});				
					}
				}

				upload(tickers, se);
				
				break;
			case 'NYSE':
			case 'Nasdaq':
				var trs = ifDoc.getElementsByTagName('table')[0].getElementsByTagName('tbody')[0].children;

				//loop through each row
				//first row is heading, start from 2nd row
				for (var i = 1; i < trs.length; i++) {
					var tr = trs[i];
					//combine first cell and second cell html in each row, seperated by comma
					//symbol before the first comma is the ticker
					//before the second comma is the name inside double quotes
					//possible span in second comma, for the apostrophy symbol
					//create ticker

					var txt = tr.children[0].textContent;
					
					if (tr.children[1]) {
						txt += tr.children[1].textContent;
					}

					txt = txt.replace(/(?:\r\n|\r|\n)/g, ' ');

					matches = /^([A-Z\.]+),"([^"]+)"/.exec(txt);
					
					if (matches) {
						var sName = matches[2];

						if (sName.indexOf('Fund') == -1) {
							var tkr = matches[1];

							//select is a reserved word, therefore must be escaped
							sName = sName.replace(/\bselect/gi, 'ESCSelect');
							sName = sName.replace(/\bunion/gi, 'ESCUnion');
							
							tickers.push({
								ticker: tkr,
								name: sName
							});
						}						
					}
				}

				upload(tickers, se);
				
				break;
			default:
				return false;
		}
	});
});