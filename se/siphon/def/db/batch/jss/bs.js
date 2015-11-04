shpsCmm.domReady().then(function() {
	var scb = seCmmBatch;
	
	var maxRetrys = 5;
	
	var initNumThreads = 5;
	var additonalNumThreads = 15;

	var threadCnt = -1;
	
	var tkrRows = [];
	
	function siphonThread() {
		threadCnt++;
		
		var threadNum = threadCnt;
		
		var retrys = 0;
		var tkrRow;
		
		function getTicker() {
			//first child of row is the cell with the ticker
			return /\s*(\d+)\s*/.exec(tkrRow.children[0].textContent)[1];
		};
		
		function siphon(tkr) {
			var se = (tkr.charAt(0) == '6') ? 'SHSE' : 'SZSE';
			
			shpsCmm.createAjax('post', '/se/siphon/def/db/batch/siphon.php', 'se='+se+'&tkr='+tkr, 'json').then(function(xhr) {
				//determin if success, set retry cntr to 0
				//if success, update the table row, else retry
				if (xhr.response) {//xhr.response will be null if failed
					retrys = 0;
					
					for (var i = 2; i < scb.hdrCells.length; i++) {
						var td = document.createElement('td');
						
						var defName = scb.defIdxs[i];
						var defValue = xhr.response[defName];
						
						//pass data to be styled
						scb.style(td, defName, defValue);
						
						var rowIdx;
						
						//get the row index
						forEachNodeItem(scb.tkrRows, function(row, idx) {
							if (row == tkrRow) {
								rowIdx = idx;
							}
						});
						
						scb.body.children[rowIdx].appendChild(td);
					}
					
					//siphon next
					//to aoid suspision, we will wait for 5 secs
					scb.tMsgCnrs[threadNum].textContent = 'waiting...';
					
					setTimeout(function() {
						scb.tMsgCnrs[threadNum].textContent = 'siphoning...';
						
						siphonNext();
					}, getRandomInt(5000, 10000));
				} else {
					if (retrys < maxRetrys) {
						scb.tMsgCnrs[threadNum].textContent = 'attempt '+retrys+' failed. Retrying...';
						
						retrys++;
						
						siphon(tkr);
					} else {
						siphonNext();
						
						scb.tMsgCnrs[threadNum].textContent = 'max retrys reached. Ticker skipped.';
					}
				}
			});
		}
		
		function siphonNext() {
			//if no more rows, terminate
			if (tkrRows.length <= 0) {
				scb.tMsgCnrs[threadNum].textContent = 'Last stock siphoned, siphon complete!';
				
				return false;
			}
			
			//pick a random row
			var randIdx = getRandomInt(0, tkrRows.length);
			tkrRow = tkrRows[randIdx];
			
			//remove the row from the arr so other threads won't bother with it
			tkrRows.splice(randIdx, 1);
			
			//if does not have a name, the tkr is invalid, skip
			if (!/[\S]+/.exec(tkrRow.children[1].textContent)) {
				siphonNext();
			} else {
				siphon(getTicker());
			}
		}
		
		scb.tMsgCnrs[threadNum].textContent = 'siphoning...';
		
		siphonNext();
	}
	
	//min interval between threads is 5 min
	var minInt = 300;
	
	function createDelayedThread(threadIdx) {
		//choose a random delay
		var delay = getRandomInt(minInt * threadIdx, 2700);
		
		var msgObj = scb.tMsgCnrs[threadIdx];
		
		//display delay
		var intId = setInterval(function() {
			msgObj.textContent = 'starting in '+(--delay)+' secs.';
		}, 1000);
		
		setTimeout(function() {
			clearInterval(intId);
			
			new siphonThread();
		}, delay * 1000);
	}
	
	//start siphoning one by one
	//first we will read the table rows, and keep an counter
	//we will read its ticker, and pass it to the server
	scb.setBtnHdlr(function(evt) {
		//set a tmp copy of the tkr rows
		forEachNodeItem(scb.tkrRows, function(row) {
			tkrRows.push(row);
		});
		
		//start one thread, then set time out for additional threads over 45 min
		new siphonThread();
		
		//for each of the rest threads, wait random time and start
		//to avoid suspision
		for (var i = 1; i < initNumThreads; i++) {
			createDelayedThread(i);
		}
		
		//then wait for 1 hour and start additional threads
		setTimeout(function() {
			for (var c = 1; c <= 15; c++) {
				new siphonThread();
			}
		}, 60 * 60 * 1000);
	});
});