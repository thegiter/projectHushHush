function shpsAjax(){}shpsAjax.siteTtl="SHPS",shpsAjax.load=!1,shpsAjax.debug={},shpsAjax.debug.checkUrl=function(s){return"/debug/view.php"==window.location.pathname?"/"+s:s},shpsAjax.rqsMgr={},shpsAjax.rqsMgr.ongoing=[],shpsAjax.rqsMgr.pending=[],shpsAjax.rqsMgr.abort=function(){shpsAjax.rqsMgr.ongoing.forEach(function(s){s.qable&&-1==shpsAjax.rqsMgr.pending.indexOf(s)&&shpsAjax.rqsMgr.pending.push(s),s.ajax.abort()}),shpsAjax.rqsMgr.ongoing=[]},shpsAjax.rqsMgr.removeOngoing=function(s){var r=shpsAjax.rqsMgr.ongoing.indexOf(s);-1!=r&&shpsAjax.rqsMgr.ongoing.splice(r,1)},shpsAjax.rqsMgr.removePending=function(s){var r=shpsAjax.rqsMgr.pending.indexOf(s);-1!=r&&shpsAjax.rqsMgr.pending.splice(r,1)},shpsAjax.rqsMgr.load=function(s,r){var n=this;this.pending.forEach(function(r){r.url==s.url&&n.removePending(r)}),this.ongoing.forEach(function(r){return r.url==s.url?!1:void 0}),s.overwrite&&this.abort(),this.ongoing.push(s),shpsCmm.createAjax("POST",shpsAjax.debug.checkUrl(s.url),shpsCmm.getRefererParam(),s.type,s.hdrs,s).then(function(e){n.removeOngoing(s),r(e),n.attemptPending()})},shpsAjax.rqsMgr.attemptPending=function(){shpsAjax.rqsMgr.pending.length>0&&0===shpsAjax.rqsMgr.ongoing.length&&shpsAjax.rqsMgr.load(shpsAjax.rqsMgr.pending[0])},shpsAjax.rscMgr={},shpsAjax.rscMgr.rscs={},shpsAjax.rscMgr.inform=function(s,r){switch(s){case"pg":shpsAjax.pgMgr.beInformedRsc(r,shpsAjax.rscMgr.rscs[r]);break;case"cpnLdr":shpsAjax.cpnMgr.ldr.beInformedRsc(r,shpsAjax.rscMgr.rscs[r])}},shpsAjax.rscMgr.onLd=function(s,r,n){this.rscs[s]=n,this.inform(r,s)},shpsAjax.rscMgr.rqs=function(s,r){if(this.rscs[s.url])this.inform(r,s.url);else{var n=shpsCmm.idbCache,e=shpsCmm.iCMgr,t=this;n.get(s.url).then(function(a){if(t.onLd(s.url,r,a.rscList),a.dlDate+1e3*a.maxAge<Date.now()){var p=[{hdr:"If-Modified-Since",value:a.lastModified}];a.eTag&&p.push({hdr:"If-None-Match",value:a.eTag}),s.type="json",shpsCmm.createAjax("GET",shpsAjax.debug.checkUrl(s.url),void 0,s.type,p).then(function(r){200==r.status&&shpsAjax.rqsMgr.load(s,function(r){var t=e.createRecordObj(r);t&&(t.rscList=r.response,t.url=s.url,n.set(t))})})}},function(){s.type="json",shpsAjax.rqsMgr.load(s,function(a){var p=e.createRecordObj(a);p&&(t.onLd(s.url,r,a.response),p.rscList=a.response,p.url=s.url,n.set(p))})})}},shpsAjax.psdBd_id="psd-bd-cnr",shpsAjax.hooks={},shpsAjax.cpnList={historyView:{url:"shared/shps_ajax/hv/",cnr_id:"psd-menu-scrl-cnr",lded:!1},betaSign:{url:"shared/cpns/beta_sign/",cnr_id:"the-shps-ajax-bd",lded:!1},bg_basic:{url:"shared/bgs/basic/",cnr_id:"psd-bg-cnr",lded:!1},bg_tdcubes:{url:"shared/bgs/tdcubes/",cnr_id:"psd-bg-cnr",lded:!1},bg_tlight:{url:"shared/bgs/tlight/",cnr_id:"psd-bg-cnr",lded:!1},menu_basic:{url:"",cnr_id:"psd-menu-cnr",lded:!1}},shpsAjax.cpnMgr={},shpsAjax.cpnMgr.ldr={},shpsAjax.cpnMgr.ldr.rqss=[],shpsAjax.cpnMgr.ldr.rqd=[],shpsAjax.cpnMgr.ldr.nonRqd=[],shpsAjax.cpnMgr.ldr.chkAll=function(){var s=shpsAjax.cpnMgr.ldr.rqd,r=shpsAjax.cpnMgr.ldr.nonRqd;if(s.length>0||r.length>0){if(s.length>0){if(!shpsAjax.cpnMgr.ldr.rqd.every(function(s){return shpsAjax.cpnList[s.name].lded?!0:!1}))return!1;shpsAjax.cpnMgr.ldr.rqd=[]}shpsAjax.cpnMgr.beInformedLded(),r.length>0&&(shpsAjax.cpnMgr.ldr.internalLoad(shpsAjax.cpnMgr.ldr.nonRqd),shpsAjax.cpnMgr.ldr.nonRqd=[])}},shpsAjax.cpnMgr.ldr.chkLded=function(s){if(!shpsAjax.cpnList[s].lded){if(!shpsAjax.cpnList[s].obj.lded)return!1;if(shpsAjax.cpnList[s].cpnsNdd&&shpsAjax.cpnList[s].cpnsNdd.some(function(s){return shpsAjax.cpnList[s].lded?void 0:!0}))return!1;if(shpsAjax.cpnList[s].cpnsUsed){var r=[];shpsAjax.cpnList[s].cpnsUsed.forEach(function(s){if(!shpsAjax.cpnList[s].lded){var n={name:s};r.push(n)}}),r.length>0&&shpsAjax.cpnMgr.ldr.internalLoad(r)}shpsAjax.cpnList[s].lded=!0,shpsAjax.cpnMgr.operator.beInformedLded(s)}shpsAjax.cpnList[s].forCpns&&shpsAjax.cpnList[s].forCpns.forEach(function(s){shpsAjax.cpnMgr.ldr.chkLded(s)}),shpsAjax.cpnMgr.ldr.chkAll()},shpsAjax.cpnMgr.ldr.appendHtml=function(s,r){function n(){shpsAjax.cpnMgr.ldr.appendHtml=function(s,r){var n=document.getElementById(shpsAjax.cpnList[r].cnr_id),e=document.createElement("div");e.innerHTML=s,forEachNodeItem(e.children,function(s){n.appendChild(s)})},shpsAjax.cpnMgr.ldr.appendHtml(s,r)}document.getElementById(shpsAjax.psdBd_id)?n():window.addEventListener("load",n,!1)},shpsAjax.cpnMgr.ldr.installItem=function(s,r){var n=shpsAjax.cpnList[r],e=shpsCmm.lnkExtFile,t=this;switch(s.type){case"link":e.lnked("link",s.objUrl);break;case"script":e.lnked("script",s.objUrl,s.async).then(function(){n.obj||shpsAjax.hooks[n.hookName]&&(shpsAjax.hooks[n.hookName](r),n.obj.onload(function(){n.obj.lded=!0,t.chkLded(r)}))});break;case"cpnList":n.cpnsNdd=[],n.cpnsUsed=[],n.closeCpns=[],n.nonCloseCpns=[];var a=[],p=shpsAjax.cpnList;s.cpns.forEach(function(s){s.required?(p[s.name].forCpns||(p[s.name].forCpns=[]),p[s.name].forCpns.push(r),n.cpnsNdd.push(s.name),shpsAjax.cpnList[s.name].lded||a.push(s)):n.cpnsUsed.push(s.name),s.close?n.closeCpns.push(s.name):n.nonCloseCpns.push(s.name)}),a.length>0?t.internalLoad(a):t.chkLded(r);break;case"html":t.appendHtml(s.html,r);break;case"module":shpsCmm.moduleMgr.get(s.url,!0)}},shpsAjax.cpnMgr.ldr.beInformedRsc=function(s,r){var n,e;if(this.rqss.some(function(r){return shpsAjax.cpnList[r.name].url==s?(n=r.name,e=shpsAjax.cpnList[r.name],!0):void 0})){if(e.hasRsc)return!1;e.hasRsc=!0,e.hookName=r.hook;var t=shpsCmm.iCMgr,a=this,p=[];r.rscs.forEach(function(s){var r=[];s.forEach(function(s){var n;n="html"==s.type||"cpnList"==s.type||"module"==s.type?Promise.resolve(s):t.getBlobUrl(s.url,s.type).then(function(r){return s.objUrl=r,s}),r.push(n)});var n=Promise.all(r);p.push(n)}),p.reduce(function(s,r){return s.then(function(){return r}).then(function(s){return s.forEach(function(s){a.installItem(s,n)}),Promise.resolve()})},Promise.resolve())}},shpsAjax.cpnMgr.ldr.internalLoad=function(s){s.forEach(function(s){var r=s.name;if(!shpsAjax.cpnList[r].lded){var n={};n.url=shpsAjax.cpnList[r].url,shpsAjax.rscMgr.rqs(n,"cpnLdr")}})},shpsAjax.cpnMgr.ldr.load=function(s){shpsAjax.cpnMgr.ldr.rqss=s.slice(),shpsAjax.cpnMgr.ldr.rqd=[],shpsAjax.cpnMgr.ldr.nonRqd=[],s.forEach(function(s){shpsAjax.cpnList[s.name].lded||(s.required?shpsAjax.cpnMgr.ldr.rqd.push(s):shpsAjax.cpnMgr.ldr.nonRqd.push(s))}),shpsAjax.cpnMgr.ldr.rqd.length>0?shpsAjax.cpnMgr.ldr.internalLoad(shpsAjax.cpnMgr.ldr.rqd):shpsAjax.cpnMgr.ldr.chkAll()},shpsAjax.cpnMgr.operator={},shpsAjax.cpnMgr.operator.rqsList=[],shpsAjax.cpnMgr.operator.listOpened=[],shpsAjax.cpnMgr.operator.rqsArgs=[],shpsAjax.cpnMgr.operator.addRa=function(s,r){var n=this.rqsArgs;r&&(n[s]||(n[s]=[]),-1==n[s].indexOf(r)&&n[s].push(r))},shpsAjax.cpnMgr.operator.open=function(s,r){if(-1==shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.rqsList.push(s),this.addRa(s,r),shpsAjax.cpnList[s].lded)if(-1==shpsAjax.cpnMgr.operator.listOpened.indexOf(s)){shpsAjax.cpnMgr.operator.listOpened.push(s);var n=this.rqsArgs;n[s]?(n[s].forEach(function(r){shpsAjax.cpnList[s].obj.intro(r)}),delete n[s]):shpsAjax.cpnList[s].obj.intro()}else r&&shpsAjax.cpnList[s].obj.intro(r)},shpsAjax.cpnMgr.operator.beInformedLded=function(s){-1==shpsAjax.cpnMgr.operator.listOpened.indexOf(s)&&-1!=shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.open(s)},shpsAjax.cpnMgr.operator.rmvSubCpns=function(s){s.forEach(function(s){shpsAjax.cpnList[s].closeCpns&&shpsAjax.cpnList[s].closeCpns.forEach(function(s){var r=shpsAjax.cpnMgr.operator.rqsList.indexOf(s);-1!=r&&shpsAjax.cpnMgr.operator.rqsList.splice(r,1)})})},shpsAjax.cpnMgr.operator.mdfList=function(s){return s.forEach(function(s){shpsAjax.cpnList[s].nonCloseCpns&&shpsAjax.cpnList[s].nonCloseCpns.forEach(function(s){-1==shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.rqsList.push(s)})}),shpsAjax.cpnMgr.operator.rqsList!=s?(shpsAjax.cpnMgr.operator.mdfList(shpsAjax.cpnMgr.operator.rqsList),void 0):(shpsAjax.cpnMgr.operator.rmvSubCpns(s),void 0)},shpsAjax.cpnMgr.operator.openOnlyThese=function(s){shpsAjax.cpnMgr.operator.rqsList=s.slice(),shpsAjax.cpnMgr.operator.mdfList(s),shpsAjax.cpnMgr.operator.listOpened.forEach(function(s){var r=shpsAjax.cpnMgr.operator.rqsList.indexOf(s);-1!=r?shpsAjax.cpnMgr.operator.rqsList.splice(r,1):(shpsAjax.cpnMgr.operator.listOpened.splice(shpsAjax.cpnMgr.operator.listOpened.indexOf(s),1),shpsAjax.cpnList[s].obj.exit())}),shpsAjax.cpnMgr.operator.rqsList.forEach(function(s){shpsAjax.cpnList[s].lded&&shpsAjax.cpnMgr.operator.open(s)})},shpsAjax.cpnMgr.busy=!1,shpsAjax.cpnMgr.rqss=[],shpsAjax.cpnMgr.q=[],shpsAjax.cpnMgr.src=void 0,shpsAjax.cpnMgr.beInformedLded=function(){if(shpsAjax.cpnMgr.busy=!1,"pg"==this.src){var s=[];this.rqss.forEach(function(r){r.close||s.push(r.name)}),shpsAjax.cpnMgr.operator.openOnlyThese(s),shpsAjax.pgMgr.beInformedCpn()}else{if(!this.q[0].every(function(s){return shpsAjax.cpnList[s.name].lded?!0:void 0}))return!1;this.q.shift()}shpsAjax.cpnMgr.q.length>0&&shpsAjax.cpnMgr.rqs(shpsAjax.cpnMgr.q[0],void 0,!0)},shpsAjax.cpnMgr.rqs=function(s,r,n){if("pg"==r)this.src=r,this.rqss=s.slice();else{if(n&&-1==this.q.indexOf(s)&&this.q.push(s),this.busy)return!1;this.src=void 0;var e=this;s.forEach(function(s){-1==e.rqss.indexOf(s)&&e.rqss.push(s)})}this.busy=!0;var t=[];this.rqss.forEach(function(s){shpsAjax.cpnList[s.name].lded||t.push(s)}),t.length>0?shpsAjax.cpnMgr.ldr.load(t):shpsAjax.cpnMgr.beInformedLded()},shpsAjax.psdMenu={},shpsAjax.psdMenu.noExp=!1,shpsAjax.psdMenu.id="psd-menu-cnr",shpsAjax.psdMenu.noExpand=function(){this.noExp=!0;var s=shpsAjax.psdMenu.elm;s.style.maxWidth=s.offsetWidth+"px",s.offsetWidth,mdf_cls(s,"add","psd-menu-no-expand")},shpsAjax.psdMenu.doExpand=function(){this.noExp=!1;var s=shpsAjax.psdMenu.elm;s.style.removeProperty("max-width"),mdf_cls(s,"remove","psd-menu-no-expand")},shpsAjax.psdMenu.fixedFcts=[],shpsAjax.psdMenu.regFixed=function(s){shpsAjax.psdMenu.fixedFcts.push(s)},shpsAjax.pgMgr={},shpsAjax.pgMgr.crtUrl="null",shpsAjax.pgMgr.rqsUlr="null",shpsAjax.pgMgr.rqsHash=null,shpsAjax.pgMgr.cache={},shpsAjax.pgMgr.cache["null"]={},shpsAjax.pgMgr.cache["null"].lded=!0,shpsAjax.pgMgr.cache["null"].obj={},shpsAjax.pgMgr.cache["null"].obj.intro=function(){},shpsAjax.pgMgr.cnr_id="psd-ctt-cnr",shpsAjax.pgMgr.operator={},shpsAjax.pgMgr.operator.getPgIdx=function(s){var r;return forEachNodeItem(shpsAjax.pgMgr.operator.cttCnr.children,function(n,e){n==s&&(r=e)}),r},shpsAjax.pgMgr.operator.getElms_bfr=function(s){var r=shpsAjax.pgMgr.operator.getPgIdx(s),n=this.cttCnr.children;if(r>0){for(var e=[],t=0;r>t;t++)e.push(n[t]);return e}return!1},shpsAjax.pgMgr.operator.getElms_afr=function(s){var r=shpsAjax.pgMgr.operator.getPgIdx(s),n=this.cttCnr.children;if(r<n.length-1){for(var e=[],t=r+1;t<n.length;t++)e.push(n[t]);return e}return!1},shpsAjax.pgMgr.operator.global3dOffset=120,shpsAjax.pgMgr.operator.single3dOffset=20,shpsAjax.pgMgr.operator.sdw3dSize=12,shpsAjax.pgMgr.operator.sdwOffset=14,shpsAjax.pgMgr.operator.hv3d_bfr=function(s){},shpsAjax.pgMgr.operator.move3d_bfr=function(s){var r=shpsAjax.pgMgr.operator.getElms_bfr(s),n=r.length-1;if(r!==!1){var e=parseFloat(getStyle(document.documentElement,"font-size"))*shpsAjax.pgMgr.operator.sdw3dSize,t=e/s.offsetHeight*100+100+shpsAjax.pgMgr.operator.sdwOffset,a=(t-this.global3dOffset/2)/t*90,p=this;r.forEach(function(s){s.style.transformOrigin="top",s.style.transform="translatey(-"+(p.global3dOffset+p.single3dOffset*n)+"%) rotatex(90deg) translatey(-"+p.global3dOffset+"%) rotatex(-90deg) rotatex(-"+a+"deg)",n--,p.hv3d_bfr(s)})}},shpsAjax.pgMgr.operator.hv3d_afr=function(s){},shpsAjax.pgMgr.operator.move3d_afr=function(s){var r=this.getElms_afr(s),n=0;if(r!==!1){var e=parseFloat(getStyle(document.documentElement,"font-size"))*this.sdw3dSize,t=e/s.offsetHeight*100+100+this.sdwOffset,a=(t-this.global3dOffset/2)/t*90,p=this;r.forEach(function(s){s.style.transformOrigin="bottom",s.style.transform="translatey("+(p.global3dOffset+p.single3dOffset*n)+"%) rotatex(90deg) translatey(-"+p.global3dOffset+"%) rotatex(-90deg) rotatex("+a+"deg)",n++,p.hv3d_afr(s)})}},shpsAjax.pgMgr.operator.hv3d_rmv=function(s){},shpsAjax.pgMgr.operator.remove3d=function(s){s.style.removeProperty("transform"),this.hv3d_rmv(s)},shpsAjax.pgMgr.operator.move3d=function(s){this.remove3d(s),this.move3d_bfr(s),this.move3d_afr(s)},shpsAjax.pgMgr.operator.closeHv=function(){this.scrlCnr.classList.remove("psd-vp-cnr-scrl")},shpsAjax.pgMgr.operator.initHvElm3d=function(){},shpsAjax.pgMgr.operator.swap=function(s){function r(){var r={};if(t[s].obj.hashChange){var n=shpsAjax.pgMgr.rqsHash;t[s].elm.hash=n,r.cb=function(){t[s].obj.hashChange(n)},r.cbTiming="end"}t[s].obj.introed?r.cb&&r.cb():(t[s].obj.intro(r),t[s].obj.introed=!0);var e=shpsAjax.psdMenu;t[s].settings&&t[s].settings.psdMenuNoExp?e.noExp||e.noExpand():e.noExp&&e.doExpand()}var n=shpsAjax.pgMgr.crtUrl,e=shpsAjax.pgMgr.crtUrl=s,t=shpsAjax.pgMgr.cache,a=shpsAjax.pgMgr.cache[s].elm,p=shpsAjax.pgMgr.operator.getElms_afr(a);p!==!1&&p.forEach(function(s){document.getElementById(shpsAjax.pgMgr.cnr_id).insertBefore(s,a),s.offsetHeight});var o=this.scrlCnr;o.classList.add("psd-vp-cnr-scrl");var i=this,h=shpsCmm.scrlSnap;h.add(o,function(){return{v:1}},function d(){h.rmv(o,d),t[n].obj.swapOut&&t[n].obj.swapOut(),i.closeHv()}),o.scrollTop+o.clientHeight<o.scrollHeight?o.scrollTop=o.scrollHeight-o.clientHeight:h.forceFireHdlrs(o),t[e].obj.swapIn&&t[e].obj.swapIn(),"null"==n?r():setTimeout(r,1e3)},shpsAjax.pgMgr.chkLded=function(){function s(){shpsAjax.pgMgr.operator.swap(r)}var r=shpsAjax.pgMgr.rqsUrl,n=shpsAjax.pgMgr.cache;if(!n[r].lded){if(!n[r].obj||!n[r].obj.lded)return!1;if(n[r].cpnsNdd&&!n[r].nddLded)return!1;n[r].lded=!0}r!=shpsAjax.pgMgr.crtUrl&&(shpsAjax.load?s():(shpsAjax.ldAnim.setEvtLsnr("exit",s),shpsAjax.load=!0))},shpsAjax.pgMgr.beInformedCpn=function(){var s=shpsAjax.pgMgr.rqsUrl,r=shpsAjax.pgMgr.cache;if(r[s].cpnsNdd){if(r[s].cpnsNdd.some(function(s){return shpsAjax.cpnList[s].lded?void 0:!0}))return!1;r[s].nddLded=!0}shpsAjax.pgMgr.chkLded()},shpsAjax.pgMgr.appendHtml=function(s,r){function n(){shpsAjax.pgMgr.appendHtml=function(s,r){var n=document.getElementById(shpsAjax.pgMgr.cnr_id),e=document.createElement("section");mdf_cls(e,"add","shps-ajax-vp"),e.innerHTML=r;var t=shpsAjax.pgMgr.cache[s];t.elm=e,t.elm.hash=this.rqsHash,e.ttl=t.ttl,e.url=s,shpsAjax.pgMgr.operator.initHvElm3d(e),n.appendChild(e);var a=document.createElement("div");a.classList.add("psd-vp-pg-ph"),document.getElementById("psd-ctt-scrl-cnr").appendChild(a)},shpsAjax.pgMgr.appendHtml(s,r)}document.getElementById(shpsAjax.psdBd_id)?n():domReadyDo(n)},shpsAjax.pgMgr.installItem=function(s,r){var n=this.cache[r],e=shpsCmm.lnkExtFile,t=this;switch(s.type){case"link":e.lnked("link",s.objUrl);break;case"cpnList":n.cpns=s.cpns,n.cpnsNdd=[],n.cpnsUsed=[],s.cpns.forEach(function(s){s.required?n.cpnsNdd.push(s.name):n.cpnsUsed.push(s.name)});break;case"html":this.appendHtml(r,s.html);break;case"script":e.lnked("script",s.objUrl,s.async).then(function(){n.obj||shpsAjax.hooks[n.hookName]&&(shpsAjax.hooks[n.hookName](r),n.obj.onload(function(){n.obj.lded=!0,t.chkLded()}))});break;case"module":shpsCmm.moduleMgr.get(s.url,!0)}},shpsAjax.pgMgr.beInformedRsc=function(s,r){if(s==this.rqsUrl){var n=this.cache;if(!n[s]){var e=n[s]={};e.hookName=r.hook,e.ttl=r.ttl,r.settings&&(e.settings=r.settings),e.cpns=[];var t=shpsCmm.iCMgr,a=this,p=[];r.rscs.forEach(function(s){var r=[];s.forEach(function(s){var n;n="html"==s.type||"cpnList"==s.type||"module"==s.type?Promise.resolve(s):t.getBlobUrl(s.url,s.type).then(function(r){return s.objUrl=r,s}),r.push(n)});var n=Promise.all(r);p.push(n)}),p.reduce(function(r,n){return r.then(function(){return n}).then(function(r){return r.forEach(function(r){a.installItem(r,s)}),Promise.resolve()})},Promise.resolve()).then(function(){shpsAjax.cpnMgr.rqs(e.cpns,"pg")})}}},shpsAjax.pgMgr.rqs=function(s,r){if(this.rqsHash=r,s==shpsAjax.pgMgr.rqsUrl)return!1;if(this.rqsUrl=s,s==shpsAjax.pgMgr.crtUrl)return!1;var n=shpsAjax.pgMgr.cache;if(!n[s]){shpsAjax.load=!1,shpsAjax.ldAnim.entry();var e={};return e.url=s,e.overwrite=!0,shpsAjax.rscMgr.rqs(e,"pg"),!1}if(!n[s].lded)return shpsAjax.load=!1,shpsAjax.ldAnim.entry(),n[s].cpnsNdd&&(n[s].nddLded||shpsAjax.cpnMgr.rqs(n[s].cpns,"pg")),!1;if(n[s].cpnsNdd&&n[s].cpnsNdd.some(function(s){var r=shpsAjax.cpnMgr.operator.listOpened.indexOf(s);return-1==r?!0:void 0})){var t={instant:!0};n[s].obj.exit(t),n[s].obj.introed=!1}shpsAjax.cpnMgr.rqs(n[s].cpns,"pg")},shpsAjax.hashChange=function(){},shpsAjax.lnkMgr={},shpsAjax.lnkMgr.registry={},shpsAjax.lnkMgr.lastUrl="",shpsAjax.lnkMgr.register=function(s){var r=s.hash.replace("#!","");this.registry[r]||(this.registry[r]=[]),this.registry[r].push(s),this.lastUrl==r&&s.removeAttribute("href")},shpsAjax.lnkMgr.beInformed=function(s){if(this.registry[s]&&this.registry[s].forEach(function(s){s.removeAttribute("href")}),this.registry[this.lastUrl]){var r=this;this.registry[this.lastUrl].forEach(function(s){s.href="#!"+r.lastUrl})}this.lastUrl=s},shpsAjax.logo_rszTxt=function(){},domReadyDo(function(){var s="shps-logo",r,n=document.getElementById(s),e=n.getElementsByTagName("span"),t=n.parentElement;shpsAjax.logo_rszTxt=function(){r=e[0].offsetHeight,n.style.lineHeight=r+"px",n.style.fontSize=r+"px",t.classList.contains("shps-logo-smaller")&&(t.style.perspectiveOrigin="50% "+1.5*r+"px")},shpsAjax.ldAnim={},shpsAjax.ldAnim.status="end",shpsAjax.ldAnim.cbs={},shpsAjax.ldAnim.cbs.end=[],shpsAjax.ldAnim.cbs.load=[],shpsAjax.ldAnim.cbs.exit=[],shpsAjax.ldAnim.evtVal=function(s){switch(s){case"end":case"load":case"exit":return!0;default:return!1}},shpsAjax.ldAnim.tgrEvtLsnr=function(s){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s].length>0&&(shpsAjax.ldAnim.cbs[s].forEach(function(s){s()}),shpsAjax.ldAnim.cbs[s]=[]),void 0):!1},shpsAjax.ldAnim.addEvtLsnr=function(s,r){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s].push(r),void 0):!1},shpsAjax.ldAnim.rmvEvtLsnr=function(s,r){if(!shpsAjax.ldAnim.evtVal(s))return!1;var n=shpsAjax.ldAnim.cbs[s].indexOf(r);-1!=n&&shpsAjax.ldAnim.cbs[s].splice(n,1)},shpsAjax.ldAnim.clrEvtLsnr=function(s){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s]=[],void 0):!1},shpsAjax.ldAnim.setEvtLsnr=function(s,r){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.clrEvtLsnr(s),shpsAjax.ldAnim.addEvtLsnr(s,r),void 0):!1};var a=function(){a=function(){};var s=t.getElementsByTagName("a")[0];s.href="#!homepage/",s.title="Go to the homepage.",shpsAjax.lnkMgr.register(s)};shpsAjax.ldAnim.onload=function(){shpsAjax.ldAnim.status="onload",shpsAjax.ldAnim.tgrEvtLsnr("load"),setTimeout(function(){for(var s=e.length-1;s>=0;s--){var p=e[s];mdf_cls(p,"remove","shps-logo-loading-in-trans"),p.classList.remove("shps-logo-entry-trans"),p.style.left=p.offsetLeft+"px",p.style.position="absolute",p.offsetHeight,mdf_cls(p,"add","shps-logo-loading-end-trans"),p.offsetHeight,p.classList.add("shps-logo-3d-"+s),p.style.left="50%",p.offsetHeight}n.style.removeProperty("animation-duration"),mdf_cls(n,"add","shps-logo-constant-rotate"),mdf_cls(n,"add","shps-logo-rotate"),t.addEventListener("transitionend",function o(s){s.target==this&&(s.target.removeEventListener(s.type,o),setTimeout(function(){t.style.top="1vmin",t.addEventListener("transitionend",function s(r){r.target.removeEventListener(r.type,s),a(),shpsAjax.ldAnim.status="end",shpsAjax.ldAnim.tgrEvtLsnr("end")},!1),mdf_cls(t,"add","shps-logo-smaller"),mdf_cls(t,"remove","shps-logo-larger"),shpsAjax.logo_rszTxt(),shpsAjax.ldAnim.tgrEvtLsnr("exit")},1e3))},!1),t.style.top=t.offsetTop-r+"px",mdf_cls(t,"add","shps-logo-larger")},500)},shpsAjax.ldAnim.ldg=function(){function s(){var t=e[n];t.addEventListener("transitionend",function(s){s.target.removeEventListener(s.type,arguments.callee),mdf_cls(t,"remove","shps-logo-entry-trans"),mdf_cls(t,"add","shps-logo-loading-in-init"),mdf_cls(t,"remove","shps-logo-loading-out"),t.offsetHeight,t===e[0]&&t.addEventListener("transitionend",function(s){s.target.removeEventListener(s.type,arguments.callee),shpsAjax.load?shpsAjax.ldAnim.onload():r()},!1),setTimeout(function(){mdf_cls(t,"add","shps-logo-loading-in-trans"),mdf_cls(t,"remove","shps-logo-loading-in-init"),t.offsetHeight},1e3)},!1),mdf_cls(t,"remove","shps-logo-loading-in-trans"),mdf_cls(t,"add","shps-logo-entry-trans"),mdf_cls(t,"add","shps-logo-loading-out"),t.offsetHeight,n--,n>=0&&setTimeout(s,100)}function r(){setTimeout(function(){n=e.length-1,s()},1e3)}shpsAjax.ldAnim.status="ldg";var n;r()},shpsAjax.ldAnim.chkLding=function(){shpsAjax.load?shpsAjax.ldAnim.onload():shpsAjax.ldAnim.ldg()},shpsAjax.ldAnim.entry=function(){function s(){mdf_cls(e[r],"remove","opa-0"),mdf_cls(e[r],"remove","shps-logo-letter-init-size"),r++,r<e.length&&setTimeout(s,200)}shpsAjax.ldAnim.entry=function(){function s(r){this.removeEventListener(r.type,s),this.classList.remove("shps-logo-loading-end-trans"),this.style.removeProperty("position"),this.style.removeProperty("left")}if(shpsAjax.load)return!0;switch(shpsAjax.ldAnim.status){case"end":shpsAjax.ldAnim.status="entry",t.addEventListener("transitionend",function o(s){return s.target!=this?!1:(s.target.removeEventListener(s.type,o),shpsAjax.ldAnim.chkLding(),void 0)}),t.style.removeProperty("top"),t.style.removeProperty("perspective-origin"),t.classList.remove("shps-logo-smaller");for(var r=e.length-1,a=r;a>=0;a--){var p=e[a];p.addEventListener("transitionend",s),p.style.left=25*a+"%",mdf_cls(p,"remove","shps-logo-3d-"+a),p.offsetHeight}mdf_cls(n,"remove","shps-logo-constant-rotate"),n.style.animationDuration="1s";break;case"onload":shpsAjax.ldAnim.setEvtLsnr("end",shpsAjax.ldAnim.entry)}},e[e.length-1].addEventListener("transitionend",function(s){s.target.removeEventListener(s.type,arguments.callee),shpsAjax.ldAnim.chkLding()});var r=0;s()};var p=shpsAjax.psdMenu.elm=document.getElementById(shpsAjax.psdMenu.id);p.lastW=0,optElmRszLsnr.add(p,function(){p.offsetWidth!=p.lastW&&(p.lastW=p.offsetWidth,shpsAjax.psdMenu.fixedFcts.forEach(function(s){s(p.lastW)}))});var o=shpsAjax.pgMgr.operator.scrlCnr=document.getElementById("psd-ctt-scrl-cnr"),i=shpsAjax.pgMgr.operator.cttCnr=document.getElementById(shpsAjax.pgMgr.cnr_id);shpsAjax.pgMgr.operator.hvScrl=function(){},o.lastElm,o.lastIdx,shpsCmm.scrlSnap.add(o,function(){return{v:i.children.length}},function(s){var r=s.v,n=i.children[r];(n!=o.lastElm||o.lastIdx!=r)&&(r!=o.lastIdx&&(shpsAjax.pgMgr.operator.hvScrl(r-o.lastIdx),o.lastIdx=r),o.lastElm=n,shpsAjax.pgMgr.operator.move3d(n))}),shpsAjax.hashChange=function(){var s=window.location.hash,r=/^#!([^#]+.*)/,n=r.exec(s);if(null!==n)if(n=n[1],shpsAjax.lnkMgr.beInformed(n),r=/^([^#]+)(#.*)/,s=r.exec(n),null!==s&&(n=s[1],s=s[2]),n!=shpsAjax.pgMgr.crtUrl||n!=shpsAjax.pgMgr.rqsUrl)shpsAjax.pgMgr.rqs(n,s);else{var e=shpsAjax.pgMgr;e.operator.closeHv();var t=e.cache[n];t.obj.hashChange&&t.obj.hashChange(s),t.elm.hash=s}}}),shpsCmm.wdwLded().then(function(){var s=shpsAjax.logo_rszTxt;s(),optRszLsnr.add(s),window.location.hash.indexOf("#!")>=0?shpsAjax.hashChange():shpsAjax.pgMgr.rqs("homepage/"),window.addEventListener("hashchange",shpsAjax.hashChange,!1);var r=shpsCmm.iCMgr,n=shpsCmm.lnkExtFile;r.getBlobUrl("/csss/history_sys.css","link").then(function(s){n.lnked("link",s)});var e=[{name:"historyView"},{name:"betaSign",required:!0}];shpsAjax.cpnMgr.rqs(e,void 0,!0),r.getBlobUrl("/shared/jss/gtm.js","script").then(function(s){n.lnked("script",s)})});