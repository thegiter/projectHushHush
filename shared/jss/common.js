'use strict';

//shpsCmm is the wrapper for all common functions in this file
//it is not necessary but is good practice in that it creates a namespace
//earlier functions are left outside of this wrapper for backward compatibilities
var shpsCmm = {};

//idbCache is a low level api to use the indexed db as a cache
//this enables the possiblity of offline usage without ssl and serviceWorker
//files must be downloaded as blob and put into the idb
//due to the fact that storage space is limited for each domain origin and is subject to erasing at browsers' discretion
//each page should evalutate which files are absolutely necessary and only store those files
//programmers should be responsible not to abuse the idb
(function() {
	//the idbCache offers 3 basic operations on the idb
	//get item, if successful, item is returned as the promise fullfilled argument, else error
	//set item is used to add a new item if item not already exist, if it exists, it will be overriden, thus is also an update method
	//rmv item removes the item if it is in the db, else nothing is removed
	//all of which returns a promise
	var iC = shpsCmm.idbCache = {};
	
	iC.objStoreName = 'rscs';
	
	//dbInitDo takes 2 parameters, 1 for success, 1 for failure,
	//both must be supplied as there is no telling if the db will be successfully opened
	iC.checkDb = new Promise(function(ff, rjc) {
		var rqs = indexedDB.open('idbCache', 2);
		
		rqs.addEventListener('error', function(evt) {
			rjc();
		});
		rqs.addEventListener('upgradeneeded', function(evt) {
			//either database created for the first time, or database needs upgrading
			
			//indexedDb uses object store rather than tables
			//in this case we create an object store for files / data from the server
			//each file / data is identified uniquely by their url
			var db = rqs.result;
			
			if (!db.objectStoreNames.contains(iC.objStoreName)) {
				db.createObjectStore(iC.objStoreName, {keyPath: 'url'});
			}
		});
		rqs.addEventListener('success', function(evt) {
			iC.db = rqs.result;
			
			ff();
		});
	});
	
	//previously, when db is created, we have defined keyPath to be 'url'
	iC.cache = {};
	
	//because read write to the harddrive can take time
	//all transactions are async
	//therefore all get, set, rmv methods returns a promise
	//keyPath is defined as 'url', data is an obj, and we will use data.url as the key
	iC.set = function(data) {
		return this.checkDb.then(function() {
			//get can only add if key not already exist, put can overwrite
			return new Promise(function(ff, rjc) {
				var rqs = iC.db.transaction(iC.objStoreName, 'readwrite').objectStore(iC.objStoreName).put(data);

				rqs.addEventListener('success', function() {
					iC.cache[data.url] = data;
					
					ff();
				});
			});
		});
	};
	
	iC.rmv = function(key) {
		return this.checkDb.then(function() {
			return new Promise(function(ff, rjc) {
				var rqs = iC.db.transaction(iC.objStoreName, 'readwrite').objectStore(iC.objStoreName).delete(key);

				rqs.addEventListener('success', function() {
					delete iC.cache[key];
					
					ff();
				});
				//if key not found, it could error
				rqs.addEventListener('error', rjc);
			});
		});
	};
	
	//get returns a promise, even if is already in cache and no need for promise
	iC.get = function(key) {
		//check cache first
		if (this.cache[key]) {
			return Promise.resolve(this.cache[key]);
		}
		
		//if not in cache, we check with db, if in db, we add to cache and fullfill
		//else, we reject
		return this.checkDb.then(function() {
			return new Promise(function(ff, rjc) {
				//get does not use rwTransObjStore, it uses the readonly transaction
				//and can have multiple ones on overlapping scope
				//therefore we create a new one every time without worrying about other possible readonly transactions
				var rqs = iC.db.transaction(iC.objStoreName).objectStore(iC.objStoreName).get(key);

				rqs.addEventListener('success', function() {
					//if key not found, it returns undefined
					if (rqs.result) {
						iC.cache[key] = rqs.result;
						
						ff(iC.cache[key]);
					} else {
						rjc();
					}
				});

				rqs.addEventListener('error', rjc);
			});
		});
	};
	//end idbCache
	
	//the iCMgr manages the cache that is the idbCache
	//and processes data to be used with the cache
	var iCM = shpsCmm.iCMgr = {};
	
	iCM.ouCache = {};
	
	//this method is to be used in string replace in checkCss
	iCM.checkCss_checkObjUrl = function(url, srcUrl, arr) {
		//reformat url
		//	remove the content after the last / gives the directory where the css file resides
		//	for each ../, move up one directory, and remove the ../, add two together and get the complete url
		var re = /(.*\/)[^\/]*$/;
		
		srcUrl = re.exec(srcUrl)[1];
		
		//find num ../s
		var reUp = /\.\.\//g;
		var ups = (url.match(reUp) || []).length;

		//go up num of directories
		if (ups > 0) {
			var reDir = /[^\/]+\/$/;
			
			for (var c = 0; c < ups; c++) {//i for index, c for count :p
				srcUrl = srcUrl.replace(reDir, '');
			}
		}

		//remove all ../s
		var theUrl = url.replace(reUp, '');
		
		//create complete url
		var newUrl = srcUrl+theUrl;
		
		var type;
		
		//check if url is css
		if (newUrl.substr(newUrl.length - 4) == '.css') {
			type = 'link';
		}
		
		//check with idb
		return new Promise(function(ff, rjc) {
			//get obj url needs to know if type is link or not in order to get the url correctly,
			//link urls are treated differently than others
			iCM.getObjUrl(newUrl, type).then(function(objUrlCnr) {
				//if found add to arr url with objUrl
				arr.push({
					subStr: url,
					repStr: objUrlCnr.objUrl
				});
			}, function() {
				//if not found, replace with complete url
				arr.push({
					subStr: url,
					repStr: window.location.origin+newUrl
				});
			}).then(function() {
				//then fullfill
				ff();
			});
		});
	};
	
	//srcUrl is the url of the css file on the network, not the blob url from idb
	iCM.checkCss = function(blob, srcUrl) {
		return new Promise(function(ff, rjc) {
			//read blob as text
			var reader = new FileReader();
			
			reader.addEventListener('loadend', function() {
				// reader.result contains the contents of blob as a typed array
				var r = reader.result;
				
				//search for urls
				//the url has a strict format, url(blablabla), url("blablabla") or URL (blabla) or any other format are not tested,
				//and blablabla is assumed to be a valid url
				var urls = [];
				
				var re = /url\(([^\)]+)\)/g;
				
				var match;
				
				while ((match = re.exec(r)) !== null) {
					urls.push(match[1]);
				}
				
				//check each url to see if in idb
				new Promise(function(ffReplace, rjcReplace) {
					if (urls.length > 0) {
						var toReplaceArr = [];
						var promiseArr = [];
						
						urls.forEach(function(matchUrl) {
							//very wierd, because can not push the expression directly into array
							var promise = iCM.checkCss_checkObjUrl(matchUrl, srcUrl, toReplaceArr);
							
							promiseArr.push(promise);
						});
						
						Promise.all(promiseArr).then(function() {
							//replace all found with objUrl
							toReplaceArr.forEach(function(replaceObj) {
								r = r.replace('url('+replaceObj.subStr+')', 'url('+replaceObj.repStr+')');
							});
							
							ffReplace();
						});
					} else {
						ffReplace();
					}
				}).then(function() {
					//create new blob with edited data
					ff(new Blob([r], {type: blob.type}));//blob.type contains the MIME type of the blob content
				});
			});
			
			reader.readAsText(blob);
		});
	};
	
	iCM.ouCache_setAndReturn = function(key, dbRecord) {
		this.ouCache[key] = {
			objUrl: URL.createObjectURL(dbRecord.blob),
			record: dbRecord
		};
		
		return this.ouCache[key];
	};
	
	//getObjUrl is similar to get and returns a promise, it is used as a shortcut when return obj is a blob
	//returns an object containing objUrl and the record as properties
	//get obj url will get from cache, or get from idb
	//if it is not in idb, it will error
	iCM.getObjUrl = function(key, type) {
		if (type == 'link') {
			var lnkKey = key+'_tmpCopy';
			
			if (this.ouCache[lnkKey]) {
				return Promise.resolve(this.ouCache[lnkKey]);
			}
			
			return iC.get(key).then(function(dbRecord) {
				return iCM.checkCss(dbRecord.blob, key);
			}).then(function(blob) {
				var record = {
					blob: blob,
					url: lnkKey
				};
				
				return iC.set(record);
			}).then(function() {
				return iC.get(lnkKey);
			}).then(function(dbRecord) {
				return iCM.ouCache_setAndReturn(lnkKey, dbRecord);
			});
		}
		
		if (this.ouCache[key]) {
			return Promise.resolve(this.ouCache[key]);
		}
		
		return iC.get(key).then(function(dbRecord) {
			return iCM.ouCache_setAndReturn(key, dbRecord);
		});
	};
	
	//createRecordObj is a shortcut method taking into account that all records of cached data will share some common properties
	//such as etag, lastModified date, max age and such
	//it takes an xhr object and gets these common properties from the xhr directly to process them
	//then it returns the record obj, which you can then add more properties to store your cached data
	//if xhr.status is not 200, it returns false
	iCM.createRecordObj = function(xhr) {
		if (xhr.status !== 200) {
			return false;
		}
		
		var record = {
			dlDate: Date.now(),
			lastModified: xhr.getResponseHeader('Last-Modified')
		};
		
		var regEx = /max\-age=([0-9]+)($|,)/;
		
		record.maxAge = regEx.exec(xhr.getResponseHeader('Cache-Control'));
		
		if (record.maxAge == null) {
			record.maxAge = 0;
		} else {
			record.maxAge = parseInt(record.maxAge[1]);
		}

		var eTag = xhr.getResponseHeader('Etag');
		
		if (eTag) {
			record.eTag = eTag;
		}
		
		return record;
	};
	
	iCM.checkExpire = function(record) {
		if ((record.dlDate + record.maxAge * 1000) < Date.now()) {
			return true;
		}
		
		return false;
	};
	
	iCM.createHdrFrom = function(record) {
		var hdrs = [{
			hdr: 'If-Modified-Since',
			value: record.lastModified
		}];
		
		if (record.eTag) {
			hdrs.push({
				hdr: 'If-None-Match',
				value: record.eTag
			});
		}
		
		return hdrs;
	};
	
	iCM.checkUpdate = function(record, url, rspType) {
		//when checking with server, GET method must be used for conditional request
		//because request manager uses POST, we will directly use createAjax
		return shpsCmm.createAjax('GET', url, undefined, rspType, this.createHdrFrom(record)).then(function(xhr) {
			//when get returns response, you can check the status, however
			//the actual content must be retrieved with POST, because the server checks for post parameters
			//therefore if status is 200 ok, we will have to retrieve again with POST
			//this time around, we can use request manager
			if (xhr.status == 200) {
				return xhr;
			}
			
			throw xhr;
		});
	};
	
	iCM.setBlob = function(url, xhr) {
		var record = iCM.createRecordObj(xhr);

		if (record) {
			record.blob = xhr.response;
			record.url = url;
			
			return iC.set(record);
		}
	};
	
	iCM.dlAndSetBlob = function(url) {
		return shpsCmm.createAjax('POST', url, shpsCmm.getRefererParam(), 'blob').then(function(xhr) {
			return iCM.setBlob(url, xhr);
		});
	};
	
	//returns a promise that passes the objUrl when fullfilled
	//get blob url will get from cache or idb, then check for expire and update
	//if not found in idb, it will load from network
	iCM.getBlobUrl = function(url, blobType) {
		//check if item is in idb
		return iCM.getObjUrl(url, blobType).then(function(result) {
			var r = result.record;
			
			//then we check if an update is available
			//if expired
			if (iCM.checkExpire(r)) {
				iCM.checkUpdate(r, url, 'blob').then(function(xhr) {
					//set update
					iCM.setBlob(url, xhr);
				});
			}
			
			//fullfill
			return result.objUrl;
		}, function() {
			//if not in iIdb, we load from network
			return iCM.dlAndSetBlob(url).then(function() {
				return iCM.getObjUrl(url, blobType);
			}).then(function(result) {
				return result.objUrl;
			});//because this is a better way of writting async sequences, no more piramid
		});
	};
	//end iCMgr
	
	//create ajax call
	//this function uses the promise object, meaning you can use "then" after it
	//it returns a promise, and passes the response
	//to use it, you do createAjax(method, url, param).then(callback);
	//the callback is passed the ajax.response
	//promise is useful in that you can use the static method Promise.all([createAjax(), createAjax()]).then(callback);
	//this checks that all promises from createAjax functions are fullfilled before calling the callback
	//making complex async easier
	//the responseType can be optionally set, by default it would be string
	//	returns xhr when successful, does not currently implement a failure detection
	//a failure detection would likely to include a timeout
	shpsCmm.createAjax = function(method, url, param, rspType, rqsHdrs, ajaxHldr, disableHdrCt) {
		return new Promise(function(fullfill, reject) {
			var ajax = new XMLHttpRequest();
			
			if (ajaxHldr) {
				ajaxHldr.ajax = ajax;
			}
			
			//async, the third parameter, is set to true, async must be true to use responseType
			//setting responseType in synchronous xhr is not allowed
			//further more, there is no point of using promise if not async
			ajax.open(method, url, true);
			//Send the proper header information along with the request
			if (!disableHdrCt) {
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			}
			
			if (rqsHdrs) {
				rqsHdrs.forEach(function(hdrObj) {
					ajax.setRequestHeader(hdrObj.hdr, hdrObj.value);
				});
			}
			
			if (rspType) {
				ajax.responseType = rspType;
			}
			
			//when loaded
			ajax.addEventListener('readystatechange', function() {
				if (ajax.readyState == 4) {
					//the response is return with the original xhr obj
					//you can then read the response and the status and the header info
					fullfill(ajax);
				}
			}, false);
			
			ajax.send(param);
		});
	};
	
	shpsCmm.getRefererParam = function() {
		return 'refuri='+window.location.hostname;
	};
	
	var lEF = shpsCmm.lnkExtFile = {};
	
	lEF.promises = {};
	
	//by default, async is on for scripts, async has no effect on links
	lEF.lnked = function(type, url, async, tag, charset) {
		//check requests
		var found = false;
		
		forEachObjProp(this.promises, function(value, key) {
			if (key == url) {
				found = value;
			}
		});
		
		if (found) {
			return found;
		}
		//end check requests

		//check scripts in doc
		//we check request first, then check elms in doc
		//becuase if request is loading, elm would also be in doc even though it's loading and not loaded
		//only if there is no request, then if its in doc, does it mean the elm is loaded
		var checkUrl;
		
		//nli.href/src has http:// in front, to get the path name, we use window.URL
		switch (type) {
			case 'script':
				checkUrl = function(nli) {		//nodeList item
					var urlObj = new URL(nli.src);

					if (urlObj.pathname == url) {
						return true;
					}
					
					return false;
				};
				
				break;
			case 'link':
				checkUrl = function(nli) {
					var urlObj = new URL(nli.href);
					
					if (urlObj.pathname == url) {
						return true;
					}
					
					return false;
				};
		}
		
		var nls = document.getElementsByTagName(type);

		for (var i = 0; i < nls.length; i++) {
			if (checkUrl(nls[i])) {
				//create promise, push to array, return promise
				var p = Promise.resolve();
				
				this.promises.url = p;
				
				return p;
			}
		}
		//end check doc
		
		//create request
		//this array var is still used, for backward compatibility
		requestedExtFile_onloadFcts[url] = [];
		
		var elm = document.createElement(type);
			
		if (charset) {
			elm.charset = charset;
		}

		if (!tag) {
			tag = 'head';
		}

		//when file load, execute all onload functions
		//then remove the request
		var p = new Promise(function(ff, rjc) {
			elm.addEventListener('load', function() {
				ff();
				
				requestedExtFile_onloadFcts[url].forEach(function(value) {
					value();
				});
				
				delete requestedExtFile_onloadFcts[url];
			}, false);
		});
		
		this.promises.url = p;
		
		switch (type) {
			case 'script':
				elm.type = 'text/javascript';
				
				if (async !== false) {
					elm.async = true;
				}
				
				elm.src = url;
			
				break;
			case 'link':
				elm.type = 'text/css';
				elm.rel = 'stylesheet';
				
				elm.href = url;
		}
		
		document.getElementsByTagName(tag)[0].appendChild(elm);
		
		return p;
	};
	
	lEF.lnkedObjUrl = function(type, url, async, tag, charset) {
		return iCM.getBlobUrl(url, type).then(function(objUrl) {
			return lEF.lnked(type, objUrl, async, tag, charset);
		});
	};
	//end link external file
	
	var wdwLdedPromise = new Promise(function(ff, rjc) {
		if (document.readyState == 'complete') {
			ff();
		} else {
			window.addEventListener('load', ff);
		}
	});
	
	shpsCmm.wdwLded = function() {
		return wdwLdedPromise;
	};
	
	var domReadyPromise = new Promise(function(ff, rjc) {
		if ((document.readyState == 'interactive') || (document.readyState == 'complete')) {
			ff();
		} else {
			document.addEventListener('DOMContentLoaded', ff, false);
		}
	});
	
	shpsCmm.domReady = function() {
		return domReadyPromise;
	};
	
	//optimized scrl end lsnr uses the optimize scrl lisener but only fires when the end of scrolling is detected
	//for user scrls, the end is only triggered when the user releases their scroll command, i.e mouse or finger
	//for script scrolls, when the scroll is paused or stopped for longer than the delay, the lisener fires
	//you can use set scriptScrl to true on the scroll elm to bypass the listener
	//so that the listener does not fire for script scrolls
	//the last scroll evt is then passed to the handler
	var oSEL = shpsCmm.optScrlEndLsnr = {};
	
	oSEL.endDelay = 50;//.05 sec

	oSEL.fireScrlEnd = function(scrlEvt, scrlEvtThis) {
		var osel = scrlEvtThis.optScrlEndLsnr;
		
		osel.hdlrs.forEach(function(hdlr) {
			hdlr(scrlEvt, scrlEvtThis);
		});
	};


	oSEL.rmvReleaseCb = function(elm) {
		var osel = elm.optScrlEndLsnr;
		
		elm.removeEventListener('mouseup', osel.mouseReleaseCb);
		elm.removeEventListener('touchcancel', osel.touchReleaseCb);
		elm.removeEventListener('touchend', osel.touchReleaseCb);
		
		osel.mouseReleaseCb = null;
		osel.touchReleaseCb = null;
	};

	oSEL.scrlHdlr = function(evt, scrlEvtThis) {
		//detect script scroll
		if (scrlEvtThis.scriptScrl) {
			scrlEvtThis.scriptScrl = false;
			
			return true;
		}
		
		var osel = scrlEvtThis.optScrlEndLsnr;
		
		if (osel.toId) {
			clearTimeout(osel.toId);
			osel.toId = null;
		}
		
		if (osel.mouseReleaseCb || osel.touchReleaseCb) {
			oSEL.rmvReleaseCb(scrlEvtThis);
		}
		
		var scrlEvt = evt;
		
		osel.toId = setTimeout(function() {
			//check if mouse is down
			if (osel.held) {
				//trigger end at scroll release
				osel.touchReleaseCb = function(evt) {
					oSEL.rmvReleaseCb(this);
					
					oSEL.fireScrlEnd(scrlEvt, scrlEvtThis);
				};
				
				osel.mouseReleaseCb = function(evt) {
					if (evt.target != this) {
						return false;
					}
					
					oSEL.rmvReleaseCb(this);
					
					oSEL.fireScrlEnd(scrlEvt, scrlEvtThis);
				};
				
				scrlEvtThis.addEventListener('mouseup', osel.mouseReleaseCb);
				scrlEvtThis.addEventListener('touchend', osel.touchReleaseCb);
				scrlEvtThis.addEventListener('touchcancel', osel.touchReleaseCb);
			} else {
				oSEL.fireScrlEnd(scrlEvt, scrlEvtThis);
			}
		}, oSEL.endDelay);
	};

	oSEL.mouseHeldHdlr = function(evt) {
		if (evt.target != this) {
			return false;
		}
		
		this.optScrlEndLsnr.held = true;
	};
	
	oSEL.mouseReleasedHdlr = function(evt) {
		if (evt.target != this) {
			return false;
		}
		
		this.optScrlEndLsnr.held = false;
	};
	
	oSEL.touchHeldHdlr = function(evt) {
		this.optScrlEndLsnr.held = true;
	};
	
	oSEL.touchReleaseHdlr = function(evt) {
		this.optScrlEndLsnr.held = false;
	};
	
	oSEL.add = function(elm, hdlr) {// hdlr: the event handler to be added to the listener
		//if not inited
		if (!elm.optScrlEndLsnr) {
			//init
			var osel = elm.optScrlEndLsnr = {};
			
			osel.held = false;
			
			osel.hdlrs = [hdlr];

			elm.addEventListener('mousedown', this.mouseHeldHdlr);
			elm.addEventListener('mouseup', this.mouseReleasedHdlr);
			
			//cater for touchscreens, sigh, next time is what? cater for motion control?
			elm.addEventListener('touchstart', this.touchHeldHdlr);
			elm.addEventListener('touchend', this.touchReleaseHdlr);
			elm.addEventListener('touchcancel', this.touchReleaseHdlr);
			
			optScrlLsnr.add(elm, this.scrlHdlr);
		} else {
			//if hdlr not already added
			var osel = elm.optScrlEndLsnr;

			//if index not found
			if (osel.hdlrs.indexOf(hdlr) == -1) {
				osel.hdlrs.push(hdlr);
			}
		}
	};

	oSEL.rmv = function(elm, hdlr) {
		if (!elm.optScrlEndLsnr) {
			return false;
		}
		
		var oselHdlrs = elm.optScrlEndLsnr.hdlrs;
		var idx = oselHdlrs.indexOf(hdlr);
		
		//if index found
		if (idx != -1) {
			//we remove the hdlr
			oselHdlrs.splice(idx, 1);
			
			//then if the array is empty
			if (oselHdlrs.length <= 0) {
				//remove scroll listner
				optScrlLsnr.rmv(elm, this.scrlHdlr);
				
				//remove scroll held and relase listeners
				elm.removeEventListener('mousedown', this.mouseHeldHdlr);
				elm.removeEventListener('mouseup', this.mouseReleasedHdlr);
				
				//cater for touchscreens, sigh, next time is what? cater for motion control?
				elm.removeEventListener('touchstart', this.touchHeldHdlr);
				elm.removeEventListener('touchend', this.touchReleaseHdlr);
				elm.removeEventListener('touchcancel', this.touchReleaseHdlr);
				
				//remove the optScrlEndLsnr data
				elm.optScrlEndLsnr = undefined;
			}
		}
	};
	//end optimized scrl end lsnr
	
	//scrl snap module is deprecated, use css scroll snap points, and swipe page module has been assigned a new purpose
	//scrl snap basically listens to when the scrl end, and calculate the nearest snap point to where the scroll ended
	//then informs the handler of the index
	//you need to feed it the function used to calculate how many snap points there are
	//this function is executed every time the scroll ends, in case the snap point have been changed
	//the function must spit out an object indicating number of snap points vertically and horizontally
	//the object must contain either v or h or both properties
	//then based on v or h will calculate the nearest snap point in the form of indexes
	//this idxsObj is then given to the handler
	var sS = shpsCmm.scrlSnap = {};
	
	//maxLen is the scrollHeight or scrollWidth
	//because calcIdx distributes the available scroll to number of children evenly
	//the children should be of the same size
	sS.calcIdx = function(sPosition, maxLen, clientLen, numChildren) {
		var avaiS = maxLen - clientLen;
		var sPerPage = avaiS / (numChildren - 1);

		return Math.round(sPosition / sPerPage);
	};
	
	sS.scrlEndHdlr = function(evt, scrlEvtThis) {
		var elm = scrlEvtThis;
		
		var ss = scrlEvtThis.scrlSnap;
		
		//check v scrolled
		var st = elm.scrollTop;
	
		if (st != ss.lastT) {
			ss.lastT = st;
		} else {
			st = false;
		}
		
		//check h scrolled
		var sl = elm.scrollLeft;
	
		if (sl != ss.lastL) {
			ss.lastL = sl;
		} else {
			sl = false;
		}
		
		ss.pairs.forEach(function(pairObj) {
			var numChildrenObj = pairObj.gNCF();
				
			var idxs = {};
			
			if (numChildrenObj.v && (st !== false)) {
				idxs.v = sS.calcIdx(st, elm.scrollHeight, elm.clientHeight, numChildrenObj.v);
			}
			
			if (numChildrenObj.h && (sl !== false)) {
				idxs.h = sS.calcIdx(sl, elm.scrollWidth, elm.clientWidth, numChildrenObj.h);
			}
			
			//if scrolled v or h
			if (('v' in idxs) || ('h' in idxs)) {//a simpler check will not work since v and h could be 0 which is falsy
				//pass result idxs to handlers
				pairObj.hdlr(idxs, elm);
			}
		});
	};
	
	//forceFireHdlrs fires all hdlrs regardless of scroll or not
	//and does not check for scroll changes
	sS.forceFireHdlrs = function(elm) {
		var ss = elm.scrlSnap;
		
		ss.pairs.forEach(function(pairObj) {
			var numChildrenObj = pairObj.gNCF();
				
			var idxs = {};
			
			if (numChildrenObj.v) {
				var st = elm.scrollTop;
			
				ss.lastT = st;
					
				idxs.v = sS.calcIdx(st, elm.scrollHeight, elm.clientHeight, numChildrenObj.v);
			}
			
			if (numChildrenObj.h) {
				var sl = elm.scrollLeft;
			
				ss.lastL = sl;
					
				idxs.h = sS.calcIdx(sl, elm.scrollWidth, elm.clientWidth, numChildrenObj.h);
			}
			
			//if either direction is valid
			if (('v' in idxs) || ('h' in idxs)) {//a simpler check will not work since v and h could be 0 which is falsy
				//pass result idxs to handlers
				pairObj.hdlr(idxs, elm);
			}
		});
	};
	
	//getNumChildrenFct must return an obj with either v or h or both values
	//the function recalculates the number of children everytime scroll event is detected
	//because children maybe added or removed as user uses the page
	//hdlr is passed the resulting indexs to show which child is to scroll to
	//index validation should be done by the handler as it is possible the index has not changed
	//getNumChildrenFct corresponds to hdlr, each hdlr can have its own way to calculate numChildren
	//it getNumChildrenFct is the same as the previous one, it must still be supplied
	sS.add = function(elm, getNumChildrenFct, hdlr) {
		//if not inited
		if (!elm.scrlSnap) {
			//init
			var ss = elm.scrlSnap = {};
			
			ss.lastT = elm.scrollTop;
			ss.lastL = elm.scrollLeft;
			
			ss.pairs = [{
				gNCF: getNumChildrenFct,
				hdlr: hdlr
			}];

			oSEL.add(elm, this.scrlEndHdlr);
		} else {
			//if hdlr not already added
			var ss = elm.scrlSnap;

			//if index not found
			//every must return true for every item of the array
			//not returning anything is same as returning a falsy result, it returns undefined
			if (ss.pairs.every(function(pairObj) {
				if (pairObj.hdlr != hdlr) {
					return true;
				}
			})) {
				ss.pairs.push({
					gNCF: getNumChildrenFct,
					hdlr: hdlr
				});
			}
		}
	};
	
	sS.rmv = function(elm, hdlr) {
		if (!elm.scrlSnap) {
			return false;
		}
		
		var ssPairs = elm.scrlSnap.pairs;
		var idx;
		
		//if index found
		if (ssPairs.some(function(pairObj, index) {
			if (pairObj.hdlr == hdlr) {
				idx = index;
				
				return true;
			}
		})) {
			//we remove the hdlr
			ssPairs.splice(idx, 1);
			
			//then if the array is empty
			if (ssPairs.length <= 0) {
				//remove scroll end listener
				oSEL.rmv(elm, this.scrlEndHdlr);
				
				//remove the scrlSnap data
				elm.scrlSnap = undefined;
			}
		}
	};
	//end scroll snap
	
	//swipe click triggers the link on the nears element that was scrolled to
	
	//swipe page checks the index the element is swiped to,
	//then finds the corresponding spg inside the element
	//then clicks the spg item, and triggers its href
	//the page using swipe page should then listen to hash changes
	//the spgs should be the same size because scroll snap requires it
	var sC = shpsCmm.swipeClick = {};
	
	//the class provides some basic get v and h num and get v and h elm methods
	//if your design is a simple one where all of the children of the elm are swipe pages, then you can use these method
	sC.getVNum = function(elm) {
		return {v: elm.children.length};
	};
	
	sC.getHNum = function(elm) {
		return {h: elm.children.length};
	};
	
	sC.getVElm = function(idxsObj, elm) {
		return elm.children[idxsObj.v];
	};
	
	sC.getHElm = function(idxsObj, elm) {
		return elm.children[idxsObj.h];
	};
	
	sC.scrlSnapHdlr = function(idxsObj, elm) {
		var sc = elm.swipeClick;
		
		//check if index is different
		var changed = false;
		
		if ('h' in idxsObj) {
			if (sc.lastHIdx !== idxsObj.h) {
				sc.lastHIdx = idxsObj.h;
				
				changed = true;
			}
		}
		
		if ('v' in idxsObj) {
			if (sc.lastVIdx !== idxsObj.v) {
				sc.lastVIdx = idxsObj.v;
				
				changed = true;
			}
		}
		
		sc.pairs.forEach(function(pair) {
			var toElm = pair.gEBI(idxsObj, elm);
		
			if (changed) {
				var toElm_clickEvt = new MouseEvent('click', {
					'bubbles': false,
					'cancelable': true
				});
				
				toElm.dispatchEvent(toElm_clickEvt);
			} else if (pair.hdlr) {
				pair.hdlr(toElm);
			}
		});
	};
	
	//for simple one direction swipes, the swipe page class does calculations automatically
	//getNumChildrenFct requires you to provide a function to calculate how many element vertically and horizontally
	//it must return an obj that contains either v or h property or both which are integers representing how many elements for each direction
	//handler is used to accept the return of the result (elm) if the index has not changed
	//the handler need to swipe back to the element, the element's link probably is no longer available
	//since it had already been activated
	//get Elm by idx must be a customized function to get element by index
	//it will be passed an idexes object which contains either v or h index or both
	sC.add = function(elm, getNumChildrenFct, getElmByIdx, hdlr) {
		//if not inited
		if (!elm.swipeClick) {
			//init
			var sc = elm.swipeClick = {};
			
			sc.pairs = [{
				gEBI: getElmByIdx,
				hdlr: hdlr
			}];

			sS.add(elm, function() {//can not assign getNumChildrenFct(elm) to getNumChildrenFct, it error too much recursion
				return getNumChildrenFct(elm);//return the result of getNumChildrenFct, which is an object
			}, sC.scrlSnapHdlr);
		} else {
			//if hdlr not already added
			var sc = elm.swipeClick;

			//if index not found
			//every must return true for every item of the array
			//not returning anything is same as returning a falsy result, it returns undefined
			if (sc.pairs.every(function(pairObj) {
				if (pairObj.hdlr != hdlr) {
					return true;
				}
			})) {
				sc.pairs.push({
					gEBI: getElmByIdx,
					hdlr: hdlr
				});
			}
		}
	};
	
	sC.rmv = function(elm, hdlr) {
		if (!elm.swipeClick) {
			return false;
		}
		
		var scPairs = elm.swipeClick.pairs;
		var idx;
		
		//if index found
		if (scPairs.some(function(pairObj, index) {
			if (pairObj.hdlr == hdlr) {
				idx = index;
				
				return true;
			}
		})) {
			//we remove the hdlr
			scPairs.splice(idx, 1);
			
			//then if the array is empty
			if (scPairs.length <= 0) {
				//remove scrl snap listner
				sS.rmv(elm, this.scrlSnapHdlr);
				
				//remove the swipe click data
				elm.swipeClick = undefined;
			}
		}
	};
	
	//the horizontal mousewheel module captures mousewheel scrolling and translates it to horizontal scroll
	//all other methods of scrolling already handles horizontal scrolling pretty well
	//only the mousewheel is the trouble and need to be dealt with
	//this means, no vertical scroll will be allowed when this module is on
	//to enable a child element vertical scroll, register the child element with module
	//if you register an element that is not a child/decendent element of the hor scroll element
	//the module will not function properly
	var hMw = shpsCmm.horMousewheel = {};
	
	//register wheel flag var correct variable with the window
	window.addEventListener('wheel', function(evt) {
		
	});
})();

var wdwLoaded = false;

window.addEventListener('load', function() {
	wdwLoaded = true;
});

// Production steps of ECMA-262, Edition 5, 15.4.4.18
// Reference: http://es5.github.io/#x15.4.4.18
if (!Array.prototype.forEach) {
  Array.prototype.forEach = function(callback, thisArg) {
    var T, k;

    if (this == null) {
      throw new TypeError(' this is null or not defined');
    }

    // 1. Let O be the result of calling ToObject passing the |this| value as the argument.
    var O = Object(this);

    // 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".
    // 3. Let len be ToUint32(lenValue).
    var len = O.length >>> 0;

    // 4. If IsCallable(callback) is false, throw a TypeError exception.
    // See: http://es5.github.com/#x9.11
    if (typeof callback !== "function") {
      throw new TypeError(callback + ' is not a function');
    }

    // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
    if (arguments.length > 1) {
      T = thisArg;
    }

    // 6. Let k be 0
    k = 0;

    // 7. Repeat, while k < len
    while (k < len) {
      var kValue;

      // a. Let Pk be ToString(k).
      //   This is implicit for LHS operands of the in operator
      // b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.
      //   This step can be combined with c
      // c. If kPresent is true, then
      if (k in O) {

        // i. Let kValue be the result of calling the Get internal method of O with argument Pk.
        kValue = O[k];

        // ii. Call the Call internal method of callback with T as the this value and
        // argument list containing kValue, k, and O.
        callback.call(T, kValue, k, O);
      }
      // d. Increase k by 1.
      k++;
    }
    // 8. return undefined
  };
}

function popup(lnk, wn, w, h, center, lct, stt, scl, rsz) {
	if (! window.focus)
		return true;
		
	var href;
	
	if (typeof lnk == 'string')
		href=lnk;
	else
		href=lnk.href;
		
	var parthree = 'width='+w+', height='+h;
	
	if (center) {
		var lft = (screen.width-w)/2;
		var top = (screen.height-h)/2;
		parthree = parthree+', left='+lft+', top='+top;
	}
	if (lct) {
		parthree = parthree+', location=yes';
	}
	if (stt) {
		parthree = parthree+', status=yes';
	}
	if (scl) {
		parthree = parthree+', scrollbars=yes';
	}
	if (rsz) {
		parthree = parthree+', resizable=yes';
	}
	
	window.open(href, wn, parthree);
}

function refresh() {
	window.location.reload();
}

function clswdw() {
	self.close();
}

function pageback() {
	history.back();
}

function addEvt(ele, evt, fct) {
	if (window.addEventListener) {
		ele.addEventListener(evt, fct, false);
	}
	else if (window.attachEvent) {
		ele.attachEvent('on'+evt, fct);
	}
}

function returnFalse(event) {
	(event.preventDefault) ? event.preventDefault() : (event.returnValue = false);
}

function classOf(o) {
	if (o === undefined) {
		return "Undefined";
	}
	if (o === null) {
		return "Null";
	}
	
	return {}.toString.call(o).slice(8, -1);
}

//deprecated and left for backward compatibilities
//use promise instead
function imgOnload(cnter, maxcnt, fct) {
	if (cnter >= maxcnt) {
		fct();
		
		return true;
	}
	
	return false;
}

//reject (imgRjc) is optional callback for backward compatibility reasons, as the old function does not have this parameter
function add_imgOnload(path, imgName_arr, imgFf, imgRjc) {
	if (classOf(imgName_arr) != 'Array') {
		return false;
	}
	
	var promiseArr = [];
	
	imgName_arr.forEach(function(name) {
		var newImg = new Image();
		
		var promise = new Promise(function(ff) {
			newImg.addEventListener('load', ff);
		});
		
		newImg.src = path.replace('/*replace*/', name);

		promiseArr.push(promise);
	});
	
	Promise.all(promiseArr).then(imgFf, function() {
		if (imgRjc) {
			imgRjc();
		}
	});
}

//the9imgsPreload is actually the 9 images onload, when all the images are loaded, an callback function is executed
//it was slightly wrongly named, but left this way for backward compatibilities
function the9imgsPreload(path, hasCtr, fct) {
	var imgName_arr = new Array('tl', 'top', 'tr', 'lft', 'rgt', 'bl', 'btm', 'br', 'ctr');	
	var newImg;
	var cnter = 0;
	var maxCnt = 8;
	
	if (hasCtr) {
		maxCnt = 9;
	}
	
	function onload() {
		cnter++;
		
		imgOnload(cnter, maxCnt, fct);
	}
	
	var forLoop_max = maxCnt-1;
	
	for (var i = 0; i <= forLoop_max; i++) {
		newImg = new Image();
		
		addEvt(newImg, 'load', onload);
		
		newImg.src = path.replace('/*replace*/', imgName_arr[i]);
	}
}

function removeCheck(regex, str) {
	str = str.replace(regex, ' ');
	
	if (str.search(regex) != -1) {
		str = removeCheck(regex, str);
	}
	
	return str;
}

//Deprecated!!!!!!
//use elm.classList instead!
//classList has the following methods:
//add - Adds a class to an element's list of classes. If class already exists in the element's list of classes, it will not add the class again.
//remove - Removes all instances of a class from an element's list of classes. If class does not exist in the element's list of classes, it will not throw an error or exception.
//toggle - Toggles the existence of a class in an element's list of classes
//	See below about the optional second argument.
//contains - Checks if an element's list of classes contains a specific class
//The toggle method has an optional second argument that will force the class name to be added or removed based on the truthiness of the second argument.
//For example, to remove a class (if it exists or not) you can call element.classList.toggle('classToBeRemoved', false)
//and to add a class (if it exists or not) you can call element.classList.toggle('classToBeAdded', true);
function mdf_cls(elmOrId, type, value) {
	var elm;
	
	//because object tag returns as function, while others return as object
	((typeof(elmOrId) == 'object') || (typeof(elmOrId) == 'function')) ? (elm = elmOrId) : (elm = document.getElementById(elmOrId));

	//code commented out is updated to use classList, but is kept as a reference
/*	var newV = value.replace('-', '\\-');
	var reCls = new RegExp("(^|(\\s)+)\\b"+newV+"\\b($|(\\s)+)", 'g');
	
	switch (type) {
		case 'add':
			var cls = elm.className;

			if (cls.search(reCls) == -1) {
				(cls.length == 0 || cls.charAt(cls.length - 1) == ' ') ? elm.className += value : elm.className += (' '+value);
			}
			
			break;
		case 'remove':
			elm.className = removeCheck(reCls, elm.className);
	}*/
	
	var clss = value.split(' ');//returns an array
	var eCL = elm.classList;//shorhand
	
	//apply allows us to use array as arguments, instead of comma separated arguments required by the original classList methods
	eCL[type].apply(eCL, clss);
}

var onloadFct_arr = [];

function add_eleOnload(elmId, fct) {				// new function, do not retrieve the element, it's a preload, meaning the element does not exist yet, get id instead
	if (onloadFct_arr[elmId]) {//	use classOf instead, typeof would return 'object', since all arrays are objects in javascript, classOf returns 'Array'
		onloadFct_arr[elmId].fcts.push(fct);
	} else {
		onloadFct_arr[elmId] = {};
		
		onloadFct_arr[elmId].fcts = [fct];
		
		onloadFct_arr[elmId].intId = setInterval(function() {
			if (document.getElementById(elmId)) {
				clearInterval(onloadFct_arr[elmId].intId);
				
				onloadFct_arr[elmId].fcts.forEach(function(fct) {
					fct();
				});
				
				onloadFct_arr[elmId] = undefined;
			}
		}, 42);//results in a little bit less than 24 frames per seconds
	}
}

function remove_eleOnload(elmId) {
	if (onloadFct_arr[elmId]) {
		clearInterval(onloadFct_arr[elmId].intId);

		onloadFct_arr[elmId] = undefined;
	}
}

//this is to spare the setInterval from add_eleOnload for performance considerations
function checkElmOnload(elmId, cb) {
	if (document.getElementById(elmId)) {
		cb();
	} else {
		add_eleOnload(elmId, cb);
	}
}

var imgSet_fct_arr = [];

function add_fct(imgSet, newFct) {
	if (typeof imgSet_fct_arr[imgSet] == 'function'){	//function objects are still identified as function
		var oldFct = imgSet_fct_arr[imgSet];
	
		imgSet_fct_arr[imgSet] = function() {
			oldFct();
			newFct();
		};
	}
	else{
		imgSet_fct_arr[imgSet] = newFct;
	}
}

//if the same script is loaded twice, global variables in the script will be refreshed to its initial value;
//it is of crucial importance that the scripts are only loaded once
//to achieve this
//scripts already in document is checked
//in addition scripts requested (not yet loaded in DOM, but is on its way) are also checked
//this requires that include is the only function used to include scripts
//allowing it to keep track of requests
//after script is loaded, request is removed
var requestedExtFile_onloadFcts = {};

//this version is deprecated and left for backward compatibility
function include(type, url, tag, charset, callback, async) {
	//check requests
	var found = false;
	
	forEachObjProp(requestedExtFile_onloadFcts, function(value, key) {
		if (key == url) {
			if (callback) {	//callback is optional, for backward compatibilities
				value.push(callback);
			}
			
			found = true;
		}
	});
	
	if (found) {
		return true;
	}
	//end check requests
	//if found, push callback to request onload fct array, terminate function
	
	//check scripts in doc
	//we check request first, then check elms in doc
	//becuase if request is loading, elm would also be in doc even though it's loading and not loaded
	//only if there is no request, then if its in doc, does it mean the elm is loaded
	var checkUrl;
	
	//nli.href/src has http:// in front, to get the path name, we use an anchor element
	var a = document.createElement('a');
	
	switch (type) {
		case 'script':
			checkUrl = function(nli) {		//nodeList item
				a.href = nli.src;

				if (a.pathname == url) {
					return true;
				}
				
				return false;
			};
			
			break;
		case 'link':
			checkUrl = function(nli) {
				a.href = nli.href;
				
				if (a.pathname == url) {
					return true;
				}
				
				return false;
			};
	}
	
	var nls = document.getElementsByTagName(type);

	for (var i = 0; i < nls.length; i++) {
		if (checkUrl(nls[i])) {
			if (callback) {	//callback is optional, for backward compatibilities
				callback();
			}
			
			return true;
		}
	}
	//end check doc
	//if found, callback, terminate function
	
	//create request
	requestedExtFile_onloadFcts[url] = [];
	
	if (callback) {
		requestedExtFile_onloadFcts[url].push(callback);
	}
	
	var ele = document.createElement(type);
		
	if (charset) {
		ele.charset = charset;
	}

	if (!tag) {
		tag = 'head';
	}

	//when file load, execute all onload functions
	//then remove the request
	ele.addEventListener('load', function() {
		requestedExtFile_onloadFcts[url].forEach(function(value) {
			value();
		});
		
		delete requestedExtFile_onloadFcts[url];
	}, false);
	
	switch (type) {
		case 'script':
			ele.type = 'text/javascript';
			
			if (async !== false) {
				ele.async = true;
			}
			
			ele.src = url;
		
			break;
		case 'link':
			ele.type = 'text/css';
			ele.rel = 'stylesheet';
			
			ele.href = url;
	}
	
	document.getElementsByTagName(tag)[0].appendChild(ele);
}

Array.prototype.shuffle = function() {
	function randOrd() {
		return (Math.round(Math.random())-0.5);
	}

	this.sort(randOrd);
};

//get style gets the css property value of an element
//e.g get 'font-size', returns '16px', note it is in string with the units
//window.getComputedStyle(elm).fontSize returns the same result
function getStyle(elmOrId, styleProp) {
	var elm;
	
	((typeof(elmOrId) == 'object') || (typeof(elmOrId) == 'function')) ? (elm = elmOrId) : (elm = document.getElementById(elmOrId));
	
	//var value;
	
	//if (ele.currentStyle) {
	//	value = ele.currentStyle[styleProp];
	//}
	//else if (window.getComputedStyle) {
	var value = document.defaultView.getComputedStyle(elm, null).getPropertyValue(styleProp);
	//}
	
	return value;
}

function imgPreload(src) {
	var img = new Image();
		
	img.scr = src;
}

function imgSetPreload(path, imgName_arr) {
	var src;
	
	imgName_arr.forEach(function(value) {
		src = path.replace('/*replace*/', value);
		
		imgPreload(src);
	});
}

// Returns a random integer between min (included) and max (excluded)
// Using Math.round() will give you a non-uniform distribution!
function getRandomInt(min, max) {
	return Math.floor(Math.random() * (max - min)) + min;
}

//	the ttlElmOnload class should be used when there is images, js and such resources involved.
//	If only the DOM element is needed, use DOMContentLoaded event on document should be sufficient in most cases
//the total element onload class attempts to check if all the resources that make up an html element is loaded,
//if so, an callback function is executed and the element is faded in through css
//for this, the element must have the opa-0 class, and an fade in transition css applied, suggested: fade-in-norm class
//this means, the main elm must be provided on construction
//the resources include images, javascript, and other DOM elements that may be required by this element, which are all optional
//and the callback function is also optional
//usage:
//	new ttlElmOnload(elmId) to instantiate object, elmId being the main element, must be provided
//	obj.addElm adds additional elements
//	obj.addImgs adds images
//	obj.addThe9Imges adds the 9 images set of images
//	obj.addScript adds javascript
//	obj.addCss adds css
//	obj.callback sets the callback function
//	set obj.useDefault to false to disable the default opacity onload animation
//		in that case, onload anim should be provided in the callback function
//	obj.start() starts the onload checks, use once all resources and callback function is set.
//	obj.lded to check if the element is loaded, returns true if loaded
function ttlElmOnload(elmId) {	//class
	this.elmId = elmId;
	this.callback = function() {
	};
	this.useDefault = true;
	this.lded = false;
	
	var lded = {};	//this is an object and not an array; no associative arrays in js
	
	this.addLdedCheck = function(key) {
		lded[key] = false;
	};
	
	this.setLded = function(key) {
		lded[key] = true;
	};
	
	this.ldFctArr = {};
	
	this.triggerOnload = function() {
		var allLded = true;
		
		Object.keys(lded).forEach(function(value) {	//return will not work here, because return will only terminate this function, and not the outer function
			if (lded[value] == false) {
				allLded = false;
				
				//you can not break out of an forEach loop
				//you can use for loop
				//or use return true in some loop, e.g. array.some(function(value){if (want_to_break) return true;});
				//or use return false in every loop
			}
		});
		
		if (allLded) {
			this.lded = true;
			
			this.callback();
			
			if (this.useDefault) {
				mdf_cls(elmId, 'remove', 'opa-0');
			}
		}
	};
	
	this.resCallback = function(key) {	//single resource onload callback
		lded[key] = true;
					
		this.triggerOnload();
	};
	
	lded[elmId] = false;
	
	var theThis = this;
	
	this.ldFctArr[elmId] = function() {
		checkElmOnload(elmId, function() {
			theThis.resCallback(elmId);
		});
	};
}

ttlElmOnload.prototype.addElm = function(elmId) {
	this.addLdedCheck(elmId);
	
	var theThis = this;
	
	this.ldFctArr[elmId] = function() {
		checkElmOnload(elmId, function() {
			theThis.resCallback(elmId);
		});
	};
};

ttlElmOnload.prototype.addImgs = function(path, namesArr) {
	this.addLdedCheck(path);
	
	var theThis = this;
	
	this.ldFctArr[path] = function() {
		add_imgOnload(path, namesArr, function() {
			theThis.resCallback(path);
		});
	};
};

ttlElmOnload.prototype.addThe9Imges = function() {
};

ttlElmOnload.prototype.addScript = function(url, tag, charset) {
	this.addLdedCheck(url);
	
	var theThis = this;
	
	this.ldFctArr[url] = function() {
		include('script', url, tag, charset, function() {
			theThis.resCallback(url);
		});
	};
};

ttlElmOnload.prototype.addCss = function(url, tag, charset) {
	this.addLdedCheck(url);
	
	var theThis = this;
	
	this.ldFctArr[url] = function() {
		include('link', url, tag, charset, function() {
			theThis.resCallback(url);
		});
	};
};

ttlElmOnload.prototype.start = function() {
	var theThis = this;
	
	Object.keys(this.ldFctArr).forEach(function(value) {
		theThis.ldFctArr[value]();
	});
};
//end total element onload class

function forEachObjProp(obj, fct) {
	for (var prop in obj) { //prop: property, string
		// important check that this is objects own property 
		// not from prototype prop inherited
		if (obj.hasOwnProperty(prop)) {
			//because prop is a string
			fct(obj[prop], prop);
		}
	}
}

function forEachNodeItem(nl, fct) {	//nl: nodeList
	//because some fct may alter nl in each loop
	//therefore changing the length of the node list, and the index for each list item
	//to create a true forEach, we are going to store each node item reference into an array before we run the fct for each node item
	var nlArr = [];
	
	for (var i = 0; i < nl.length; i++) {
		nlArr[i] = nl[i];
	}
	
	nlArr.forEach(function(elm, idx) {
		fct(elm, idx);
	});
}

//because the resize event can fire at a high rate, and in most cases, we dont really need the handler to be executed every time it fires
//this class is aimed to optimize the resize event listener so that it fires less
//this class should not be used if you really want your handler to be executed every time event fires
//optimized resize listener static class
//even though everything is public, only the optRszLsnr.add and remove should be used
//I expect the programmers to be responsible
var optRszLsnr = {};

optRszLsnr.fps = 24;
optRszLsnr.interval = 1000 / optRszLsnr.fps;

optRszLsnr.inited = false;
optRszLsnr.hdlrs = [];

//running is used to check if the last resize event handler has been executed yet.
//every time the event handler is executed, running is set back to false
optRszLsnr.running = false;

//initialized the last timestamp;
optRszLsnr.lastTs = 0;
optRszLsnr.intendTs = 0;
optRszLsnr.expireTs = 0;

//the animation frame handler
optRszLsnr.afHdlr = function(ts) {
	//must meet intend ts
	if (ts >= optRszLsnr.intendTs) {
		//then we set ts to the new ts and run the handlers
		optRszLsnr.lastTs = ts;
		
		optRszLsnr.hdlrs.forEach(function(hdlr) {
			hdlr();
		});
		
		//set intend ts for next paint
		//if old intendTs expired
		if (ts >= optRszLsnr.expireTs) {
			//reset to ts
			optRszLsnr.intendTs = ts;
		}
		
		optRszLsnr.intendTs += optRszLsnr.interval;
		
		//update expireTs
		//ts is allowed to exceed intendTs, but can only exceed by 1 frame
		//if more, then it is expired
		optRszLsnr.expireTs = optRszLsnr.intendTs + optRszLsnr.interval;
		
		//and we say is finished running
		optRszLsnr.running = false;
	}
	else {
		//we dont run the handlers, but postpone it to the next animation frame where we will check time stamp again.
		window.requestAnimationFrame(optRszLsnr.afHdlr);
	}
};

//there is no need to received the evt obj as argument,
//because event type will always be resize
//and event target will always be window
//and useCapture will always be false
optRszLsnr.rszHdlr = function() {
	//detect script resize
	if (this.scriptRsz) {
		this.scriptRsz = false;
		
		return true;
	}
	
	//if running is false, last handler has finished execution and is no longer running
	if (!optRszLsnr.running) {
		//we then start a new execution
		optRszLsnr.running = true;
		
		//we request the hdlrs to only run at next repaint
		window.requestAnimationFrame(optRszLsnr.afHdlr);
	}
};

optRszLsnr.add = function(hdlr) {// hdlr: the event handler to be added to the listener
	//if index not found
	if (optRszLsnr.hdlrs.indexOf(hdlr) == -1) {
		optRszLsnr.hdlrs.push(hdlr);
	
		if (!optRszLsnr.inited) {
			optRszLsnr.inited = true;
		
			window.addEventListener('resize', optRszLsnr.rszHdlr, false);
		}
	}
};

optRszLsnr.rmv = function(hdlr) {
	var idx = optRszLsnr.hdlrs.indexOf(hdlr);
	
	//if index found
	if (idx != -1) {
		//we remove the hdlr
		optRszLsnr.hdlrs.splice(idx, 1);
		
		//then if the array is empty
		if (optRszLsnr.hdlrs.length <= 0) {
			//we remove the real resize event listener from window
			window.removeEventListener('resize', optRszLsnr.rszHdlr, false);
			
			optRszLsnr.inited = false;
		}
	}
};
//end optimized resize listener

//optElmRszLsnr adds resize listeners to elements
//this assumes the element can be resized, i.e block or inline block element
//use optElmRszLsnr.add(elm, hdlr) to add listeners, no argument is passed to the handler because, the event does not bubble
//meaning the event target or this will always be the elm, and event is always resize,
//you must use optElmRszLsnr.rmv(elm, hdlr) to remove the handler
// !IMPORTANT: a nasty issue with this class and workaround is that the element will be set to relative if originally is set to static
//	Keep this in mind when using this class, if setting to static is important, then you can not use this class
var optElmRszLsnr = {};

optElmRszLsnr.fps = 24;
optElmRszLsnr.interval = 1000 / optElmRszLsnr.fps;

//the animation frame handler
optElmRszLsnr.afHdlr = function(elm, ts) {
	var oerl = elm.optElmRszLsnr;
	
	//must meet intend ts
	if (ts >= oerl.intendTs) {
		//then we set ts to the new ts and run the handlers
		oerl.lastTs = ts;
		
		oerl.hdlrs.forEach(function(hdlr) {
			hdlr();
		});
		
		//set intend ts for next paint
		//if old intendTs expired
		if (ts >= oerl.expireTs) {
			//reset to ts
			oerl.intendTs = ts;
		}
		
		oerl.intendTs += optElmRszLsnr.interval;
		
		//update expireTs
		//ts is allowed to exceed intendTs, but can only exceed by 1 frame
		//if more, then it is expired
		oerl.expireTs = oerl.intendTs + optElmRszLsnr.interval;
		
		//and we say is finished running
		oerl.running = false;
	}
	else {
		//we dont run the handlers, but postpone it to the next animation frame where we will check time stamp again.
		window.requestAnimationFrame(function(newTs) {
			optElmRszLsnr.afHdlr(elm, newTs);
		});
	}
};

optElmRszLsnr.rszHdlr = function(evt) {
	var elm = this.rszElm;
	
	//detect script resize
	if (elm.scriptRsz) {
		this.scriptRsz = false;
		
		return true;
	}
	
	//if running is false, last handler has finished execution and is no longer running
	if (!elm.optElmRszLsnr.running) {
		//we then start a new execution
		elm.optElmRszLsnr.running = true;
		
		//we request the hdlrs to only run at next repaint
		window.requestAnimationFrame(function(ts) {
			optElmRszLsnr.afHdlr(elm, ts);
		});
	}
};

optElmRszLsnr.add = function(elm, hdlr) {// hdlr: the event handler to be added to the listener
	//if not inited
	if (!elm.optElmRszLsnr) {
		//init
		var oerl = elm.optElmRszLsnr = {};
		
		oerl.running = false;
		oerl.lastTs = 0;
		oerl.intendTs = 0;
		oerl.expireTs = 0;
		
		oerl.hdlrs = [];
		
		oerl.hdlrs.push(hdlr);
		
		if (getStyle(elm, 'position') == 'static') {
			elm.style.position = 'relative';
		}
		
        var obj = oerl.rszTrigger = document.createElement('object');
		
		mdf_cls(obj, 'add', 'vis-hid pos-abs w-100 h-100 dsp-blk');
		
		obj.style.zIndex = '-99999';
		obj.style.pointerEvents = 'none';
		obj.style.overflow = 'hidden';
		obj.style.top = '0';
		obj.style.left = '0';
		
		obj.type = 'text/html';
		obj.data = 'about:blank';
		
		obj.addEventListener('load', function() {
			var wdw = this.contentDocument.defaultView;
			
			wdw.rszElm = elm;
			
			//because it takes time for obj to load
			//there is a window of time when even though the resize is supposed to be registered
			//but during that time the resize handlers are never run
			//to compensate this we mannually trigger the handler once when the obj loads
			//call allows you to set the obj for the this var
			//also note apply can be used if the arguments are stored in array
			//example: fct.call(thisVar, arg1, arg2, blablabla);
			//fct.apply(thisVar, arrayOfArgs);
			//a for array, c for comma
			//alternatively, we can create an custom event resize, and then dispatch it
			//but this way is simpler
			optElmRszLsnr.rszHdlr.call(wdw);
			
			wdw.addEventListener('resize', optElmRszLsnr.rszHdlr);
		}, false);
		
		elm.appendChild(obj);
	}
	else {
		//if hdlr not already added
		//if index not found
		if (elm.optElmRszLsnr.hdlrs.indexOf(hdlr) == -1) {
			elm.optElmRszLsnr.hdlrs.push(hdlr);
		}
	}
};

optElmRszLsnr.rmv = function(elm, hdlr) {
	var idx = elm.optElmRszLsnr.hdlrs.indexOf(hdlr);
	
	//if index found
	if (idx != -1) {
		//we remove the hdlr
		elm.optElmRszLsnr.hdlrs.splice(idx, 1);
		
		//then if the array is empty
		if (elm.optElmRszLsnr.hdlrs.length <= 0) {
			//we remove the obj
			elm.removeChild(elm.optElmRszLsnr.rszTrigger);
			
			//remove the optElmRszLsnr data
			elm.optElmRszLsnr = undefined;
		}
	}
};
//end optElmRszLsnr

//optScrlLsnr
//optimized scroll listener class does the same thing as optElmRszLsnr, because the scroll event can fire at a very high rate
//we sync the event with animation frame because scroll event is an event of visual change
//so we assume the event handler will also be visual related, and thus sync it with animation frame
//in addition, we also reduce the fire rate, to approximately 24 frames
//if your event handler is not visual related and need to fire at precisely when scroll happens, you should not use this class
//this frame rate is lower than the scroll result is drawn by the browser, because the the screen usually has a higher refresh rate
//thus, you will see the event drawn less than the actual scroll, and out of sync
//however, this is an acceptable loss for websites, in exchange for a performance gain
//	the evt argument is passed to each handler
var optScrlLsnr = {};

//the 24 fps is the intended frame rate, however, it is not possible to force browser to run this exactly 24 time per second
//we should try run at least 24 times per second, but not exceeding 24 fps too much
//the problem is, we don't know when a session of scroll starts, and when it ends
//whether a scroll is a consecutive scroll of the previous one, or a start of a new session
//if it is a new session, then it have to reset the intended time stamp, otherwise it will have to conform to the existing intended time stamp
//to achieve this, we set an expire ts for the intend ts
//if the intend ts has expired, we reset the intend ts
optScrlLsnr.fps = 24;
optScrlLsnr.interval = 1000 / optScrlLsnr.fps;//			just to be crazy precise, we'll let browser calculate what the interval is

//the animation frame handler
optScrlLsnr.afHdlr = function(evt, ts, rszEvtThis) {//	rszEvtThis is the this var in the resize event
	var osl = rszEvtThis.optScrlLsnr;
	
	//at next repaint we check the difference between last run time stamp and the new time stamp in milliseconds
	if (ts >= osl.intendTs) {
		//then we set ts to the new ts and run the handlers
		osl.lastTs = ts;
		
		osl.hdlrs.forEach(function(hdlr) {
			hdlr(evt, rszEvtThis);
		});
		
		//set intend ts for next paint
		//if old intendTs expired
		if (ts >= osl.expireTs) {
			//reset to ts
			osl.intendTs = ts;
		}
		
		osl.intendTs += optScrlLsnr.interval;
		
		//update expireTs
		//ts is allowed to exceed intendTs, but can only exceed by 1 frame
		//if more, then it is expired
		osl.expireTs = osl.intendTs + optScrlLsnr.interval;
		
		//and we say is finished running
		osl.running = false;
	}
	else {
		//we dont run the handlers, but postpone it to the next animation frame where we will check time stamp again.
		window.requestAnimationFrame(function(newTs) {
			optScrlLsnr.afHdlr(evt, newTs, rszEvtThis);
		});
	}
};

optScrlLsnr.rszHdlr = function(evt) {
	//detect script scroll
	if (this.scriptScrl) {
		this.scriptScrl = false;
		
		return true;
	}
	
	//if running is false, last handler has finished execution and is no longer running
	if (!this.optScrlLsnr.running) {
		//we then start a new execution
		this.optScrlLsnr.running = true;
		
		var rszEvtThis = this;
		
		//we request the hdlrs to only run at next repaint
		window.requestAnimationFrame(function(ts) {
			optScrlLsnr.afHdlr(evt, ts, rszEvtThis);
		});
	}
};

optScrlLsnr.add = function(elm, hdlr) {// hdlr: the event handler to be added to the listener
	//if not inited
	if (!elm.optScrlLsnr) {
		//init
		var osl = elm.optScrlLsnr = {};
		
		osl.running = false;
		osl.lastTs = 0;
		osl.intendTs = 0;
		osl.expireTs = 0;
		
		osl.hdlrs = [];
		
		osl.hdlrs.push(hdlr);
			
		elm.addEventListener('scroll', this.rszHdlr);
	}
	else {
		//if hdlr not already added
		//if index not found
		if (elm.optScrlLsnr.hdlrs.indexOf(hdlr) == -1) {
			elm.optScrlLsnr.hdlrs.push(hdlr);
		}
	}
};

optScrlLsnr.rmv = function(elm, hdlr) {
	var idx = elm.optScrlLsnr.hdlrs.indexOf(hdlr);
	
	//if index found
	if (idx != -1) {
		//we remove the hdlr
		elm.optScrlLsnr.hdlrs.splice(idx, 1);
		
		//then if the array is empty
		if (elm.optScrlLsnr.hdlrs.length <= 0) {
			elm.removeEventListener('scroll', this.rszHdlr);
			
			//remove the optScrlLsnr data
			elm.optScrlLsnr = undefined;
		}
	}
};
//end optScrlLsnr

//optAnimScrlLsnr
//this static class differs from the above in that this one syncs with animation frame and has no fps limit
//	the evt argument is passed to each handler
var optAnimScrlLsnr = {};

optAnimScrlLsnr.rszHdlr = function(evt) {
	//detect script scroll
	if (this.scriptScrl) {
		this.scriptScrl = false;
		
		return true;
	}
	
	var osl = this.optAnimScrlLsnr;
	
	//if running is false, last handler has finished execution and is no longer running
	if (!osl.running) {
		//we then start a new execution
		osl.running = true;
		
		//we request the hdlrs to only run at next repaint
		window.requestAnimationFrame(function() {
			osl.hdlrs.forEach(function(hdlr) {
				hdlr(evt);
			});

			//and we say is finished running
			osl.running = false;
		});
	}
};

optAnimScrlLsnr.add = function(elm, hdlr) {// hdlr: the event handler to be added to the listener
	//if not inited
	if (!elm.optAnimScrlLsnr) {
		//init
		var osl = elm.optAnimScrlLsnr = {};
		
		osl.running = false;
		
		osl.hdlrs = [];
		
		osl.hdlrs.push(hdlr);
			
		elm.addEventListener('scroll', this.rszHdlr);
	}
	else {
		//if hdlr not already added
		//if index not found
		if (elm.optAnimScrlLsnr.hdlrs.indexOf(hdlr) == -1) {
			elm.optAnimScrlLsnr.hdlrs.push(hdlr);
		}
	}
};

optAnimScrlLsnr.rmv = function(elm, hdlr) {
	var idx = elm.optAnimScrlLsnr.hdlrs.indexOf(hdlr);
	
	//if index found
	if (idx != -1) {
		//we remove the hdlr
		elm.optAnimScrlLsnr.hdlrs.splice(idx, 1);
		
		//then if the array is empty
		if (elm.optAnimScrlLsnr.hdlrs.length <= 0) {
			//remove the optScrlLsnr data
			elm.optAnimScrlLsnr = undefined;
			
			elm.removeEventListener('scroll', this.rszHdlr);
		}
	}
};
//end optScrlLsnr

//optMseMveLsnr
//this class is an adaptation of the optScrlLsnr class
//it is very similar to that class, see that class's documentation
var optMseMveLsnr = {};

optMseMveLsnr.fps = 24;
optMseMveLsnr.interval = 1000 / optScrlLsnr.fps;//			just to be crazy precise, we'll let browser calculate what the interval is

//the animation frame handler
optMseMveLsnr.afHdlr = function(evt, ts, mmEvtThis) {//	rszEvtThis is the this var in the resize event
	var omml = mmEvtThis.optMseMveLsnr;
	
	//at next repaint we check the difference between last run time stamp and the new time stamp in milliseconds
	if (ts >= omml.intendTs) {
		//then we set ts to the new ts and run the handlers
		omml.lastTs = ts;
		
		omml.hdlrs.forEach(function(hdlr) {
			hdlr(evt);
		});
		
		//set intend ts for next paint
		//if old intendTs expired
		if (ts >= omml.expireTs) {
			//reset to ts
			omml.intendTs = ts;
		}
		
		omml.intendTs += optMseMveLsnr.interval;
		
		//update expireTs
		//ts is allowed to exceed intendTs, but can only exceed by 1 frame
		//if more, then it is expired
		omml.expireTs = omml.intendTs + optMseMveLsnr.interval;
		
		//and we say is finished running
		omml.running = false;
	}
	else {
		//we dont run the handlers, but postpone it to the next animation frame where we will check time stamp again.
		window.requestAnimationFrame(function(newTs) {
			optMseMveLsnr.afHdlr(evt, newTs, mmEvtThis);
		});
	}
};

optMseMveLsnr.mmHdlr = function(evt) {
	//if running is false, last handler has finished execution and is no longer running
	if (!this.optMseMveLsnr.running) {
		//we then start a new execution
		this.optMseMveLsnr.running = true;
		
		var mmEvtThis = this;
		
		//we request the hdlrs to only run at next repaint
		window.requestAnimationFrame(function(ts) {
			optMseMveLsnr.afHdlr(evt, ts, mmEvtThis);
		});
	}
};

optMseMveLsnr.add = function(elm, hdlr) {// hdlr: the event handler to be added to the listener
	//if not inited
	if (!elm.optMseMveLsnr) {
		//init
		var omml = elm.optMseMveLsnr = {};
		
		omml.running = false;
		omml.lastTs = 0;
		omml.intendTs = 0;
		omml.expireTs = 0;
		
		omml.hdlrs = [];
		
		omml.hdlrs.push(hdlr);
			
		elm.addEventListener('mousemove', this.mmHdlr);
	}
	else {
		//if hdlr not already added
		//if index not found
		if (elm.optMseMveLsnr.hdlrs.indexOf(hdlr) == -1) {
			elm.optMseMveLsnr.hdlrs.push(hdlr);
		}
	}
};

optMseMveLsnr.rmv = function(elm, hdlr) {
	var idx = elm.optMseMveLsnr.hdlrs.indexOf(hdlr);
	
	//if index found
	if (idx != -1) {
		//we remove the hdlr
		elm.optMseMveLsnr.hdlrs.splice(idx, 1);
		
		//then if the array is empty
		if (elm.optMseMveLsnr.hdlrs.length <= 0) {
			//remove the optScrlLsnr data
			elm.optMseMveLsnr = undefined;
			
			//remove listener
			elm.removeEventListener('mousemove', this.mmHdlr);
		}
	}
};
//end optMseMveLsnr

//deprecated, use the promise version in shpsCmm instead
//left for backward compatibility
function domReadyDo(cb) {
	if ((document.readyState == 'interactive') || (document.readyState == 'complete')) {
		cb();
	} else {
		document.addEventListener('DOMContentLoaded', cb, false);
	}
}

function capitalize(str) {
	return str && str[0].toUpperCase()+str.slice(1);
}

//get css rem in pixels
function getRemPx() {
	return parseFloat(getStyle(document.documentElement, 'font-size'));
}

//get elm dimension by using a clone
function getElmCloneDimension(elm) {
	var clone = elm.cloneNode(true);
	var parent = elm.parentNode;
	
	clone.classList.add('vsb-hid', 'pos-abs');
	clone.classList.remove('dsp-non');
	clone.style.height = 'auto';
	clone.style.width = 'auto';
	
	parent.appendChild(clone);
	
	var dimension = {
		w:clone.offsetWidth,
		h:clone.offsetHeight
	};
	
	parent.removeChild(clone);

	return dimension;
}

//===========================================================================section Anim
//evt, elmId, offset y, offset x (in px)
//vertical position: above, below (where does the elm appear)
//hor pos: left, right
//whether is position fixed, if not, then is position absolute
//vertical move: btm
//hor move: rgt
//by default, they are moved with top and lft
function follow_mouse(event, elm, osy, osx, vpos, hpos, fixed, vm, hm) { //offset
	var cposx = event.pageX;					//cursor position x relative to page
	var cposy = event.pageY;
	var cprwx = event.clientX;	//cposx - elm.scrollLeft, cursor position x relative to viewport
	var cprwy = event.clientY;
	var elew = elm.offsetWidth;
	var eleh = elm.offsetHeight;

	switch (vpos) {
		case 'above':
			if (cprwy < (eleh + osy)) {
				vpos = 'below';
			}
			
			break;
		case 'below':
			break;
		default:
			vpos = 'below';
	}
	
	switch (hpos) {
		case 'left':
			if (cprwx < (elew + osx)) {
				hpos = 'right';
			}
			
			break;
		case 'right':
			break;
		default:
			hpos = 'right';
	}

	if (fixed) {
		cposy = cprwy;
		cposx = cprwx;
	}
	
	var eletop;
	var elelft;
	
	switch (vpos) {
		case 'above':
			eletop = cposy - eleh - osy;
			
			break;
		case 'below':
			eletop = cposy + osy;
	}
	
	switch (hpos) {
		case 'left':
			elelft = cposx - elew - osx;
			break;
		case 'right':
			elelft = cposx + osx;
	}
	
	if (fixed && vm == 'btm') {
		elm.style.bottom = (document.documentElement.clientHeight - eletop - eleh)+'px';
	}
	else {
		elm.style.top = eletop+'px';
	}
	if (fixed && hm == 'rgt') {
		elm.style.right = (document.documentElement.clientWidth - elelft - elew)+'px';
	}
	else {
		elm.style.left = elelft+'px';
	}
}

function move(eleid, drc, pxl) {
	var ele = document.getElementById(eleid);
	
	switch (drc) {
		case 'left':
			ele.style.left = (ele.offsetLeft-pxl)+'px';
			
			break;
		case 'right':
			ele.style.left = (ele.offsetLeft+pxl)+'px';
			
			break;
		case 'up':
			ele.style.top = (ele.offsetTop-pxl)+'px';
			
			break;
		case 'down':
			ele.style.top = (ele.offsetTop+pxl)+'px';
	}
}

/**
 * BezierEasing - use bezier curve for transition easing function
 * by Gaëtan Renaudeau 2014 – MIT License
 *
 * Credits: is based on Firefox's nsSMILKeySpline.cpp
 * Usage:
 * var spline = BezierEasing(0.25, 0.1, 0.25, 1.0)
 * spline(x) => returns the easing value | x must be in [0, 1] range
 *
 */
(function (definition) {
  if (typeof exports === "object") {
    module.exports = definition();
  } else if (typeof define === 'function' && define.amd) {
    define([], definition);
  } else {
    window.BezierEasing = definition();
  }
}(function () {
  var global = this;

  // These values are established by empiricism with tests (tradeoff: performance VS precision)
  var NEWTON_ITERATIONS = 4;
  var NEWTON_MIN_SLOPE = 0.001;
  var SUBDIVISION_PRECISION = 0.0000001;
  var SUBDIVISION_MAX_ITERATIONS = 10;

  var kSplineTableSize = 11;
  var kSampleStepSize = 1.0 / (kSplineTableSize - 1.0);

  var float32ArraySupported = ('Float32Array' in window);

  function A (aA1, aA2) { return 1.0 - 3.0 * aA2 + 3.0 * aA1; }
  function B (aA1, aA2) { return 3.0 * aA2 - 6.0 * aA1; }
  function C (aA1)      { return 3.0 * aA1; }

  // Returns x(t) given t, x1, and x2, or y(t) given t, y1, and y2.
  function calcBezier (aT, aA1, aA2) {
    return ((A(aA1, aA2)*aT + B(aA1, aA2))*aT + C(aA1))*aT;
  }

  // Returns dx/dt given t, x1, and x2, or dy/dt given t, y1, and y2.
  function getSlope (aT, aA1, aA2) {
    return 3.0 * A(aA1, aA2)*aT*aT + 2.0 * B(aA1, aA2) * aT + C(aA1);
  }

  function binarySubdivide (aX, aA, aB, mX1, mX2) {
    var currentX, currentT, i = 0;
    do {
      currentT = aA + (aB - aA) / 2.0;
      currentX = calcBezier(currentT, mX1, mX2) - aX;
      if (currentX > 0.0) {
        aB = currentT;
      } else {
        aA = currentT;
      }
    } while (Math.abs(currentX) > SUBDIVISION_PRECISION && ++i < SUBDIVISION_MAX_ITERATIONS);
    return currentT;
  }

  function BezierEasing (mX1, mY1, mX2, mY2) {
    // Validate arguments
    if (arguments.length !== 4) {
      throw new Error("BezierEasing requires 4 arguments.");
    }
    for (var i=0; i<4; ++i) {
      if (typeof arguments[i] !== "number" || isNaN(arguments[i]) || !isFinite(arguments[i])) {
        throw new Error("BezierEasing arguments should be integers.");
      }
    }
    if (mX1 < 0 || mX1 > 1 || mX2 < 0 || mX2 > 1) {
      throw new Error("BezierEasing x values must be in [0, 1] range.");
    }

    var mSampleValues = float32ArraySupported ? new Float32Array(kSplineTableSize) : new Array(kSplineTableSize);

    function newtonRaphsonIterate (aX, aGuessT) {
      for (var i = 0; i < NEWTON_ITERATIONS; ++i) {
        var currentSlope = getSlope(aGuessT, mX1, mX2);
        if (currentSlope === 0.0) return aGuessT;
        var currentX = calcBezier(aGuessT, mX1, mX2) - aX;
        aGuessT -= currentX / currentSlope;
      }
      return aGuessT;
    }

    function calcSampleValues () {
      for (var i = 0; i < kSplineTableSize; ++i) {
        mSampleValues[i] = calcBezier(i * kSampleStepSize, mX1, mX2);
      }
    }

    function getTForX (aX) {
      var intervalStart = 0.0;
      var currentSample = 1;
      var lastSample = kSplineTableSize - 1;

      for (; currentSample != lastSample && mSampleValues[currentSample] <= aX; ++currentSample) {
        intervalStart += kSampleStepSize;
      }
      --currentSample;

      // Interpolate to provide an initial guess for t
      var dist = (aX - mSampleValues[currentSample]) / (mSampleValues[currentSample+1] - mSampleValues[currentSample]);
      var guessForT = intervalStart + dist * kSampleStepSize;

      var initialSlope = getSlope(guessForT, mX1, mX2);
      if (initialSlope >= NEWTON_MIN_SLOPE) {
        return newtonRaphsonIterate(aX, guessForT);
      } else if (initialSlope === 0.0) {
        return guessForT;
      } else {
        return binarySubdivide(aX, intervalStart, intervalStart + kSampleStepSize, mX1, mX2);
      }
    }

    var _precomputed = false;
    function precompute() {
      _precomputed = true;
      if (mX1 != mY1 || mX2 != mY2)
        calcSampleValues();
    }

    var f = function (aX) {
      if (!_precomputed) precompute();
      if (mX1 === mY1 && mX2 === mY2) return aX; // linear
      // Because JavaScript number are imprecise, we should guarantee the extremes are right.
      if (aX === 0) return 0;
      if (aX === 1) return 1;
      return calcBezier(getTForX(aX), mY1, mY2);
    };

    f.getControlPoints = function() { return [{ x: mX1, y: mY1 }, { x: mX2, y: mY2 }]; };

    var args = [mX1, mY1, mX2, mY2];
    var str = "BezierEasing("+args+")";
    f.toString = function () { return str; };

    var css = "cubic-bezier("+args+")";
    f.toCSS = function () { return css; };

    return f;
  }

  // CSS mapping
  BezierEasing.css = {
    "ease":        BezierEasing(0.25, 0.1, 0.25, 1.0),
    "linear":      BezierEasing(0.00, 0.0, 1.00, 1.0),
    "ease-in":     BezierEasing(0.42, 0.0, 1.00, 1.0),
    "ease-out":    BezierEasing(0.00, 0.0, 0.58, 1.0),
    "ease-in-out": BezierEasing(0.42, 0.0, 0.58, 1.0)
  };

  return BezierEasing;
}));

//begin the transit class
//fps and delay are optional
//fps can only be undefined, positive number, or false
//if fps is false, will allow the anim to be drawn when ever possible, thus sync with browser repaint rate 
//if fps not set (undefined), fps defaults to 24, 
//if fps is negative, 0, or other invalid value, will cause error on the animation
function transit(frame, start, to, duration, fps, delay) {	//define class, in js, same as defining a function, duration is in milliseconds
	this.frame = frame;
	this.start = start;
	this.to = to;
	this.duration = duration;
	
	if (fps === undefined) {//	if fps is undefined
		//we'll set the frame rate using this variable
		//24 is enough for motion pictures
		//the stutter you might experience is difficult to explain
		//lack of motion blur maybe the reason why 24 fps is not enough.
		//but because this is a website and animation is not extremely important, we'll settle for 24fps to save cpu usage
		fps = 24;
	}
	
	if (fps === false) {
		this.interval = 0;//			set interval to 0, and just draw frames whenever browser repaints
	}
	else {
		this.interval = 1000 / fps;
	}
	
	this.delay = delay || 0;

	this.totalChange = this.to - this.start;
	this.timePassed = 0;
	this.progress = 0;
	this.delta = undefined;				//object has to be instantiated before delta can be assigned
	
	this.lastTs = undefined;//					last time stamp
	this.intendTs = 0;
	
	this.state = 'stop';
	
	this.transitEnd = function() {
	};
}

//the ease out is the quadEaseOut
//the quadritic easings are the most gentle easing in terms of speed at moving across from start to end
//other easings appear the animated item is move very fast at a high velocity
transit.prototype.easeOut = function() {	//define the easeOut delta method
	return -this.totalChange * this.progress * (this.progress - 2) + this.start;
};

transit.easeMiddleBezier = BezierEasing(0, .4, 1, .5);

transit.prototype.easeMiddle = function() {
	return this.totalChange * transit.easeMiddleBezier(this.progress) + this.start;
};

transit.prototype.quadEaseIn = function() {
	var p = this.progress;
	
	return this.totalChange * p * p + this.start;
};
transit.prototype.quadEaseInOut = function() {
	var newP = this.progress * 2;
	
	if (newP < 1) {
		return this.totalChange / 2 * newP * newP + this.start;
	}
	
	newP--;
	
	return -this.totalChange / 2 * (newP * (newP - 2) - 1) + this.start;
};

transit.prototype.step = function(ts) {//	define the step method that calculates the next frame
	//discard if does not meet the intended ts
	
	//we check the time stamp against the intended time stamp
	//this mean, if refresh rate is lower than our intended frame rate, it will be capped to the refresh rate
	//if it is heigher than our frame rate, the actaul repaint maybe a little later than our intended time stamp
	//but will be capped at the frame rate, and will not be less than our intended frame rate
	if (ts >= this.intendTs) {
		this.timePassed += ts - this.lastTs;//				update the time passed
		
		this.lastTs = ts;
		
		this.progress = this.timePassed / this.duration;//	update the progress
		
		if (this.progress > 1) {
			this.progress = 1;
		}

		this.frame(this.delta());//				execute the given frame function with the value to draw the next frame;

		this.intendTs += this.interval;
	}
	
	if (this.progress < 1) {
		if (this.state == 'play') {
			this.play();
		}
	} else {
		this.stop();
	}
};

transit.prototype.addEventListener = function(evt, fct) {
	switch (evt) {
		case 'end':
			var oldFtc = this.transitEnd;
			
			this.transitEnd = function() {
				oldFtc();
				fct();
			};
			
			break;
		default:
			return false;
	}
};

transit.prototype.play = function() {//		the play method
	if (this.progress >= 1) {
		this.replay();
		
		return;
	}
	
	this.state = 'play';
	
	if (!this.lastTs) {
		this.lastTs = this.intendTs = performance.now();
	}
	
	var theThis = this;//					because in setInterval, this refers to the window, so we store this in a variable
	
	this.afId = window.requestAnimationFrame(function(ts) {
		theThis.step(ts);//					must be wrapped in the anonymous function
	});
};

transit.prototype.replay = function() {
	this.timePassed = 0;
	this.progress = 0;
	//reset time stamp
	this.lastTs = undefined;
	
	this.play();
};

transit.prototype.pause = function() {
	this.state = 'pause';
};

transit.prototype.stop = function() {
	this.state = 'stop';
	
	//run on transit end handlers
	this.transitEnd();
};
//end the transit class

//begin the transScroll module
function transScroll(elm, to, hor, dur, cb) { //duration is in seconds
	var duration;
	var tsTrans;
	
	if (hor) {
		if (dur) {
			duration = dur * 1000;
		} else {
			duration = Math.abs(to - elm.scrollLeft);	//turns all negative to positive values, aka absolute value
		}
		
		tsTrans = new transit(function(value) {
			elm.scriptScrl = true;//setting sriptScrl to true does not trigger scroll events; same for scriptRsz, if you want to trigger the events, dont set this
			
			elm.scrollLeft = value;
		}, elm.scrollLeft, to, duration);
	} else {
		if (dur) {
			duration = dur * 1000;
		} else {
			duration = Math.abs(to - elm.scrollTop);	//turns all negative to positive values, aka absolute value
		}
		
		tsTrans = new transit(function(value) {
			elm.scriptScrl = true;
			
			elm.scrollTop = value;
		}, elm.scrollTop, to, duration);
	}
	
	//the delta method must be assigned
	tsTrans.delta = tsTrans.quadEaseInOut;

	//scrl evt detection function, transScroll does not trigger scrl evt
	//so if scrl evt is detected, we stop transcroll
	function tsScrlDetect() {
		tsTrans.stop();
	}
	
	//if tsTrans is stopped of completed, we remove the detection
	var transEndFcts = [function() {
		optScrlLsnr.rmv(elm, tsScrlDetect);
	}];
	
	if (cb) {
		transEndFcts.push(cb);
	}
	
	tsTrans.addEventListener('end', function() {
		transEndFcts.forEach(function(cb) {
			cb();
		});
	});

	//listen for user scrolls/non-script scroll and end script scroll
	optScrlLsnr.add(elm, tsScrlDetect);
	
	//scroll the shit out of it
	tsTrans.play();
}
//end the transScroll module

//start basic tween
function Delegate() {}

Delegate.create = function (o, f) {
	var a = [];
	var l = arguments.length ;
	
	for (var i = 2 ; i < l ; i++) {
		a[i-2] = arguments[i];
	}
	
	return function() {
		var aP = [].concat(arguments, a) ;
		f.apply(o, aP);
	};
};

var Tween = function(obj, prop, func, begin, finish, duration, suffixe) {
	this.init(obj, prop, func, begin, finish, duration, suffixe);
};

var t = Tween.prototype;

t.obj = {};
t.prop='';
t.func = function (t, b, c, d) { return c*t/d + b; };
t.begin = 0;
t.change = 0;
t.prevTime = 0;
t.prevPos = 0;
t.looping = false;
t._duration = 0;
t._time = 0;
t._pos = 0;
t._position = 0;
t._startTime = 0;
t._finish = 0;
t.name = '';
t.suffixe = '';
t._listeners = [];	
t.setTime = function(t){
	this.prevTime = this._time;
	
	if (t > this.getDuration()) {
		if (this.looping) {
			this.rewind (t - this._duration);
			this.update();
			this.broadcastMessage('onMotionLooped',{target:this,type:'onMotionLooped'});
		}
		else {
			this._time = this._duration;
			this.update();
			this.stop();
			this.broadcastMessage('onMotionFinished',{target:this,type:'onMotionFinished'});
		}
	}
	else if (t < 0) {
		this.rewind();
		this.update();
	}
	else {
		this._time = t;
		this.update();
	}
};
t.getTime = function() {
	return this._time;
};
t.setDuration = function(d) {
	this._duration = (d == null || d <= 0) ? 100000 : d;
};
t.getDuration = function() {
	return this._duration;
};
t.setPosition = function(p) {
	this.prevPos = this._pos;
	
	var a = (this.suffixe != '') ? this.suffixe : '';
	
	this.obj[this.prop] = Math.round(p) + a;
	this._pos = p;
	
	this.broadcastMessage('onMotionChanged', {target:this, type:'onMotionChanged'});
};
t.getPosition = function(t) {
	if (t == undefined) {
		t = this._time;
	}
	
	return this.func(t, this.begin, this.change, this._duration);
};
t.setFinish = function(f) {
	this.change = f - this.begin;
};
t.geFinish = function() {
	return this.begin + this.change;
};
t.init = function(obj, prop, func, begin, finish, duration, suffixe) {
	if (!arguments.length) {
		return;
	}
	
	this._listeners = [];
	this.addListener(this);
	
	if (suffixe) {
		this.suffixe = suffixe;
	}
	
	this.obj = obj;
	this.prop = prop;
	this.begin = begin;
	this._pos = begin;
	this.setDuration(duration);
	
	if (func!=null && func!='') {
		this.func = func;
	}
	
	this.setFinish(finish);
};
t.start = function() {
	this.rewind();
	this.startEnterFrame();
	this.broadcastMessage('onMotionStarted',{target:this, type:'onMotionStarted'});
	//alert('in');
};
t.rewind = function(t) {
	this.stop();
	this._time = (t == undefined) ? 0 : t;
	this.fixTime();
	this.update();
};
t.fforward = function() {
	this._time = this._duration;
	this.fixTime();
	this.update();
};
t.update = function() {
	this.setPosition(this.getPosition(this._time));
};
t.startEnterFrame = function() {
	this.stopEnterFrame();
	this.isPlaying = true;
	this.onEnterFrame();
};
t.onEnterFrame = function() {
	if (this.isPlaying) {
		this.nextFrame();
		setTimeout(Delegate.create(this, this.onEnterFrame), 0);
	}
};
t.nextFrame = function() {
	this.setTime((this.getTimer() - this._startTime) / 1000);
};
t.stop = function() {
	this.stopEnterFrame();
	this.broadcastMessage('onMotionStopped', {target:this,type:'onMotionStopped'});
};
t.stopEnterFrame = function() {
	this.isPlaying = false;
};
t.continueTo = function(finish, duration) {
	this.begin = this._pos;
	
	this.setFinish(finish);
	
	if (this._duration != undefined) {
		this.setDuration(duration);
	}
	
	this.start();
};
t.resume = function() {
	this.fixTime();
	this.startEnterFrame();
	this.broadcastMessage('onMotionResumed',{target:this, type:'onMotionResumed'});
};
t.yoyo = function () {
	this.continueTo(this.begin, this._time);
};

t.addListener = function(o) {
	this.removeListener (o);
	
	return this._listeners.push(o);
};
t.removeListener = function(o) {
	var a = this._listeners;
	var i = a.length;
	
	while (i--) {
		if (a[i] == o) {
			a.splice (i, 1);
			
			return true;
		}
	}
	
	return false;
};
t.broadcastMessage = function() {
	var arr = [];
	
	for (var i = 0; i < arguments.length; i++){
		arr.push(arguments[i]);
	}
	
	var e = arr.shift();
	var a = this._listeners;
	var l = a.length;
	
	for (var i=0; i<l; i++){
		if (a[i][e]) {
			a[i][e].apply(a[i], arr);
		}
	}
};
t.fixTime = function() {
	this._startTime = this.getTimer() - this._time * 1000;
};
t.getTimer = function() {
	return new Date().getTime()-this._time;
};
Tween.backEaseIn = function(t,b,c,d,a,p) {
	if (s == undefined) {
		var s = 1.70158;
	}
	
	return c*(t/=d)*t*((s+1)*t-s)+b;
};
Tween.backEaseOut = function(t,b,c,d,a,p) {
	if (s == undefined) {
		var s = 1.70158;
	}
	
	return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
};
Tween.backEaseInOut = function(t,b,c,d,a,p) {
	if (s == undefined) {
		var s = 1.70158;
	}
	if ((t /= (d / 2)) < 1) {
		return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
	}
	
	return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
};
Tween.elasticEaseIn = function(t,b,c,d,a,p) {
		if (t==0) {
			return b;
		}
		if ((t/=d)==1) {
			return b+c;
		}
		if (!p) {
			p = d * .3;
		}
		if (!a || a < Math.abs(c)) {
			a=c;
			
			var s = p / 4;
		}
		else {
			var s = p / (2 * Math.PI) * Math.asin(c / a);
		}
		
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
};
Tween.elasticEaseOut = function (t,b,c,d,a,p) {
		if (t==0) {
			return b;
		}
		if ((t/=d)==1) {
			return b+c;
		}
		if (!p) {
			p = d * .3;
		}
		if (!a || a < Math.abs(c)) {
			a=c;
			
			var s=p/4;
		}
		else {
			var s = p/(2*Math.PI) * Math.asin (c/a);
		}
		
		return (a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b);
};
Tween.elasticEaseInOut = function (t,b,c,d,a,p) {
	if (t==0) {
		return b;
	}
	if ((t /= (d / 2)) == 2) {
		return b+c;
	}
	if (!p) {
		var p = d * (.3 * 1.5);
	}
	if (!a || a < Math.abs(c)) {
		var a = c;
		var s=p/4;
	}
	else {
		var s = p / (2 * Math.PI) * Math.asin(c / a);
	}
	if (t < 1) {
		return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
	}
	
	return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * .5 + c + b;
};
Tween.bounceEaseOut = function(t,b,c,d) {
	if ((t/=d) < (1/2.75)) {
		return c*(7.5625*t*t) + b;
	}
	else if (t < (2/2.75)) {
		return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
	}
	else if (t < (2.5/2.75)) {
		return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
	}
	else {
		return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
	}
};
Tween.bounceEaseIn = function(t,b,c,d) {
	return c - Tween.bounceEaseOut (d-t, 0, c, d) + b;
};
Tween.bounceEaseInOut = function(t,b,c,d) {
	if (t < d/2) {
		return Tween.bounceEaseIn(t * 2, 0, c, d) * .5 + b;
	}
	else {
		return Tween.bounceEaseOut(t * 2 - d, 0, c, d) * .5 + c * .5 + b;
	}
};
Tween.strongEaseInOut = function(t,b,c,d) {
	return c*(t/=d)*t*t*t*t + b;
};
Tween.regularEaseIn = function(t,b,c,d) {
	return c*(t/=d)*t + b;
};
Tween.regularEaseOut = function(t,b,c,d) {
	return -c *(t/=d)*(t-2) + b;
};
Tween.regularEaseInOut = function(t,b,c,d) {
	if ((t /= (d / 2)) < 1) {
		return c/2*t*t + b;
	}
	
	return -c/2 * ((--t)*(t-2) - 1) + b;
};
Tween.strongEaseIn = function(t,b,c,d) {
	return c*(t /= d)*t*t*t*t + b;
};
Tween.strongEaseOut = function(t,b,c,d) {
	return c*((t = t/d-1)*t*t*t*t + 1) + b;
};
Tween.strongEaseInOut = function(t,b,c,d) {
	if ((t /= (d / 2)) < 1) {
		return c/2*t*t*t*t*t + b;
	}
	
	return c/2*((t -= 2)*t*t*t*t + 2) + b;
};

OpacityTween.prototype = new Tween();
OpacityTween.prototype.constructor = Tween;
OpacityTween.superclass = Tween.prototype;

function OpacityTween(obj, func, fromOpacity, toOpacity, duration) {
	this.targetObject = obj;
	this.init({}, 'x', func, fromOpacity, toOpacity, duration);
}

var o = OpacityTween.prototype;

o.targetObject = {};
o.onMotionChanged = function(evt) {
	var v = evt.target._pos;
	var t = this.targetObject;
	
	t.style.opacity = v / 100;
	
	if (t.filters) {
		t.style.filter = 'progid:DXImageTransform.Microsoft.Alpha('+v+')';
	}
};

//obsolete!!!!! use css transition instead
function onload_fadeIn(eleOrId) {
	var ele;
	
	if (typeof (eleOrId) == 'string') {
		ele = document.getElementById(eleOrId);
	}
	else {
		ele = eleOrId;
	}
	
	var opaTwn = new OpacityTween(ele, Tween.regularEaseIn, 0, 100, 1.5);

	opaTwn.start();
}
//end basic tween
//===========================================================================end section anim