shpsCmm.domReady().then(function() {
	var scb = seCmmBatch;
	
	var maxRetrys = 5;
	
	var numThreads = 5;
	var threadRows = [];
	
	function siphonThread(threadRowsObj, threadNum) {
		var cntr = threadRowsObj.s - 2;

		var retrys = 0;
		
		function getTicker() {
			//first child of row is the cell with the ticker
			return /\s*(\d+)\s*/.exec(scb.tkrRows[cntr].children[0].textContent)[1];
		};
		
		function siphon(tkr) {
			shpsCmm.createAjax('post', '/se/siphon/def/db/batch/siphon.php', 'se='+scb.se+'&tkr='+tkr, 'json').then(function(xhr) {
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
						
						scb.body.children[cntr].appendChild(td);
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
			cntr++;
			
			if (cntr >= threadRowsObj.e) {
				scb.tMsgCnrs[threadNum].textContent = 'Thread '+threadNum+' last stock siphoned, siphon complete!';
				
				return false;
			}
			
			if (!scb.tkrRows[cntr].children[1].textContent) {
				siphonNext();
			} else {
				siphon(getTicker());
			}
		}
		
		scb.tMsgCnrs[threadNum].textContent = 'siphoning...';
		
		siphonNext();
	}

	function createDelayedThread(threadIdx) {
		//choose a random delay
		var delay = getRandomInt(900, 3600);
		
		var msgObj = scb.tMsgCnrs[threadIdx];
		
		//display delay
		var intId = setInterval(function() {
			msgObj.textContent = 'starting in '+(--delay)+' secs.';
		}, 1000);
		
		var trObj = threadRows[threadIdx];
		
		setTimeout(function() {
			clearInterval(intId);
			
			new siphonThread(trObj, threadIdx);
		}, delay * 1000);
	}
	
	function startSiphoning() {
		//create threads
		//assign rows to each thread
		var numRowsPerThread = Math.floor(scb.maxRows / numThreads);
		
		for (var c = 1; c <= numThreads; c++) {
			threadRows.push({
				s: (c - 1) * numRowsPerThread + 1,
				e: c * numRowsPerThread
			});
			
			if (c == 1) {
				var more;
				
				if ((more = scb.maxRows - numThreads * numRowsPerThread) > 0) {
					threadRows[c - 1].e += more;
				}
			}
		}
		
		//choose a random thread to start and then
		//for each of the rest threads, wait random time and start
		//to avoid suspision
		var randThreadIdx = getRandomInt(0, 5);
		
		new siphonThread(threadRows[randThreadIdx], randThreadIdx);
		
		for (var i = 0; i < threadRows.length; i++) {
			if (i !== randThreadIdx) {
				createDelayedThread(i);
			}
		}
	}
	
	//start siphoning one by one
	//first we will read the table rows, and keep an counter
	//we will read its ticker, and pass it to the server
	scb.setBtnHdlr(function(evt) {
		cntr = -1;
		retrys = 0;
		
		startSiphoning();	
	});
});