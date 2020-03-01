(function() {
	const dataScriptP = shpsCmm.extFileMgr.lnked('script', '/se/cmm/jss/se_data.js');

	//check for refresh, and set appropriate settings
	var refreshParam = '';
	var ignoreLuParam = '';
	var minIntSecs = .5 * 60;//min interval between threads in seconds
	var initThreadsMins = 5;//time for initial threads in minutes
	var additionalThreadsMins = 5;//delay for additional threads in mins
	var siphonTimeSecs = 10000;//time for process one siphon

	if (typeof se_ignore_lu != 'undefined') {
		ignoreLuParam = '&ignore_lu=ignore';
	}

	if (typeof se_refresh != 'undefined') {
		refreshParam = '&refresh=refresh';
		minIntSecs = 3 * 60;
		initThreadsMins = 40;
		additionalThreadsMins = 50;
		siphonTimeSecs = 200000;//millisecs
	}
	//end settings

	function switchOffRefresh() {
		se_refresh = false;

		refreshParam = '';
		siphonTimeSecs = 10000;
	}

	shpsCmm.domMgr.domReady().then(function() {
		var scb = seCmmBatch;

		const MAX_RETRYS = 5;

		const NUM_JS_THREADS = 0;//due to same domain policy, js thread wont work
		const INIT_NUM_THREADS = 5;
		const ADDITIONAL_NUM_THREADS = 1;

		const MAX_THREADS = 54;
		//max concurrent siphoning network can handle seems to be limited to 15
		//could be a website limitation or just server network limitation

		var threadCnt = -1;

		let tkrRows = [];
		let tkrsCnt;

		var statusP = document.createElement('p');
		scb.msgCnr.appendChild(statusP);

		var startTime;
		var procTkrsCntr = 0;
		var elapsedTime = 0;
		var speed = 0;

		var timeSpan = document.createElement('span');
		var procTkrsSpan = document.createElement('span');
		var speedSpan = document.createElement('span');
		var progressSpan = document.createElement('span');
		var estimateSpan = document.createElement('span');

		const MAX_FAILS = MAX_THREADS;
		var fail_cntr = 0;
		let retryCnt = 0;

		const MAX_RQSS = 5;
		var ttlRqss = 0;

		const MAX_TO_CNT = 3;

		function siphonThread(js) {
			threadCnt++;

			var tStartTs = Date.now();

			var threadNum = threadCnt;

			scb.msgCnr.insertBefore(document.createElement('span'), statusP);
			scb.tMsgCnrs = scb.msgCnr.children;

			var retrys = 0;
			var noMcFails = 0;
			var tkrRow;
			let toCnt = 0;

			function getTicker() {
				//first child of row is the cell with the ticker
				return /\S+/.exec(tkrRow.children[0].textContent)[0];
			}

			function delayedSN() {
				//to avoid suspision, we will wait for 4 to 100 secs (time needed to complete one request)
				scb.tMsgCnrs[threadNum].textContent = 'waiting...';

				setTimeout(function() {
					scb.tMsgCnrs[threadNum].textContent = 'siphoning...';

					siphonNext();
				}, shpsCmm.util.getRandomInt(4000, siphonTimeSecs));
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
						Array.prototype.forEach.call(scb.tkrRows, function(row, idx) {
							if (row == tkrRow) {
								rowIdx = idx;
							}
						});

						scb.body.children[rowIdx].appendChild(td);
					}

					//siphon next
					if (def['lu']) {
						scb.tMsgCnrs[threadNum].textContent = 'siphoning...';

						siphonNext();
					} else {
						delayedSN();
					}
				});
			}

			function siphon(tkr, se, sameRqs) {
				//if max concurrent requests are reached,
				//retry in 2 min
				if ((!sameRqs) && (ttlRqss >= MAX_RQSS)) {
					setTimeout(function() {
						siphon(tkr, se);
					}, 1000 * 60 * 4);

					scb.tMsgCnrs[threadNum].textContent = 'concurrent rqss maxed. Retry in 4 minutes';

					return false;
				}

				//if enable js siphone, it needs to be updated
				//it is not updated due to it is never used
				if (js) {
					var oldAdvice;

					//get db car cc ir
					//if db, set to db values
					shpsCmm.ajaxMgr.createAjax('post', '/se/cmm/get_db_def_vars.php', 'se='+se+'&tkr='+tkr, 'json').then(function(varsObj) {
						oldAdvice = varsObj.oldAdvice;

						return js_siphoned(tkr, varsObj.car, varsObj.cc, varsObj.ir);
					}).then(function(def) {
						//upload to db
						return shpsCmm.ajaxMgr.createAjax('post', '/se/cmm/update_db_def.php', 'se='+se+'&tkr='+tkr+'&def='+JSON.stringify(def)+'&old_advice='+oldAdvice, 'json');
					}).then(function(xhr) {
						//siphon end operations
						siphonEnd(xhr.response);
					});
				} else {
					//pick a random subdomain
					var rand = shpsCmm.util.getRandomInt(1, 3);

					//if there is no www., we simply add the sesrand and remove nothing
					//var seurl = 'http://ses'+rand+'.'+window.location.hostname.replace('www.', '');
					var seurl = '/se/siphon/def/db/batch/siphon.php';

					ttlRqss++;

					shpsCmm.ajaxMgr.createAjax('post', seurl, 'se='+se+'&tkr='+tkr+refreshParam+ignoreLuParam+'&ee25d6='+document.cookie.replace(/(?:(?:^|.*;\s*)ee25d6\s*\=\s*([^;]*).*$)|^.*$/, "$1"), 'json').then(function(xhr) {
						//if cloudflare hardcoded 100 sec tiemout occurs
						//and has not timed out 3 times
						//wait for time to complete siphon (200 secs)
						//then try again
						if (xhr.status == 524) {
							toCnt++;

							if (toCnt < MAX_TO_CNT) {
								let waitMin = siphonTimeSecs / 1000 / 60;

								scb.tMsgCnrs[threadNum].textContent = 'attempt '+retrys+' timed out. Retry in '+waitMin+' minutes';

								setTimeout(function() {
									siphon(tkr, se, true);
								}, siphonTimeSecs);

								return false;
							}
						}

						ttlRqss--;

						//determin if success, set retry cntr to 0
						//if success, update the table row, else retry
						if (xhr.response && !xhr.response.err) {//xhr.response will be null if failed
							retrys = 0;
							noMcFails = 0;
							fail_cntr = 0;
							retryCnt = 0;

							siphonEnd(xhr.response);
						} else if (retrys < MAX_RETRYS) {
							let waitMin = (retryCnt / MAX_RETRYS + 1) * (siphonTimeSecs / 1000) / 60;

							scb.tMsgCnrs[threadNum].textContent = 'attempt '+retrys+' failed. Retry in '+waitMin+' minutes';

							retrys++;
							retryCnt++;

							if (xhr.response && (/^no mc\:.*/.test(xhr.response.err_msg))) {
								noMcFails++;
							}

							setTimeout(function() {
								siphon(tkr, se);
							}, 1000 * 60 * waitMin);
						} else {
							if (noMcFails == retrys) {
								noMcFails = 0;

								//remove tkr
								shpsCmm.ajaxMgr.createAjax('post', '/se/use_db/rmv_tkr.php', 'se='+se+'&tkr='+tkr).then(function(xhr) {
									var msg = 'tkr removal failed!';

									if (xhr.responseText == 'success') {
										msg = 'tkr removed.';
									}

									scb.tMsgCnrs[threadNum].textContent = msg;
								});
							} else {
								fail_cntr++;

								if (se_refresh && (fail_cntr >= MAX_FAILS)) {
									switchOffRefresh();
								}
							}

							delayedSN();

							scb.tMsgCnrs[threadNum].textContent = 'max retrys reached. Ticker skipped.';
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

				//if 60 min passed, the thread duplicates itself
				if ((threadCnt < (MAX_THREADS - 1)) && ((Date.now() - tStartTs) >= (1000 * 60 * 60))) {
					tStartTs = Date.now();

					new siphonThread(js);
				}

				//update status
				elapsedTime = Date.now() - startTime;
				let elapsedSecs = elapsedTime / 1000;
				timeSpan.textContent = parseInt(elapsedSecs / 86400)+'d '+(new Date(elapsedSecs % 86400 * 1000)).toUTCString().replace(/.*(\d{2}):(\d{2}):(\d{2}).*/, "$1h $2m $3s");

				procTkrsSpan.textContent = procTkrsCntr;

				if (procTkrsCntr != 0) {
					speed = elapsedTime / 1000 / procTkrsCntr;
				}

				speedSpan.textContent = speed;

				progressSpan.textContent = procTkrsCntr / tkrsCnt * 100;

				var estimate = (tkrsCnt - procTkrsCntr) * speed;
				estimateSpan.textContent = parseInt(estimate / 86400)+'d '+(new Date(estimate % 86400 * 1000)).toUTCString().replace(/.*(\d{2}):(\d{2}):(\d{2}).*/, "$1h $2m $3s");
				//end update status

				//pick a random row
				var randIdx = shpsCmm.util.getRandomInt(0, tkrRows.length);
				tkrRow = tkrRows[randIdx];

				//remove the row from the arr so other threads won't bother with it
				tkrRows.splice(randIdx, 1);

				procTkrsCntr++;

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
			Array.prototype.forEach.call(scb.tkrRows, function(row) {
				tkrRows.push(row);
			});

			tkrsCnt = tkrRows.length;
			statusP.textContent += 'ttl tkrs: '+tkrsCnt+'; Elapsed time: ';

			statusP.appendChild(timeSpan);

			statusP.appendChild(document.createTextNode('; Proccessed tkrs: '));
			statusP.appendChild(procTkrsSpan);

			statusP.appendChild(document.createTextNode('; Speed: '));
			statusP.appendChild(speedSpan);
			statusP.appendChild(document.createTextNode('s/tkr; Progress: '));
			statusP.appendChild(progressSpan);
			statusP.appendChild(document.createTextNode('%; Estimated time remaining: '));
			statusP.appendChild(estimateSpan);

			startTime = Date.now();

			//start one thread, then set time out for additional threads over 40 min
			new siphonThread();

			//for each of the rest threads, wait random time and start
			//to avoid suspision
			for (let i = 1; i < INIT_NUM_THREADS; i++) {
				//choose a random delay
				const delay = shpsCmm.util.getRandomInt(minIntSecs * i, initThreadsMins * 60);

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
			var firstDelay = shpsCmm.util.getRandomInt(minIntSecs, 600);

			for (let i = 0; i < NUM_JS_THREADS; i++) {
				if (i == 0) {
					createDelayedThread(INIT_NUM_THREADS, firstDelay, true);

					continue;
				}

				//choose a random delay
				var delay = shpsCmm.util.getRandomInt(firstDelay + minIntSecs * i, 3600);

				createDelayedThread(i + INIT_NUM_THREADS, delay, true);
			}
		});
	});
})();
