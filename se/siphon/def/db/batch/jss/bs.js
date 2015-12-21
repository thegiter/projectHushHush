(function() {
	var dataScriptP = shpsCmm.lnkExtFile.lnked('script', '/se/cmm/jss/se_data.js');
	
	shpsCmm.domReady().then(function() {
		var scb = seCmmBatch;
	
		var maxRetrys = 5;

		var numJsThreads = 0;//due to same domain policy, js thread wont work
		var initNumThreads = 5;
		var additionalNumThreads = 1;

		const maxThreads = 25;//server limits concurrent scripts to 30;
		
		var threadCnt = -1;
		
		var tkrRows = [];
		
		function siphonThread(js) {
			threadCnt++;
			
			var tStartTs = Date.now();
			
			var threadNum = threadCnt;
			
			scb.msgCnr.appendChild(document.createElement('span'));
			scb.tMsgCnrs = scb.msgCnr.children;
			
			var retrys = 0;
			var tkrRow;
			
			function getTicker() {
				//first child of row is the cell with the ticker
				return /\S+/.exec(tkrRow.children[0].textContent)[0];
			}
			
			function siphonEnd(def) {
				dataScriptP.then(function() {
					for (var i = 2; i < scb.hdrCells.length; i++) {
						var td = document.createElement('td');
						
						var defName = scb.defIdxs[i];
						var defValue = def[defName];
						
						//pass data to be styled
						seData.style(td, defName, defValue, def);
						
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
					//to avoid suspision, we will wait for 4 to 60 secs (time needed to complete one request)
					scb.tMsgCnrs[threadNum].textContent = 'waiting...';
					
					setTimeout(function() {
						scb.tMsgCnrs[threadNum].textContent = 'siphoning...';
						
						siphonNext();
					}, getRandomInt(4000, 60000));
				});
			}
			
			function siphon(tkr) {
				var se = (tkr.charAt(0) == '6') ? 'SHSE' : 'SZSE';
				
				if (js) {
					var oldAdvice;
					
					//get db car cc ir
					//if db, set to db values
					shpsCmm.createAjax('post', '/se/cmm/get_db_def_vars.php', 'se='+se+'&tkr='+tkr, 'json').then(function(varsObj) {
						oldAdvice = varsObj.oldAdvice;
						
						return js_siphoned(tkr, varsObj.car, varsObj.cc, varsObj.ir);
					}).then(function(def) {
						//upload to db
						return shpsCmm.createAjax('post', '/se/cmm/update_db_def.php', 'se='+se+'&tkr='+tkr+'&def='+JSON.stringify(def)+'&old_advice='+oldAdvice, 'json');
					}).then(function(xhr) {
						//siphon end operations
						siphonEnd(xhr.response);
					});
				} else {
					//pick a random subdomain
					var rand = getRandomInt(1, 3);
					
					//if there is no www., we simply add the sesrand and remove nothing
					var seurl = 'http://ses'+rand+'.'+window.location.hostname.replace('www.', '');
					
					shpsCmm.createAjax('post', seurl, 'se='+se+'&tkr='+tkr+'&ee25d6='+document.cookie.replace(/(?:(?:^|.*;\s*)ee25d6\s*\=\s*([^;]*).*$)|^.*$/, "$1"), 'json').then(function(xhr) {
						//determin if success, set retry cntr to 0
						//if success, update the table row, else retry
						if (xhr.response) {//xhr.response will be null if failed
							retrys = 0;
							
							siphonEnd(xhr.response);
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
			}
			
			function siphonNext() {
				//if no more rows, terminate
				if (tkrRows.length <= 0) {
					scb.tMsgCnrs[threadNum].classList.add('dsp-non');
					
					return false;
				}
				
				//if 30 min passed, the thread dulicates itself
				if (((Date.now() - tStartTs) >= (1000 * 60 * 30)) && (threadCnt < (maxThreads - 1))) {
					tStartTs = Date.now();
					
					new siphonThread(js);
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
		
		//min interval between threads is 3 min
		var minInt = 3 * 60;
		
		function createDelayedThread(threadIdx, delay, js) {
			var msgObj = scb.tMsgCnrs[threadIdx];
			
			//display delay
			var intId = setInterval(function() {
				msgObj.textContent = 'starting in '+(--delay)+' secs.';
			}, 1000);
			
			setTimeout(function() {
				clearInterval(intId);
				
				new siphonThread(js);
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
			
			//start one thread, then set time out for additional threads over 40 min
			new siphonThread();
			
			//for each of the rest threads, wait random time and start
			//to avoid suspision
			for (var i = 1; i < initNumThreads; i++) {
				//choose a random delay
				var delay = getRandomInt(minInt * i, 40 * 60);
				
				createDelayedThread(i, delay);
			}
			
			//then wait for 50 min and start additional threads
			setTimeout(function() {
				for (var c = 1; c <= additionalNumThreads; c++) {
					new siphonThread();
				}
			}, 50 * 60 * 1000);
			
			//after a short random delay, start first js thread, then gradually increase threads over 1 hour
			//choose a random delay
			var firstDelay = getRandomInt(minInt, 600);
			
			for (var i = 0; i < numJsThreads; i++) {
				if (i == 0) {
					createDelayedThread(initNumThreads, firstDelay, true);
					
					continue;
				}
				
				//choose a random delay
				var delay = getRandomInt(firstDelay + minInt * i, 3600);
				
				createDelayedThread(i + initNumThreads, delay, true);
			}
		});
	});
})();