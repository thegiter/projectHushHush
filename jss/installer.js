!function(){function e(){return new Promise(function(e,t){"interactive"==document.readyState||"complete"==document.readyState?e():document.addEventListener("DOMContentLoaded",e,!1)})}function t(e,t,n,r,o){return new Promise(function(a,c){var i=new XMLHttpRequest;i.open(e,t,!0),i.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),o&&o.forEach(function(e){i.setRequestHeader(e.hdr,e.value)}),r&&(i.responseType=r),i.addEventListener("readystatechange",function(){4==i.readyState&&a(i)},!1),i.send(n)})}function n(){return"refuri="+window.location.hostname}function r(e,t){for(var n in e)e.hasOwnProperty(n)&&t(e[n],n)}function o(e,t,n,o,a,c){var i=!1;if(r(d,function(e,n){n==t&&(a&&e.push(a),i=!0)}),i)return!0;var u,s=document.createElement("a");switch(e){case"script":u=function(e){return s.href=e.src,s.pathname==t?!0:!1};break;case"link":u=function(e){return s.href=e.href,s.pathname==t?!0:!1}}for(var l=document.getElementsByTagName(e),h=0;h<l.length;h++)if(u(l[h]))return a&&a(),!0;d[t]=[],a&&d[t].push(a);var f=document.createElement(e);switch(o&&(f.charset=o),n||(n="head"),f.addEventListener("load",function(){d[t].forEach(function(e){e()}),delete d[t]},!1),e){case"script":f.type="text/javascript",c!==!1&&(f.async=!0),f.src=t;break;case"link":f.type="text/css",f.rel="stylesheet",f.href=t}document.getElementsByTagName(n)[0].appendChild(f)}function a(e){return"html"==e.type?(document.body.innerHTML=e.html,Promise.resolve()):"img"!=e.type?new Promise(function(t,n){o(e.type,e.objUrl,void 0,void 0,function(){t()},e.async)}):void 0}function c(e){return new Promise(function(t,n){return"html"==e.type?(t(e),!0):(l.getBlobUrl(e.url,e.type).then(function(n){e.objUrl=n,t(e)}),void 0)})}function i(e){var t=[];e.forEach(function(e){var n=[];e.forEach(function(e){var t=c(e);n.push(t)});var r=Promise.all(n);t.push(r)}),t.reduce(function(e,t){return e.then(function(){return t}).then(function(e){var t=[];return e.forEach(function(e){var n=a(e);t.push(n)}),Promise.all(t)})},Promise.resolve())}function u(){return t("POST",debug.checkUrl(m),n(),"json").then(function(e){var t=l.createRecordObj(e);return t&&(t.manifest=e.response,t.url=m,l.set(t)),t.manifest})}var s=604800,l={};l.objStoreName="appFiles",l.checkDb=new Promise(function(e,t){var n=indexedDB.open("installer",2);n.addEventListener("error",function(e){t()}),n.addEventListener("upgradeneeded",function(e){var t=n.result;t.objectStoreNames.contains(l.objStoreName)||t.createObjectStore(l.objStoreName,{keyPath:"url"})}),n.addEventListener("success",function(t){l.db=n.result,e()})}),l.cache={},l.set=function(e){return this.checkDb.then(function(){return new Promise(function(t,n){var r=l.db.transaction(l.objStoreName,"readwrite").objectStore(l.objStoreName).put(e);r.addEventListener("success",function(){l.cache[e.url]=e,t()})})})},l.rmv=function(e){return this.checkDb.then(function(){return new Promise(function(t,n){var r=l.db.transaction(l.objStoreName,"readwrite").objectStore(l.objStoreName)["delete"](e);r.addEventListener("success",function(){delete l.cache[e],t()}),r.addEventListener("error",n)})})},l.get=function(e){return this.cache[e]?Promise.resolve(this.cache[e]):this.checkDb.then(function(){return new Promise(function(t,n){var r=l.db.transaction(l.objStoreName).objectStore(l.objStoreName).get(e);r.addEventListener("success",function(){r.result?(l.cache[e]=r.result,t(l.cache[e])):n()}),r.addEventListener("error",n)})})},l.ouCache={},l.checkCss_checkObjUrl=function(e,t,n){var r=/(.*\/)[^\/]*$/;t=r.exec(t)[1];var o=/\.\.\//g,a=(e.match(o)||[]).length;if(a>0)for(var c=/[^\/]+\/$/,i=0;a>i;i++)t=t.replace(c,"");var u=e.replace(o,""),s=t+u,d;return".css"==s.substr(s.length-4)&&(d="link"),new Promise(function(t,r){l.getObjUrl(s,d).then(function(t){n.push({subStr:e,repStr:t.objUrl})},function(){n.push({subStr:e,repStr:window.location.origin+s})}).then(function(){t()})})},l.checkCss=function(e,t){return new Promise(function(n,r){var o=new FileReader;o.addEventListener("loadend",function(){for(var r=o.result,a=[],c=/url\(([^\)]+)\)/g,i;null!==(i=c.exec(r));)a.push(i[1]);new Promise(function(e,n){if(a.length>0){var o=[],c=[];a.forEach(function(e){var n=l.checkCss_checkObjUrl(e,t,o);c.push(n)}),Promise.all(c).then(function(){o.forEach(function(e){r=r.replace("url("+e.subStr+")","url("+e.repStr+")")}),e()})}else e()}).then(function(){n(new Blob([r],{type:e.type}))})}),o.readAsText(e)})},l.ouCache_setAndReturn=function(e,t){return this.ouCache[e]={objUrl:URL.createObjectURL(t.blob),record:t},this.ouCache[e]},l.getObjUrl=function(e,t){if("link"==t){var n=e+"_tmpCopy";return this.ouCache[n]?Promise.resolve(this.ouCache[n]):l.get(e).then(function(t){return l.checkCss(t.blob,e)}).then(function(e){var t={blob:e,url:n};return l.set(t)}).then(function(){return l.get(n)}).then(function(e){return l.ouCache_setAndReturn(n,e)})}return this.ouCache[e]?Promise.resolve(this.ouCache[e]):l.get(e).then(function(t){return l.ouCache_setAndReturn(e,t)})},l.createRecordObj=function(e){if(200!==e.status)return!1;var t={dlDate:Date.now(),lastModified:e.getResponseHeader("Last-Modified")},n=/max\-age=([0-9]+)($|,)/;t.maxAge=n.exec(e.getResponseHeader("Cache-Control")),t.maxAge=null==t.maxAge?0:parseInt(t.maxAge[1]);var r=e.getResponseHeader("Etag");return r&&(t.eTag=r),t},l.checkExpire=function(e){return e.dlDate+1e3*e.maxAge<Date.now()?!0:!1},l.createHdrFrom=function(e){var t=[{hdr:"If-Modified-Since",value:e.lastModified}];return e.eTag&&t.push({hdr:"If-None-Match",value:e.eTag}),t},l.checkUpdate=function(e,n,r){return t("GET",n,void 0,r,this.createHdrFrom(e)).then(function(e){if(200==e.status)return e;throw e})},l.setBlob=function(e,t){var n=l.createRecordObj(t);return n?(n.blob=t.response,n.url=e,l.set(n)):void 0},l.dlAndSetBlob=function(e){return t("POST",e,n(),"blob").then(function(t){return l.setBlob(e,t)})},l.getBlobUrl=function(e,t){return l.getObjUrl(e,t).then(function(t){var n=t.record;return l.checkExpire(n)&&l.checkUpdate(n,e,"blob").then(function(t){l.setBlob(e,t)}),t.objUrl},function(){return l.dlAndSetBlob(e).then(function(){return l.getObjUrl(e,t)}).then(function(e){return e.objUrl})})},debug={},debug.checkUrl=function(e){return"/debug/view.php"==window.location.pathname?"/"+e:e};var d={},h=["jss/installer.js","shared/imgs/favicon.ico","shared/csss/default.css"],f=localStorage.getItem("installDate");f||(f=(new Date).toUTCString(),localStorage.setItem("installDate",f),localStorage.setItem("installerLastCheckDate","0")),parseInt(localStorage.getItem("installerLastCheckDate"))+0*s<Date.now()&&(h.forEach(function(e){e=debug.checkUrl(e);var n=localStorage.getItem(e+"_lastModified");n&&"null"!=n||(n=f);var r=[{hdr:"If-Modified-Since",value:n}],o=localStorage.getItem(e+"_eTag");o&&r.push({hdr:"If-None-Match",value:o}),t("GET",e,void 0,void 0,r).then(function(t){if(200==t.status){console.log("rspUrl: "+e+"; date: "+t.getResponseHeader("Last-Modified")),localStorage.setItem(e+"_lastModified",t.getResponseHeader("Last-Modified"));var n=t.getResponseHeader("Etag");n?localStorage.setItem(e+"_eTag",n):localStorage.removeItem(e+"_eTag")}})}),localStorage.setItem("installerLastCheckDate",Date.now()));var m="manifest.php";l.get(m).then(function(e){if(i(e.manifest),l.checkExpire(e)){var n=[{hdr:"If-Modified-Since",value:e.lastModified}];e.eTag&&n.push({hdr:"If-None-Match",value:e.eTag}),t("GET",debug.checkUrl(m),void 0,"json",n).then(function(e){200==e.status&&u()})}},function(){u()["catch"](function(){}).then(function(e){i(e)})})}();