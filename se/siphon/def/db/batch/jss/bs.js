(function() {
	var dataScriptP = shpsCmm.lnkExtFile.lnked('script', '/se/cmm/jss/se_data.js');
	
	//check for refresh, and set appropriate settings
	var refreshParam = '';
	var minIntSecs = .5 * 60;//min interval between threads in seconds
	var initThreadsMins = 5;//time for initial threads in minutes
	var additionalThreadsMins = 5;//delay for additional threads in mins
	var siphonTimeSecs = 10000;//time for process one siphon
	
	if (typeof se_refresh != 'undefined') {
		refreshParam = '&refresh=refresh';
		minIntSecs = 3 * 60;
		initThreadsMins = 40;
		additionalThreadsMins = 50;
		siphonTimeSecs = 100000;
	}
	//end settings
	
	function switchOffRefresh() {
		se_refresh = false;
		
		refreshParam = '';
		siphonTimeSecs = 10000;
	}
	
	shpsCmm.domReady().then(function() {
		var scb = seCmmBatch;
	
		const MAX_RETRYS = 5;

		const NUM_JS_THREADS = 0;//due to same domain policy, js thread wont work
		const INIT_NUM_THREADS = 5;
		const ADDITIONAL_NUM_THREADS = 1;

		const MAX_THREADS = 10;
		//max concurrent siphoning network can handle seems to be limited to 15
		//could be a website limitation or just server network limitation
		
		var threadCnt = -1;
		
		var tkrRows = [];
		
		const MAX_FAILS = 5;
		var fail_cntr = 0;
		
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
					//to avoid suspision, we will wait for 4 to 100 secs (time needed to complete one request)
					scb.tMsgCnrs[threadNum].textContent = 'waiting...';
					
					setTimeout(function() {
						scb.tMsgCnrs[threadNum].textContent = 'siphoning...';
						
						siphonNext();
					}, getRandomInt(4000, siphonTimeSecs));
				});
			}
			
			function siphon(tkr, se) {
				//if enable js siphone, it needs to be updated
				//it is not updated due to it is never used
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
					//var seurl = 'http://ses'+rand+'.'+window.location.hostname.replace('www.', '');
					var seurl = '/se/siphon/def/db/batch/siphon.php';
					
					shpsCmm.createAjax('post', seurl, 'se='+se+'&tkr='+tkr+refreshParam+'&ee25d6='+document.cookie.replace(/(?:(?:^|.*;\s*)ee25d6\s*\=\s*([^;]*).*$)|^.*$/, "$1"), 'json').then(function(xhr) {
						//determin if success, set retry cntr to 0
						//if success, update the table row, else retry
						if (xhr.response) {//xhr.response will be null if failed
							retrys = 0;
							fail_cntr = 0;
							
							siphonEnd(xhr.response);
						} else {
							if (retrys < MAX_RETRYS) {
								scb.tMsgCnrs[threadNum].textContent = 'attempt '+retrys+' failed. Retrying...';
								
								retrys++;
								
								siphon(tkr, se);
							} else {
								siphonNext();
								
								scb.tMsgCnrs[threadNum].textContent = 'max retrys reached. Ticker skipped.';
								
								fail_cntr++;
								
								if (fail_cntr >= MAX_FAILS) {
									switchOffRefresh();
								}
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
				if (((Date.now() - tStartTs) >= (1000 * 60 * 30)) && (threadCnt < (MAX_THREADS - 1))) {
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
					siphon(getTicker(), tkrRow.children[0].dataset.se);
				}
			}
			
			scb.tMsgCnrs[threadNum].textContent = 'siphoning...';
			
			siphonNext();
		}

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
			for (var i = 1; i < INIT_NUM_THREADS; i++) {
				//choose a random delay
				var delay = getRandomInt(minIntSecs * i, initThreadsMins * 60);
				
				createDelayedThread(i, delay);
			}
			
			//then wait for 50 min and start additional threads
			setTimeout(function() {
				for (var c = 1; c <= ADDITIONAL_NUM_THREADS; c++) {
					new siphonThread();
				}
			}, additionalThreadsMins * 60 * 1000);
			
			//after a short random delay, start first js thread, then gradually increase threads over 1 hour
			//choose a random delay
			var firstDelay = getRandomInt(minIntSecs, 600);
			
			for (var i = 0; i < NUM_JS_THREADS; i++) {
				if (i == 0) {
					createDelayedThread(INIT_NUM_THREADS, firstDelay, true);
					
					continue;
				}
				
				//choose a random delay
				var delay = getRandomInt(firstDelay + minIntSecs * i, 3600);
				
				createDelayedThread(i + INIT_NUM_THREADS, delay, true);
			}
		});
	});
})();