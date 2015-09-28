"use strict";function popup(t,e,n,r,s,i,o,a,c){if(!window.focus)return!0;var u;u="string"==typeof t?t:t.href;var l="width="+n+", height="+r;if(s){var d=(screen.width-n)/2,h=(screen.height-r)/2;l=l+", left="+d+", top="+h}i&&(l+=", location=yes"),o&&(l+=", status=yes"),a&&(l+=", scrollbars=yes"),c&&(l+=", resizable=yes"),window.open(u,e,l)}function refresh(){window.location.reload()}function clswdw(){self.close()}function pageback(){history.back()}function addEvt(t,e,n){window.addEventListener?t.addEventListener(e,n,!1):window.attachEvent&&t.attachEvent("on"+e,n)}function returnFalse(t){t.preventDefault?t.preventDefault():t.returnValue=!1}function classOf(t){return void 0===t?"Undefined":null===t?"Null":{}.toString.call(t).slice(8,-1)}function imgOnload(t,e,n){return t>=e?(n(),!0):!1}function add_imgOnload(t,e,n,r){if("Array"!=classOf(e))return!1;var s=[];e.forEach(function(e){var n=new Image,r=new Promise(function(t){n.addEventListener("load",t)});n.src=t.replace("/*replace*/",e),s.push(r)}),Promise.all(s).then(n,function(){r&&r()})}function the9imgsPreload(t,e,n){function r(){o++,imgOnload(o,a,n)}var s=new Array("tl","top","tr","lft","rgt","bl","btm","br","ctr"),i,o=0,a=8;e&&(a=9);for(var c=a-1,u=0;c>=u;u++)i=new Image,addEvt(i,"load",r),i.src=t.replace("/*replace*/",s[u])}function removeCheck(t,e){return e=e.replace(t," "),-1!=e.search(t)&&(e=removeCheck(t,e)),e}function mdf_cls(t,e,n){var r;r="object"==typeof t||"function"==typeof t?t:document.getElementById(t);var s=n.split(" "),i=r.classList;i[e].apply(i,s)}function add_eleOnload(t,e){onloadFct_arr[t]?onloadFct_arr[t].fcts.push(e):(onloadFct_arr[t]={},onloadFct_arr[t].fcts=[e],onloadFct_arr[t].intId=setInterval(function(){document.getElementById(t)&&(clearInterval(onloadFct_arr[t].intId),onloadFct_arr[t].fcts.forEach(function(t){t()}),onloadFct_arr[t]=void 0)},42))}function remove_eleOnload(t){onloadFct_arr[t]&&(clearInterval(onloadFct_arr[t].intId),onloadFct_arr[t]=void 0)}function checkElmOnload(t,e){document.getElementById(t)?e():add_eleOnload(t,e)}function add_fct(t,e){if("function"==typeof imgSet_fct_arr[t]){var n=imgSet_fct_arr[t];imgSet_fct_arr[t]=function(){n(),e()}}else imgSet_fct_arr[t]=e}function include(t,e,n,r,s,i){var o=!1;if(forEachObjProp(requestedExtFile_onloadFcts,function(t,n){n==e&&(s&&t.push(s),o=!0)}),o)return!0;var a,c=document.createElement("a");switch(t){case"script":a=function(t){return c.href=t.src,c.pathname==e?!0:!1};break;case"link":a=function(t){return c.href=t.href,c.pathname==e?!0:!1}}for(var u=document.getElementsByTagName(t),l=0;l<u.length;l++)if(a(u[l]))return s&&s(),!0;requestedExtFile_onloadFcts[e]=[],s&&requestedExtFile_onloadFcts[e].push(s);var d=document.createElement(t);switch(r&&(d.charset=r),n||(n="head"),d.addEventListener("load",function(){requestedExtFile_onloadFcts[e].forEach(function(t){t()}),delete requestedExtFile_onloadFcts[e]},!1),t){case"script":d.type="text/javascript",i!==!1&&(d.async=!0),d.src=e;break;case"link":d.type="text/css",d.rel="stylesheet",d.href=e}document.getElementsByTagName(n)[0].appendChild(d)}function getStyle(t,e){var n;n="object"==typeof t||"function"==typeof t?t:document.getElementById(t);var r=document.defaultView.getComputedStyle(n,null).getPropertyValue(e);return r}function imgPreload(t){var e=new Image;e.scr=t}function imgSetPreload(t,e){var n;e.forEach(function(e){n=t.replace("/*replace*/",e),imgPreload(n)})}function getRandomInt(t,e){return Math.floor(Math.random()*(e-t))+t}function ttlElmOnload(t){this.elmId=t,this.callback=function(){},this.useDefault=!0,this.lded=!1;var e={};this.addLdedCheck=function(t){e[t]=!1},this.setLded=function(t){e[t]=!0},this.ldFctArr={},this.triggerOnload=function(){var n=!0;Object.keys(e).forEach(function(t){0==e[t]&&(n=!1)}),n&&(this.lded=!0,this.callback(),this.useDefault&&mdf_cls(t,"remove","opa-0"))},this.resCallback=function(t){e[t]=!0,this.triggerOnload()},e[t]=!1;var n=this;this.ldFctArr[t]=function(){checkElmOnload(t,function(){n.resCallback(t)})}}function forEachObjProp(t,e){for(var n in t)t.hasOwnProperty(n)&&e(t[n],n)}function forEachNodeItem(t,e){for(var n=[],r=0;r<t.length;r++)n[r]=t[r];n.forEach(function(t,n){e(t,n)})}function domReadyDo(t){"interactive"==document.readyState||"complete"==document.readyState?t():document.addEventListener("DOMContentLoaded",t,!1)}function capitalize(t){return t&&t[0].toUpperCase()+t.slice(1)}function getRemPx(){return parseFloat(getStyle(document.documentElement,"font-size"))}function getElmCloneDimension(t){var e=t.cloneNode(!0),n=t.parentNode;e.classList.add("vsb-hid","pos-abs"),e.classList.remove("dsp-non"),e.style.height="auto",e.style.width="auto",n.appendChild(e);var r={w:e.offsetWidth,h:e.offsetHeight};return n.removeChild(e),r}function follow_mouse(t,e,n,r,s,i,o,a,c){var u=t.pageX,l=t.pageY,d=t.clientX,h=t.clientY,f=e.offsetWidth,p=e.offsetHeight;switch(s){case"above":p+n>h&&(s="below");break;case"below":break;default:s="below"}switch(i){case"left":f+r>d&&(i="right");break;case"right":break;default:i="right"}o&&(l=h,u=d);var m,v;switch(s){case"above":m=l-p-n;break;case"below":m=l+n}switch(i){case"left":v=u-f-r;break;case"right":v=u+r}o&&"btm"==a?e.style.bottom=document.documentElement.clientHeight-m-p+"px":e.style.top=m+"px",o&&"rgt"==c?e.style.right=document.documentElement.clientWidth-v-f+"px":e.style.left=v+"px"}function move(t,e,n){var r=document.getElementById(t);switch(e){case"left":r.style.left=r.offsetLeft-n+"px";break;case"right":r.style.left=r.offsetLeft+n+"px";break;case"up":r.style.top=r.offsetTop-n+"px";break;case"down":r.style.top=r.offsetTop+n+"px"}}function transit(t,e,n,r,s,i){this.frame=t,this.start=e,this.to=n,this.duration=r,void 0===s&&(s=24),this.interval=s===!1?0:1e3/s,this.delay=i||0,this.totalChange=this.to-this.start,this.timePassed=0,this.progress=0,this.delta=void 0,this.lastTs=void 0,this.intendTs=0,this.state="stop",this.transitEnd=function(){}}function transScroll(t,e,n,r){var s,i;n?(s=r?1e3*r:Math.abs(e-t.scrollLeft),i=new transit(function(e){t.scrollLeft=e},t.scrollLeft,e,s)):(s=r?1e3*r:Math.abs(e-t.scrollTop),i=new transit(function(e){t.scrollTop=e},t.scrollTop,e,s)),i.delta=i.quadEaseInOut,i.play()}function Delegate(){}function OpacityTween(t,e,n,r,s){this.targetObject=t,this.init({},"x",e,n,r,s)}function onload_fadeIn(t){var e;e="string"==typeof t?document.getElementById(t):t;var n=new OpacityTween(e,Tween.regularEaseIn,0,100,1.5);n.start()}var shpsCmm={};!function(){var t=shpsCmm.idbCache={};t.objStoreName="rscs",t.checkDb=new Promise(function(e,n){var r=indexedDB.open("idbCache",2);r.addEventListener("error",function(t){n()}),r.addEventListener("upgradeneeded",function(e){var n=r.result;n.objectStoreNames.contains(t.objStoreName)||n.createObjectStore(t.objStoreName,{keyPath:"url"})}),r.addEventListener("success",function(n){t.db=r.result,e()})}),t.cache={},t.set=function(e){return this.checkDb.then(function(){return new Promise(function(n,r){var s=t.db.transaction(t.objStoreName,"readwrite").objectStore(t.objStoreName).put(e);s.addEventListener("success",function(){t.cache[e.url]=e,n()})})})},t.rmv=function(e){return this.checkDb.then(function(){return new Promise(function(n,r){var s=t.db.transaction(t.objStoreName,"readwrite").objectStore(t.objStoreName)["delete"](e);s.addEventListener("success",function(){delete t.cache[e],n()}),s.addEventListener("error",r)})})},t.get=function(e){return this.cache[e]?Promise.resolve(this.cache[e]):this.checkDb.then(function(){return new Promise(function(n,r){var s=t.db.transaction(t.objStoreName).objectStore(t.objStoreName).get(e);s.addEventListener("success",function(){s.result?(t.cache[e]=s.result,n(t.cache[e])):r()}),s.addEventListener("error",r)})})};var e=shpsCmm.iCMgr={};e.ouCache={},e.checkCss_checkObjUrl=function(t,n,r){var s=/(.*\/)[^\/]*$/;n=s.exec(n)[1];var i=/\.\.\//g,o=(t.match(i)||[]).length;if(o>0)for(var a=/[^\/]+\/$/,c=0;o>c;c++)n=n.replace(a,"");var u=t.replace(i,""),l=n+u,d;return".css"==l.substr(l.length-4)&&(d="link"),new Promise(function(n,s){e.getObjUrl(l,d).then(function(e){r.push({subStr:t,repStr:e.objUrl})},function(){r.push({subStr:t,repStr:window.location.origin+l})}).then(function(){n()})})},e.checkCss=function(t,n){return new Promise(function(r,s){var i=new FileReader;i.addEventListener("loadend",function(){for(var s=i.result,o=[],a=/url\(([^\)]+)\)/g,c;null!==(c=a.exec(s));)o.push(c[1]);new Promise(function(t,r){if(o.length>0){var i=[],a=[];o.forEach(function(t){var r=e.checkCss_checkObjUrl(t,n,i);a.push(r)}),Promise.all(a).then(function(){i.forEach(function(t){s=s.replace("url("+t.subStr+")","url("+t.repStr+")")}),t()})}else t()}).then(function(){r(new Blob([s],{type:t.type}))})}),i.readAsText(t)})},e.ouCache_setAndReturn=function(t,e){return this.ouCache[t]={objUrl:URL.createObjectURL(e.blob),record:e},this.ouCache[t]},e.getObjUrl=function(n,r){if("link"==r){var s=n+"_tmpCopy";return this.ouCache[s]?Promise.resolve(this.ouCache[s]):t.get(n).then(function(t){return e.checkCss(t.blob,n)}).then(function(e){var n={blob:e,url:s};return t.set(n)}).then(function(){return t.get(s)}).then(function(t){return e.ouCache_setAndReturn(s,t)})}return this.ouCache[n]?Promise.resolve(this.ouCache[n]):t.get(n).then(function(t){return e.ouCache_setAndReturn(n,t)})},e.createRecordObj=function(t){if(200!==t.status)return!1;var e={dlDate:Date.now(),lastModified:t.getResponseHeader("Last-Modified")},n=/max\-age=([0-9]+)($|,)/;e.maxAge=n.exec(t.getResponseHeader("Cache-Control")),e.maxAge=null==e.maxAge?0:parseInt(e.maxAge[1]);var r=t.getResponseHeader("Etag");return r&&(e.eTag=r),e},e.checkExpire=function(t){return t.dlDate+1e3*t.maxAge<Date.now()?!0:!1},e.createHdrFrom=function(t){var e=[{hdr:"If-Modified-Since",value:t.lastModified}];return t.eTag&&e.push({hdr:"If-None-Match",value:t.eTag}),e},e.checkUpdate=function(t,e,n){return shpsCmm.createAjax("GET",e,void 0,n,this.createHdrFrom(t)).then(function(t){if(200==t.status)return t;throw t})},e.setBlob=function(n,r){var s=e.createRecordObj(r);return s?(s.blob=r.response,s.url=n,t.set(s)):void 0},e.dlAndSetBlob=function(t){return shpsCmm.createAjax("POST",t,shpsCmm.getRefererParam(),"blob").then(function(n){return e.setBlob(t,n)})},e.getBlobUrl=function(t,n){return e.getObjUrl(t,n).then(function(n){var r=n.record;return e.checkExpire(r)&&e.checkUpdate(r,t,"blob").then(function(n){e.setBlob(t,n)}),n.objUrl},function(){return e.dlAndSetBlob(t).then(function(){return e.getObjUrl(t,n)}).then(function(t){return t.objUrl})})},shpsCmm.createAjax=function(t,e,n,r,s,i){return new Promise(function(o,a){var c=new XMLHttpRequest;i&&(i.ajax=c),c.open(t,e,!0),c.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),s&&s.forEach(function(t){c.setRequestHeader(t.hdr,t.value)}),r&&(c.responseType=r),c.addEventListener("readystatechange",function(){4==c.readyState&&o(c)},!1),c.send(n)})},shpsCmm.getRefererParam=function(){return"refuri="+window.location.hostname};var n=shpsCmm.lnkExtFile={};n.promises={},n.lnked=function(t,e,n,r,s){var i=!1;if(forEachObjProp(this.promises,function(t,n){n==e&&(i=t)}),i)return i;var o;switch(t){case"script":o=function(t){var n=new URL(t.src);return n.pathname==e?!0:!1};break;case"link":o=function(t){var n=new URL(t.href);return n.pathname==e?!0:!1}}for(var a=document.getElementsByTagName(t),c=0;c<a.length;c++)if(o(a[c])){var u=Promise.resolve();return this.promises.url=u,u}requestedExtFile_onloadFcts[e]=[];var l=document.createElement(t);s&&(l.charset=s),r||(r="head");var u=new Promise(function(t,n){l.addEventListener("load",function(){t(),requestedExtFile_onloadFcts[e].forEach(function(t){t()}),delete requestedExtFile_onloadFcts[e]},!1)});switch(this.promises.url=u,t){case"script":l.type="text/javascript",n!==!1&&(l.async=!0),l.src=e;break;case"link":l.type="text/css",l.rel="stylesheet",l.href=e}return document.getElementsByTagName(r)[0].appendChild(l),u};var r=new Promise(function(t,e){"complete"==document.readyState?t():window.addEventListener("load",t)});shpsCmm.wdwLded=function(){return r};var s=new Promise(function(t,e){"interactive"==document.readyState||"complete"==document.readyState?t():document.addEventListener("DOMContentLoaded",t,!1)});shpsCmm.domReady=function(){return s}}();var wdwLoaded=!1;window.addEventListener("load",function(){wdwLoaded=!0}),Array.prototype.forEach||(Array.prototype.forEach=function(t,e){var n,r;if(null==this)throw new TypeError(" this is null or not defined");var s=Object(this),i=s.length>>>0;if("function"!=typeof t)throw new TypeError(t+" is not a function");for(arguments.length>1&&(n=e),r=0;i>r;){var o;r in s&&(o=s[r],t.call(n,o,r,s)),r++}});var onloadFct_arr=[],imgSet_fct_arr=[],requestedExtFile_onloadFcts={};Array.prototype.shuffle=function(){function t(){return Math.round(Math.random())-.5}this.sort(t)},ttlElmOnload.prototype.addElm=function(t){this.addLdedCheck(t);var e=this;this.ldFctArr[t]=function(){checkElmOnload(t,function(){e.resCallback(t)})}},ttlElmOnload.prototype.addImgs=function(t,e){this.addLdedCheck(t);var n=this;this.ldFctArr[t]=function(){add_imgOnload(t,e,function(){n.resCallback(t)})}},ttlElmOnload.prototype.addThe9Imges=function(){},ttlElmOnload.prototype.addScript=function(t,e,n){this.addLdedCheck(t);var r=this;this.ldFctArr[t]=function(){include("script",t,e,n,function(){r.resCallback(t)})}},ttlElmOnload.prototype.addCss=function(t,e,n){this.addLdedCheck(t);var r=this;this.ldFctArr[t]=function(){include("link",t,e,n,function(){r.resCallback(t)})}},ttlElmOnload.prototype.start=function(){var t=this;Object.keys(this.ldFctArr).forEach(function(e){t.ldFctArr[e]()})};var optRszLsnr={};optRszLsnr.fps=24,optRszLsnr.interval=1e3/optRszLsnr.fps,optRszLsnr.inited=!1,optRszLsnr.hdlrs=[],optRszLsnr.running=!1,optRszLsnr.lastTs=0,optRszLsnr.intendTs=0,optRszLsnr.expireTs=0,optRszLsnr.afHdlr=function(t){t>=optRszLsnr.intendTs?(optRszLsnr.lastTs=t,optRszLsnr.hdlrs.forEach(function(t){t()}),t>=optRszLsnr.expireTs&&(optRszLsnr.intendTs=t),optRszLsnr.intendTs+=optRszLsnr.interval,optRszLsnr.expireTs=optRszLsnr.intendTs+optRszLsnr.interval,optRszLsnr.running=!1):window.requestAnimationFrame(optRszLsnr.afHdlr)},optRszLsnr.rszHdlr=function(){optRszLsnr.running||(optRszLsnr.running=!0,window.requestAnimationFrame(optRszLsnr.afHdlr))},optRszLsnr.add=function(t){-1==optRszLsnr.hdlrs.indexOf(t)&&(optRszLsnr.hdlrs.push(t),optRszLsnr.inited||(optRszLsnr.inited=!0,window.addEventListener("resize",optRszLsnr.rszHdlr,!1)))},optRszLsnr.rmv=function(t){var e=optRszLsnr.hdlrs.indexOf(t);-1!=e&&(optRszLsnr.hdlrs.splice(e,1),optRszLsnr.hdlrs.length<=0&&(window.removeEventListener("resize",optRszLsnr.rszHdlr,!1),optRszLsnr.inited=!1))};var optElmRszLsnr={};optElmRszLsnr.fps=24,optElmRszLsnr.interval=1e3/optElmRszLsnr.fps,optElmRszLsnr.afHdlr=function(t,e){var n=t.optElmRszLsnr;e>=n.intendTs?(n.lastTs=e,n.hdlrs.forEach(function(t){t()}),e>=n.expireTs&&(n.intendTs=e),n.intendTs+=optElmRszLsnr.interval,n.expireTs=n.intendTs+optElmRszLsnr.interval,n.running=!1):window.requestAnimationFrame(function(e){optElmRszLsnr.afHdlr(t,e)})},optElmRszLsnr.rszHdlr=function(t){var e=this.rszElm;e.optElmRszLsnr.running||(e.optElmRszLsnr.running=!0,window.requestAnimationFrame(function(t){optElmRszLsnr.afHdlr(e,t)}))},optElmRszLsnr.add=function(t,e){if(t.optElmRszLsnr)-1==t.optElmRszLsnr.hdlrs.indexOf(e)&&t.optElmRszLsnr.hdlrs.push(e);else{var n=t.optElmRszLsnr={};n.running=!1,n.lastTs=0,n.intendTs=0,n.expireTs=0,n.hdlrs=[],n.hdlrs.push(e),"static"==getStyle(t,"position")&&(t.style.position="relative");var r=n.rszTrigger=document.createElement("object");mdf_cls(r,"add","vis-hid pos-abs w-100 h-100 dsp-blk"),r.style.zIndex="-99999",r.style.pointerEvents="none",r.style.overflow="hidden",r.style.top="0",r.style.left="0",r.type="text/html",r.data="about:blank",r.addEventListener("load",function(){var e=this.contentDocument.defaultView;e.rszElm=t,optElmRszLsnr.rszHdlr.call(e),e.addEventListener("resize",optElmRszLsnr.rszHdlr)},!1),t.appendChild(r)}},optElmRszLsnr.rmv=function(t,e){var n=t.optElmRszLsnr.hdlrs.indexOf(e);-1!=n&&(t.optElmRszLsnr.hdlrs.splice(n,1),t.optElmRszLsnr.hdlrs.length<=0&&(t.removeChild(t.optElmRszLsnr.rszTrigger),t.optElmRszLsnr=void 0))};var optScrlLsnr={};optScrlLsnr.fps=24,optScrlLsnr.interval=1e3/optScrlLsnr.fps,optScrlLsnr.afHdlr=function(t,e,n){var r=n.optScrlLsnr;e>=r.intendTs?(r.lastTs=e,r.hdlrs.forEach(function(e){e(t)}),e>=r.expireTs&&(r.intendTs=e),r.intendTs+=optScrlLsnr.interval,r.expireTs=r.intendTs+optScrlLsnr.interval,r.running=!1):window.requestAnimationFrame(function(e){optScrlLsnr.afHdlr(t,e,n)})},optScrlLsnr.rszHdlr=function(t){if(!this.optScrlLsnr.running){this.optScrlLsnr.running=!0;var e=this;window.requestAnimationFrame(function(n){optScrlLsnr.afHdlr(t,n,e)})}},optScrlLsnr.add=function(t,e){if(t.optScrlLsnr)-1==t.optScrlLsnr.hdlrs.indexOf(e)&&t.optScrlLsnr.hdlrs.push(e);else{var n=t.optScrlLsnr={};n.running=!1,n.lastTs=0,n.intendTs=0,n.expireTs=0,n.hdlrs=[],n.hdlrs.push(e),t.addEventListener("scroll",this.rszHdlr)}},optScrlLsnr.rmv=function(t,e){var n=t.optScrlLsnr.hdlrs.indexOf(e);-1!=n&&(t.optScrlLsnr.hdlrs.splice(n,1),t.optScrlLsnr.hdlrs.length<=0&&(t.optScrlLsnr=void 0,t.removeEventListener("scroll",this.rszHdlr)))};var optAnimScrlLsnr={};optAnimScrlLsnr.rszHdlr=function(t){var e=this.optAnimScrlLsnr;e.running||(e.running=!0,window.requestAnimationFrame(function(){e.hdlrs.forEach(function(e){e(t)}),e.running=!1}))},optAnimScrlLsnr.add=function(t,e){if(t.optAnimScrlLsnr)-1==t.optAnimScrlLsnr.hdlrs.indexOf(e)&&t.optAnimScrlLsnr.hdlrs.push(e);else{var n=t.optAnimScrlLsnr={};n.running=!1,n.hdlrs=[],n.hdlrs.push(e),t.addEventListener("scroll",this.rszHdlr)}},optAnimScrlLsnr.rmv=function(t,e){var n=t.optAnimScrlLsnr.hdlrs.indexOf(e);-1!=n&&(t.optAnimScrlLsnr.hdlrs.splice(n,1),t.optAnimScrlLsnr.hdlrs.length<=0&&(t.optAnimScrlLsnr=void 0,t.removeEventListener("scroll",this.rszHdlr)))};var optMseMveLsnr={};optMseMveLsnr.fps=24,optMseMveLsnr.interval=1e3/optScrlLsnr.fps,optMseMveLsnr.afHdlr=function(t,e,n){var r=n.optMseMveLsnr;e>=r.intendTs?(r.lastTs=e,r.hdlrs.forEach(function(e){e(t)}),e>=r.expireTs&&(r.intendTs=e),r.intendTs+=optMseMveLsnr.interval,r.expireTs=r.intendTs+optMseMveLsnr.interval,r.running=!1):window.requestAnimationFrame(function(e){optMseMveLsnr.afHdlr(t,e,n)})},optMseMveLsnr.mmHdlr=function(t){if(!this.optMseMveLsnr.running){this.optMseMveLsnr.running=!0;var e=this;window.requestAnimationFrame(function(n){optMseMveLsnr.afHdlr(t,n,e)})}},optMseMveLsnr.add=function(t,e){if(t.optMseMveLsnr)-1==t.optMseMveLsnr.hdlrs.indexOf(e)&&t.optMseMveLsnr.hdlrs.push(e);else{var n=t.optMseMveLsnr={};n.running=!1,n.lastTs=0,n.intendTs=0,n.expireTs=0,n.hdlrs=[],n.hdlrs.push(e),t.addEventListener("mousemove",this.mmHdlr)}},optMseMveLsnr.rmv=function(t,e){var n=t.optMseMveLsnr.hdlrs.indexOf(e);-1!=n&&(t.optMseMveLsnr.hdlrs.splice(n,1),t.optMseMveLsnr.hdlrs.length<=0&&(t.optMseMveLsnr=void 0,t.removeEventListener("mousemove",this.mmHdlr)))},function(t){"object"==typeof exports?module.exports=t():"function"==typeof define&&define.amd?define([],t):window.BezierEasing=t()}(function(){function t(t,e){return 1-3*e+3*t}function e(t,e){return 3*e-6*t}function n(t){return 3*t}function r(r,s,i){return((t(s,i)*r+e(s,i))*r+n(s))*r}function s(r,s,i){return 3*t(s,i)*r*r+2*e(s,i)*r+n(s)}function i(t,e,n,s,i){var o,a,c=0;do a=e+(n-e)/2,o=r(a,s,i)-t,o>0?n=a:e=a;while(Math.abs(o)>l&&++c<d);return a}function o(t,e,n,o){function a(e,i){for(var o=0;c>o;++o){var a=s(i,t,n);if(0===a)return i;var u=r(i,t,n)-e;i-=u/a}return i}function l(){for(var e=0;h>e;++e)g[e]=r(e*f,t,n)}function d(e){for(var r=0,o=1,c=h-1;o!=c&&g[o]<=e;++o)r+=f;--o;var l=(e-g[o])/(g[o+1]-g[o]),d=r+l*f,p=s(d,t,n);return p>=u?a(e,d):0===p?d:i(e,r,r+f,t,n)}function m(){L=!0,(t!=e||n!=o)&&l()}if(4!==arguments.length)throw new Error("BezierEasing requires 4 arguments.");for(var v=0;4>v;++v)if("number"!=typeof arguments[v]||isNaN(arguments[v])||!isFinite(arguments[v]))throw new Error("BezierEasing arguments should be integers.");if(0>t||t>1||0>n||n>1)throw new Error("BezierEasing x values must be in [0, 1] range.");var g=p?new Float32Array(h):new Array(h),L=!1,E=function(s){return L||m(),t===e&&n===o?s:0===s?0:1===s?1:r(d(s),e,o)};E.getControlPoints=function(){return[{x:t,y:e},{x:n,y:o}]};var w=[t,e,n,o],b="BezierEasing("+w+")";E.toString=function(){return b};var y="cubic-bezier("+w+")";return E.toCSS=function(){return y},E}var a=this,c=4,u=.001,l=1e-7,d=10,h=11,f=1/(h-1),p="Float32Array"in window;return o.css={ease:o(.25,.1,.25,1),linear:o(0,0,1,1),"ease-in":o(.42,0,1,1),"ease-out":o(0,0,.58,1),"ease-in-out":o(.42,0,.58,1)},o}),transit.prototype.easeOut=function(){return-this.totalChange*this.progress*(this.progress-2)+this.start},transit.easeMiddleBezier=BezierEasing(0,.4,1,.5),transit.prototype.easeMiddle=function(){return this.totalChange*transit.easeMiddleBezier(this.progress)+this.start},transit.prototype.quadEaseIn=function(){var t=this.progress;return this.totalChange*t*t+this.start},transit.prototype.quadEaseInOut=function(){var t=2*this.progress;return 1>t?this.totalChange/2*t*t+this.start:(t--,-this.totalChange/2*(t*(t-2)-1)+this.start)},transit.prototype.step=function(t){t>=this.intendTs&&(this.timePassed+=t-this.lastTs,this.lastTs=t,this.progress=this.timePassed/this.duration,this.progress>1&&(this.progress=1),this.frame(this.delta()),this.intendTs+=this.interval),this.progress<1?"play"==this.state&&this.play():(this.stop(),this.transitEnd())},transit.prototype.addEventListener=function(t,e){switch(t){case"end":var n=this.transitEnd;this.transitEnd=function(){n(),e()};break;default:return!1}},transit.prototype.play=function(){if(this.progress>=1)return this.replay(),void 0;this.state="play",this.lastTs||(this.lastTs=this.intendTs=performance.now());var t=this;this.afId=window.requestAnimationFrame(function(e){t.step(e)})},transit.prototype.replay=function(){this.timePassed=0,this.progress=0,this.lastTs=void 0,this.play()},transit.prototype.pause=function(){this.state="pause"},transit.prototype.stop=function(){this.state="stop"},Delegate.create=function(t,e){for(var n=[],r=arguments.length,s=2;r>s;s++)n[s-2]=arguments[s];return function(){var r=[].concat(arguments,n);e.apply(t,r)}};var Tween=function(t,e,n,r,s,i,o){this.init(t,e,n,r,s,i,o)},t=Tween.prototype;t.obj={},t.prop="",t.func=function(t,e,n,r){return n*t/r+e},t.begin=0,t.change=0,t.prevTime=0,t.prevPos=0,t.looping=!1,t._duration=0,t._time=0,t._pos=0,t._position=0,t._startTime=0,t._finish=0,t.name="",t.suffixe="",t._listeners=[],t.setTime=function(t){this.prevTime=this._time,t>this.getDuration()?this.looping?(this.rewind(t-this._duration),this.update(),this.broadcastMessage("onMotionLooped",{target:this,type:"onMotionLooped"})):(this._time=this._duration,this.update(),this.stop(),this.broadcastMessage("onMotionFinished",{target:this,type:"onMotionFinished"})):0>t?(this.rewind(),this.update()):(this._time=t,this.update())},t.getTime=function(){return this._time},t.setDuration=function(t){this._duration=null==t||0>=t?1e5:t},t.getDuration=function(){return this._duration},t.setPosition=function(t){this.prevPos=this._pos;var e=""!=this.suffixe?this.suffixe:"";this.obj[this.prop]=Math.round(t)+e,this._pos=t,this.broadcastMessage("onMotionChanged",{target:this,type:"onMotionChanged"})},t.getPosition=function(t){return void 0==t&&(t=this._time),this.func(t,this.begin,this.change,this._duration)},t.setFinish=function(t){this.change=t-this.begin},t.geFinish=function(){return this.begin+this.change},t.init=function(t,e,n,r,s,i,o){arguments.length&&(this._listeners=[],this.addListener(this),o&&(this.suffixe=o),this.obj=t,this.prop=e,this.begin=r,this._pos=r,this.setDuration(i),null!=n&&""!=n&&(this.func=n),this.setFinish(s))},t.start=function(){this.rewind(),this.startEnterFrame(),this.broadcastMessage("onMotionStarted",{target:this,type:"onMotionStarted"})},t.rewind=function(t){this.stop(),this._time=void 0==t?0:t,this.fixTime(),this.update()},t.fforward=function(){this._time=this._duration,this.fixTime(),this.update()},t.update=function(){this.setPosition(this.getPosition(this._time))},t.startEnterFrame=function(){this.stopEnterFrame(),this.isPlaying=!0,this.onEnterFrame()},t.onEnterFrame=function(){this.isPlaying&&(this.nextFrame(),setTimeout(Delegate.create(this,this.onEnterFrame),0))},t.nextFrame=function(){this.setTime((this.getTimer()-this._startTime)/1e3)},t.stop=function(){this.stopEnterFrame(),this.broadcastMessage("onMotionStopped",{target:this,type:"onMotionStopped"})},t.stopEnterFrame=function(){this.isPlaying=!1},t.continueTo=function(t,e){this.begin=this._pos,this.setFinish(t),void 0!=this._duration&&this.setDuration(e),this.start()},t.resume=function(){this.fixTime(),this.startEnterFrame(),this.broadcastMessage("onMotionResumed",{target:this,type:"onMotionResumed"})},t.yoyo=function(){this.continueTo(this.begin,this._time)},t.addListener=function(t){return this.removeListener(t),this._listeners.push(t)},t.removeListener=function(t){for(var e=this._listeners,n=e.length;n--;)if(e[n]==t)return e.splice(n,1),!0;return!1},t.broadcastMessage=function(){for(var t=[],e=0;e<arguments.length;e++)t.push(arguments[e]);for(var n=t.shift(),r=this._listeners,s=r.length,e=0;s>e;e++)r[e][n]&&r[e][n].apply(r[e],t)},t.fixTime=function(){this._startTime=this.getTimer()-1e3*this._time},t.getTimer=function(){return(new Date).getTime()-this._time},Tween.backEaseIn=function(t,e,n,r,s,i){if(void 0==o)var o=1.70158;return n*(t/=r)*t*((o+1)*t-o)+e},Tween.backEaseOut=function(t,e,n,r,s,i){if(void 0==o)var o=1.70158;return n*((t=t/r-1)*t*((o+1)*t+o)+1)+e},Tween.backEaseInOut=function(t,e,n,r,s,i){if(void 0==o)var o=1.70158;return(t/=r/2)<1?n/2*t*t*(((o*=1.525)+1)*t-o)+e:n/2*((t-=2)*t*(((o*=1.525)+1)*t+o)+2)+e},Tween.elasticEaseIn=function(t,e,n,r,s,i){if(0==t)return e;if(1==(t/=r))return e+n;if(i||(i=.3*r),!s||s<Math.abs(n)){s=n;var o=i/4}else var o=i/(2*Math.PI)*Math.asin(n/s);return-(s*Math.pow(2,10*(t-=1))*Math.sin(2*(t*r-o)*Math.PI/i))+e},Tween.elasticEaseOut=function(t,e,n,r,s,i){if(0==t)return e;if(1==(t/=r))return e+n;if(i||(i=.3*r),!s||s<Math.abs(n)){s=n;var o=i/4}else var o=i/(2*Math.PI)*Math.asin(n/s);return s*Math.pow(2,-10*t)*Math.sin(2*(t*r-o)*Math.PI/i)+n+e},Tween.elasticEaseInOut=function(t,e,n,r,s,i){if(0==t)return e;if(2==(t/=r/2))return e+n;if(!i)var i=.3*r*1.5;if(!s||s<Math.abs(n))var s=n,o=i/4;else var o=i/(2*Math.PI)*Math.asin(n/s);return 1>t?-.5*s*Math.pow(2,10*(t-=1))*Math.sin(2*(t*r-o)*Math.PI/i)+e:s*Math.pow(2,-10*(t-=1))*Math.sin(2*(t*r-o)*Math.PI/i)*.5+n+e},Tween.bounceEaseOut=function(t,e,n,r){return(t/=r)<1/2.75?7.5625*n*t*t+e:2/2.75>t?n*(7.5625*(t-=1.5/2.75)*t+.75)+e:2.5/2.75>t?n*(7.5625*(t-=2.25/2.75)*t+.9375)+e:n*(7.5625*(t-=2.625/2.75)*t+.984375)+e},Tween.bounceEaseIn=function(t,e,n,r){return n-Tween.bounceEaseOut(r-t,0,n,r)+e},Tween.bounceEaseInOut=function(t,e,n,r){return r/2>t?.5*Tween.bounceEaseIn(2*t,0,n,r)+e:.5*Tween.bounceEaseOut(2*t-r,0,n,r)+.5*n+e},Tween.strongEaseInOut=function(t,e,n,r){return n*(t/=r)*t*t*t*t+e},Tween.regularEaseIn=function(t,e,n,r){return n*(t/=r)*t+e},Tween.regularEaseOut=function(t,e,n,r){return-n*(t/=r)*(t-2)+e},Tween.regularEaseInOut=function(t,e,n,r){return(t/=r/2)<1?n/2*t*t+e:-n/2*(--t*(t-2)-1)+e},Tween.strongEaseIn=function(t,e,n,r){return n*(t/=r)*t*t*t*t+e},Tween.strongEaseOut=function(t,e,n,r){return n*((t=t/r-1)*t*t*t*t+1)+e},Tween.strongEaseInOut=function(t,e,n,r){return(t/=r/2)<1?n/2*t*t*t*t*t+e:n/2*((t-=2)*t*t*t*t+2)+e},OpacityTween.prototype=new Tween,OpacityTween.prototype.constructor=Tween,OpacityTween.superclass=Tween.prototype;var o=OpacityTween.prototype;o.targetObject={},o.onMotionChanged=function(t){var e=t.target._pos,n=this.targetObject;n.style.opacity=e/100,n.filters&&(n.style.filter="progid:DXImageTransform.Microsoft.Alpha("+e+")")};