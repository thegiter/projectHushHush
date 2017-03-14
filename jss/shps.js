"use strict";function shpsAjax(){}shpsAjax.dependenciesP=shpsCmm.moduleMgr.get("/shared/modules/dependencies/",!0),shpsAjax.dependenciesLded=function(){return this.dependenciesP},shpsAjax.siteTtl="SHPS",shpsAjax.load=!1,shpsAjax.debug={},shpsAjax.debug.checkUrl=function(s){return"/debug/view.php"==window.location.pathname?"/"+s:s},shpsAjax.rqsMgr={},shpsAjax.rqsMgr.ongoing=[],shpsAjax.rqsMgr.pending=[],shpsAjax.rqsMgr.abort=function(){shpsAjax.rqsMgr.ongoing.forEach(function(s){s.qable&&-1==shpsAjax.rqsMgr.pending.indexOf(s)&&shpsAjax.rqsMgr.pending.push(s),s.ajax.abort()}),shpsAjax.rqsMgr.ongoing=[]},shpsAjax.rqsMgr.removeOngoing=function(s){var n=shpsAjax.rqsMgr.ongoing.indexOf(s);-1!=n&&shpsAjax.rqsMgr.ongoing.splice(n,1)},shpsAjax.rqsMgr.removePending=function(s){var n=shpsAjax.rqsMgr.pending.indexOf(s);-1!=n&&shpsAjax.rqsMgr.pending.splice(n,1)},shpsAjax.rqsMgr.load=function(s,n){var r=this;this.pending.forEach(function(n){n.url==s.url&&r.removePending(n)}),this.ongoing.forEach(function(n){return n.url==s.url?!1:void 0}),s.overwrite&&this.abort(),this.ongoing.push(s),shpsCmm.createAjax("POST",shpsAjax.debug.checkUrl(s.url),shpsCmm.getRefererParam(),s.type,s.hdrs,s).then(function(e){r.removeOngoing(s),n(e),r.attemptPending()})},shpsAjax.rqsMgr.attemptPending=function(){shpsAjax.rqsMgr.pending.length>0&&0===shpsAjax.rqsMgr.ongoing.length&&shpsAjax.rqsMgr.load(shpsAjax.rqsMgr.pending[0])},shpsAjax.rscMgr={},shpsAjax.rscMgr.rscs={},shpsAjax.rscMgr.inform=function(s,n){shpsAjax.dependenciesLded().then(function(){switch(s){case"pg":shpsAjax.pgMgr.beInformedRsc(n,shpsAjax.rscMgr.rscs[n]);break;case"cpnLdr":shpsAjax.cpnMgr.ldr.beInformedRsc(n,shpsAjax.rscMgr.rscs[n])}})},shpsAjax.rscMgr.onLd=function(s,n,r){this.rscs[s]=r,this.inform(n,s)},shpsAjax.rscMgr.rqs=function(s,n){if(this.rscs[s.url])this.inform(n,s.url);else{var r=shpsCmm.idbCache,e=shpsCmm.iCMgr,t=this;r.get(s.url).then(function(a){if(t.onLd(s.url,n,a.rscList),a.dlDate+1e3*a.maxAge<Date.now()){var p=[{hdr:"If-Modified-Since",value:a.lastModified}];a.eTag&&p.push({hdr:"If-None-Match",value:a.eTag}),s.type="json",shpsCmm.createAjax("GET",shpsAjax.debug.checkUrl(s.url),void 0,s.type,p).then(function(n){200==n.status&&shpsAjax.rqsMgr.load(s,function(n){var t=e.createRecordObj(n);t&&(t.rscList=n.response,t.url=s.url,r.set(t))})})}},function(){s.type="json",shpsAjax.rqsMgr.load(s,function(a){var p=e.createRecordObj(a);p&&(t.onLd(s.url,n,a.response),p.rscList=a.response,p.url=s.url,r.set(p))})})}},shpsAjax.psdBd_id="psd-bd-cnr",shpsAjax.hooks={},shpsAjax.cpnList={historyView:{url:"shared/shps_ajax/hv/",cnr_id:"psd-menu-scrl-cnr",lded:!1},betaSign:{url:"shared/cpns/beta_sign/",cnr_id:"the-shps-ajax-bd",lded:!1},bg_basic:{url:"shared/bgs/basic/",cnr_id:"psd-bg-cnr",lded:!1},bg_tdcubes:{url:"shared/bgs/tdcubes/",cnr_id:"psd-bg-cnr",lded:!1},bg_inttile:{url:"shared/bgs/inttile/",cnr_id:"psd-bg-cnr",lded:!1},bg_tlight:{url:"shared/bgs/tlight/",cnr_id:"psd-bg-cnr",lded:!1},menu_basic:{url:"",cnr_id:"psd-menu-cnr",lded:!1}},shpsAjax.cpnMgr={},shpsAjax.cpnMgr.ldr={},shpsAjax.cpnMgr.ldr.rqss=[],shpsAjax.cpnMgr.ldr.rqd=[],shpsAjax.cpnMgr.ldr.nonRqd=[],shpsAjax.cpnMgr.ldr.chkAll=function(){var s=shpsAjax.cpnMgr.ldr.rqd,n=shpsAjax.cpnMgr.ldr.nonRqd;if(s.length>0||n.length>0){if(s.length>0){if(!shpsAjax.cpnMgr.ldr.rqd.every(function(s){return shpsAjax.cpnList[s.name].lded?!0:!1}))return!1;shpsAjax.cpnMgr.ldr.rqd=[]}shpsAjax.cpnMgr.beInformedLded(),n.length>0&&(shpsAjax.cpnMgr.ldr.internalLoad(shpsAjax.cpnMgr.ldr.nonRqd),shpsAjax.cpnMgr.ldr.nonRqd=[])}},shpsAjax.cpnMgr.ldr.chkLded=function(s){if(!shpsAjax.cpnList[s].lded){if(!shpsAjax.cpnList[s].obj.lded)return!1;if(shpsAjax.cpnList[s].cpnsNdd&&shpsAjax.cpnList[s].cpnsNdd.some(function(s){return shpsAjax.cpnList[s].lded?void 0:!0}))return!1;if(!shpsAjax.cpnList[s].modules.every(function(s){return s.lded}))return!1;if(shpsAjax.cpnList[s].cpnsUsed){var n=[];shpsAjax.cpnList[s].cpnsUsed.forEach(function(s){if(!shpsAjax.cpnList[s].lded){var r={name:s};n.push(r)}}),n.length>0&&shpsAjax.cpnMgr.ldr.internalLoad(n)}shpsAjax.cpnList[s].lded=!0,shpsAjax.cpnMgr.operator.beInformedLded(s)}shpsAjax.cpnList[s].forCpns&&shpsAjax.cpnList[s].forCpns.forEach(function(s){shpsAjax.cpnMgr.ldr.chkLded(s)}),shpsAjax.cpnMgr.ldr.chkAll()},shpsAjax.cpnMgr.ldr.appendHtml=function(s,n){function r(){shpsAjax.cpnMgr.ldr.appendHtml=function(s,n){var r=document.getElementById(shpsAjax.cpnList[n].cnr_id),e=document.createElement("div");e.innerHTML=s,forEachNodeItem(e.children,function(s){r.appendChild(s)})},shpsAjax.cpnMgr.ldr.appendHtml(s,n)}document.getElementById(shpsAjax.psdBd_id)?r():window.addEventListener("load",r,!1)},shpsAjax.cpnMgr.ldr.installItem=function(s,n){var r=shpsAjax.cpnList[n],e=shpsCmm.lnkExtFile,t=this;switch(s.type){case"link":e.lnked("link",s.objUrl);break;case"script":e.lnked("script",s.objUrl,s.async).then(function(){r.obj||shpsAjax.hooks[r.hookName]&&(shpsAjax.hooks[r.hookName](n),r.obj.onload(function(){r.obj.lded=!0,t.chkLded(n)}))});break;case"cpnList":r.cpnsNdd=[],r.cpnsUsed=[],r.closeCpns=[],r.nonCloseCpns=[];var a=[],p=shpsAjax.cpnList;s.cpns.forEach(function(s){s.required?(p[s.name].forCpns||(p[s.name].forCpns=[]),p[s.name].forCpns.push(n),r.cpnsNdd.push(s.name),shpsAjax.cpnList[s.name].lded||a.push(s)):r.cpnsUsed.push(s.name),s.close?r.closeCpns.push(s.name):r.nonCloseCpns.push(s.name)}),a.length>0?t.internalLoad(a):t.chkLded(n);break;case"html":t.appendHtml(s.html,n);break;case"module":r.modules.push(s),s.lded=!1,shpsCmm.moduleMgr.get(s.url,!0).then(function(){s.lded=!0,t.chkLded(n)})}},shpsAjax.cpnMgr.ldr.beInformedRsc=function(s,n){var r,e;if(this.rqss.some(function(n){return shpsAjax.cpnList[n.name].url==s?(r=n.name,e=shpsAjax.cpnList[n.name],!0):void 0})){if(e.hasRsc)return!1;e.hasRsc=!0,e.hookName=n.hook,e.modules=[];var t=shpsCmm.iCMgr,a=this,p=[];n.rscs.forEach(function(s){var n=[];s.forEach(function(s){var r;r="html"==s.type||"cpnList"==s.type||"module"==s.type?Promise.resolve(s):t.getBlobUrl(s.url,s.type).then(function(n){return s.objUrl=n,s}),n.push(r)});var r=Promise.all(n);p.push(r)}),p.reduce(function(s,n){return s.then(function(){return n}).then(function(s){return s.forEach(function(s){a.installItem(s,r)}),Promise.resolve()})},Promise.resolve())}},shpsAjax.cpnMgr.ldr.internalLoad=function(s){s.forEach(function(s){var n=s.name;if(!shpsAjax.cpnList[n].lded){var r={};r.url=shpsAjax.cpnList[n].url,shpsAjax.rscMgr.rqs(r,"cpnLdr")}})},shpsAjax.cpnMgr.ldr.load=function(s){shpsAjax.cpnMgr.ldr.rqss=s.slice(),shpsAjax.cpnMgr.ldr.rqd=[],shpsAjax.cpnMgr.ldr.nonRqd=[],s.forEach(function(s){shpsAjax.cpnList[s.name].lded||(s.required?shpsAjax.cpnMgr.ldr.rqd.push(s):shpsAjax.cpnMgr.ldr.nonRqd.push(s))}),shpsAjax.cpnMgr.ldr.rqd.length>0?shpsAjax.cpnMgr.ldr.internalLoad(shpsAjax.cpnMgr.ldr.rqd):shpsAjax.cpnMgr.ldr.chkAll()},shpsAjax.cpnMgr.operator={},shpsAjax.cpnMgr.operator.rqsList=[],shpsAjax.cpnMgr.operator.listOpened=[],shpsAjax.cpnMgr.operator.rqsArgs=[],shpsAjax.cpnMgr.operator.addRa=function(s,n){var r=this.rqsArgs;n&&(r[s]||(r[s]=[]),-1==r[s].indexOf(n)&&r[s].push(n))},shpsAjax.cpnMgr.operator.open=function(s,n){if(-1==shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.rqsList.push(s),this.addRa(s,n),shpsAjax.cpnList[s].lded)if(-1==shpsAjax.cpnMgr.operator.listOpened.indexOf(s)){shpsAjax.cpnMgr.operator.listOpened.push(s);var r=this.rqsArgs;r[s]?(r[s].forEach(function(n){shpsAjax.cpnList[s].obj.intro(n)}),delete r[s]):shpsAjax.cpnList[s].obj.intro()}else n&&shpsAjax.cpnList[s].obj.intro(n)},shpsAjax.cpnMgr.operator.beInformedLded=function(s){-1==shpsAjax.cpnMgr.operator.listOpened.indexOf(s)&&-1!=shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.open(s)},shpsAjax.cpnMgr.operator.rmvSubCpns=function(s){s.forEach(function(s){shpsAjax.cpnList[s].closeCpns&&shpsAjax.cpnList[s].closeCpns.forEach(function(s){var n=shpsAjax.cpnMgr.operator.rqsList.indexOf(s);-1!=n&&shpsAjax.cpnMgr.operator.rqsList.splice(n,1)})})},shpsAjax.cpnMgr.operator.mdfList=function(s){return s.forEach(function(s){shpsAjax.cpnList[s].nonCloseCpns&&shpsAjax.cpnList[s].nonCloseCpns.forEach(function(s){-1==shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.rqsList.push(s)})}),shpsAjax.cpnMgr.operator.rqsList!=s?(shpsAjax.cpnMgr.operator.mdfList(shpsAjax.cpnMgr.operator.rqsList),void 0):(shpsAjax.cpnMgr.operator.rmvSubCpns(s),void 0)},shpsAjax.cpnMgr.operator.openOnlyThese=function(s){shpsAjax.cpnMgr.operator.rqsList=s.slice(),shpsAjax.cpnMgr.operator.mdfList(s),shpsAjax.cpnMgr.operator.listOpened.forEach(function(s){var n=shpsAjax.cpnMgr.operator.rqsList.indexOf(s);-1!=n?shpsAjax.cpnMgr.operator.rqsList.splice(n,1):(shpsAjax.cpnMgr.operator.listOpened.splice(shpsAjax.cpnMgr.operator.listOpened.indexOf(s),1),shpsAjax.cpnList[s].obj.exit())}),shpsAjax.cpnMgr.operator.rqsList.forEach(function(s){shpsAjax.cpnList[s].lded&&shpsAjax.cpnMgr.operator.open(s)})},shpsAjax.cpnMgr.busy=!1,shpsAjax.cpnMgr.rqss=[],shpsAjax.cpnMgr.q=[],shpsAjax.cpnMgr.src=void 0,shpsAjax.cpnMgr.beInformedLded=function(){if(shpsAjax.cpnMgr.busy=!1,"pg"==this.src){var s=[];this.rqss.forEach(function(n){n.close||s.push(n.name)}),shpsAjax.cpnMgr.operator.openOnlyThese(s),shpsAjax.pgMgr.beInformedCpn()}else{if(!this.q[0].every(function(s){return shpsAjax.cpnList[s.name].lded?!0:void 0}))return!1;this.q.shift()}shpsAjax.cpnMgr.q.length>0&&shpsAjax.cpnMgr.rqs(shpsAjax.cpnMgr.q[0],void 0,!0)},shpsAjax.cpnMgr.rqs=function(s,n,r){if("pg"==n)this.src=n,this.rqss=s.slice();else{if(r&&-1==this.q.indexOf(s)&&this.q.push(s),this.busy)return!1;this.src=void 0;var e=this;s.forEach(function(s){-1==e.rqss.indexOf(s)&&e.rqss.push(s)})}this.busy=!0;var t=[];this.rqss.forEach(function(s){shpsAjax.cpnList[s.name].lded||t.push(s)}),t.length>0?shpsAjax.cpnMgr.ldr.load(t):shpsAjax.cpnMgr.beInformedLded()},shpsAjax.psdMenu={},shpsAjax.psdMenu.noExp=!1,shpsAjax.psdMenu.id="psd-menu-cnr",shpsAjax.psdMenu.disableHg=function(){},shpsAjax.psdMenu.enableHg=function(){},shpsAjax.psdMenu.noExpand=function(){this.noExp=!0,this.disableHg();var s=shpsAjax.psdMenu.elm;s.style.maxWidth=s.offsetWidth+"px",s.offsetWidth,mdf_cls(s,"add","psd-menu-no-expand")},shpsAjax.psdMenu.doExpand=function(){this.noExp=!1;var s=shpsAjax.psdMenu.elm;s.style.removeProperty("max-width"),mdf_cls(s,"remove","psd-menu-no-expand"),this.enableHg()},shpsAjax.psdMenu.fixedFcts=[],shpsAjax.psdMenu.regFixed=function(s){shpsAjax.psdMenu.fixedFcts.push(s)},shpsAjax.pgMgr={},shpsAjax.pgMgr.crtUrl="null",shpsAjax.pgMgr.rqsUrl="null",shpsAjax.pgMgr.rqsHash=null,shpsAjax.pgMgr.cache={},shpsAjax.pgMgr.cache["null"]={},shpsAjax.pgMgr.cache["null"].lded=!0,shpsAjax.pgMgr.cache["null"].obj={},shpsAjax.pgMgr.cache["null"].obj.intro=function(){},shpsAjax.pgMgr.cnr_id="psd-ctt-cnr",shpsAjax.pgMgr.operator={},shpsAjax.pgMgr.operator.getPgIdx=function(s){var n;return forEachNodeItem(shpsAjax.pgMgr.operator.cttCnr.children,function(r,e){r==s&&(n=e)}),n},shpsAjax.pgMgr.operator.getElms_bfr=function(s){var n=shpsAjax.pgMgr.operator.getPgIdx(s),r=this.cttCnr.children;if(n>0){for(var e=[],t=0;n>t;t++)e.push(r[t]);return e}return!1},shpsAjax.pgMgr.operator.getElms_afr=function(s){var n=shpsAjax.pgMgr.operator.getPgIdx(s),r=this.cttCnr.children;if(n<r.length-1){for(var e=[],t=n+1;t<r.length;t++)e.push(r[t]);return e}return!1},shpsAjax.pgMgr.operator.global3dOffset=120,shpsAjax.pgMgr.operator.single3dOffset=20,shpsAjax.pgMgr.operator.sdw3dSize=12,shpsAjax.pgMgr.operator.sdwOffset=14,shpsAjax.pgMgr.operator.hv3d_bfr=function(s){},shpsAjax.pgMgr.operator.move3d_bfr=function(s){var n=shpsAjax.pgMgr.operator.getElms_bfr(s),r=n.length-1;if(n!==!1){var e=parseFloat(getStyle(document.documentElement,"font-size"))*shpsAjax.pgMgr.operator.sdw3dSize,t=e/s.offsetHeight*100+100+shpsAjax.pgMgr.operator.sdwOffset,a=(t-this.global3dOffset/2)/t*90,p=this;n.forEach(function(s){s.style.transformOrigin="top",s.style.transform="translatey(-"+(p.global3dOffset+p.single3dOffset*r)+"%) rotatex(90deg) translatey(-"+p.global3dOffset+"%) rotatex(-90deg) rotatex(-"+a+"deg)",r--,p.hv3d_bfr(s)})}},shpsAjax.pgMgr.operator.hv3d_afr=function(s){},shpsAjax.pgMgr.operator.move3d_afr=function(s){var n=this.getElms_afr(s),r=0;if(n!==!1){var e=parseFloat(getStyle(document.documentElement,"font-size"))*this.sdw3dSize,t=e/s.offsetHeight*100+100+this.sdwOffset,a=(t-this.global3dOffset/2)/t*90,p=this;n.forEach(function(s){s.style.transformOrigin="bottom",s.style.transform="translatey("+(p.global3dOffset+p.single3dOffset*r)+"%) rotatex(90deg) translatey(-"+p.global3dOffset+"%) rotatex(-90deg) rotatex("+a+"deg)",r++,p.hv3d_afr(s)})}},shpsAjax.pgMgr.operator.hv3d_rmv=function(s){},shpsAjax.pgMgr.operator.remove3d=function(s){s.style.removeProperty("transform"),this.hv3d_rmv(s)},shpsAjax.pgMgr.operator.move3d=function(s){this.remove3d(s),this.move3d_bfr(s),this.move3d_afr(s)},shpsAjax.pgMgr.operator.closeHv=function(){this.scrlCnr.classList.remove("psd-vp-cnr-scrl")},shpsAjax.pgMgr.operator.initHvElm3d=function(){},shpsAjax.pgMgr.operator.swap=function(s){function n(){var n={};if(t[s].obj.hashChange){var r=shpsAjax.pgMgr.rqsHash;t[s].elm.hash=r,n.cb=function(){t[s].obj.hashChange(r)},n.cbTiming="end"}t[s].obj.introed?n.cb&&n.cb():(t[s].obj.intro(n),t[s].obj.introed=!0);var e=shpsAjax.psdMenu;t[s].settings&&t[s].settings.psdMenuNoExp?e.noExp||e.noExpand():e.noExp&&e.doExpand()}var r=shpsAjax.pgMgr.crtUrl,e=shpsAjax.pgMgr.crtUrl=s,t=shpsAjax.pgMgr.cache,a=shpsAjax.pgMgr.cache[s].elm,p=shpsAjax.pgMgr.operator.getElms_afr(a);p!==!1&&p.forEach(function(s){document.getElementById(shpsAjax.pgMgr.cnr_id).insertBefore(s,a),s.offsetHeight});var o=this.scrlCnr;o.classList.add("psd-vp-cnr-scrl");var i=this,h=shpsCmm.scrlSnap;h.add(o,function(){return{v:1}},function d(){h.rmv(o,d),t[r].obj.swapOut&&t[r].obj.swapOut(),i.closeHv()}),o.scrollTop+o.clientHeight<o.scrollHeight-1?o.scrollTop=o.scrollHeight-o.clientHeight:h.forceFireHdlrs(o),t[e].obj.swapIn&&t[e].obj.swapIn(),"null"==r?n():setTimeout(n,1e3)},shpsAjax.pgMgr.chkLded=function(){var s=shpsAjax.pgMgr.rqsUrl,n=shpsAjax.pgMgr.cache;if(!n[s].lded){if(!n[s].obj||!n[s].obj.lded)return!1;if(n[s].cpnsNdd&&!n[s].nddLded)return!1;if(!n[s].modules.every(function(s){return s.lded}))return!1;n[s].lded=!0}if(s!=shpsAjax.pgMgr.crtUrl){var r=function e(){shpsAjax.pgMgr.operator.swap(s)};shpsAjax.load?r():(shpsAjax.ldAnim.setEvtLsnr("exit",r),shpsAjax.load=!0)}},shpsAjax.pgMgr.beInformedCpn=function(){var s=shpsAjax.pgMgr.rqsUrl,n=shpsAjax.pgMgr.cache;if(n[s].cpnsNdd){if(n[s].cpnsNdd.some(function(s){return shpsAjax.cpnList[s].lded?void 0:!0}))return!1;n[s].nddLded=!0}shpsAjax.pgMgr.chkLded()},shpsAjax.pgMgr.appendHtml=function(s,n){function r(){shpsAjax.pgMgr.appendHtml=function(s,n){var r=document.getElementById(shpsAjax.pgMgr.cnr_id),e=document.createElement("section");mdf_cls(e,"add","shps-ajax-vp"),e.innerHTML=n;var t=shpsAjax.pgMgr.cache[s];t.elm=e,t.elm.hash=this.rqsHash,e.ttl=t.ttl,e.url=s,shpsAjax.pgMgr.operator.initHvElm3d(e),r.appendChild(e);var a=document.createElement("div");a.classList.add("psd-vp-pg-ph"),document.getElementById("psd-ctt-scrl-cnr").appendChild(a)},shpsAjax.pgMgr.appendHtml(s,n)}document.getElementById(shpsAjax.psdBd_id)?r():domReadyDo(r)},shpsAjax.pgMgr.installItem=function(s,n){var r=this.cache[n],e=shpsCmm.lnkExtFile,t=this;switch(s.type){case"link":e.lnked("link",s.objUrl);break;case"cpnList":r.cpns=s.cpns,r.cpnsNdd=[],r.cpnsUsed=[],s.cpns.forEach(function(s){s.required?r.cpnsNdd.push(s.name):r.cpnsUsed.push(s.name)});break;case"html":this.appendHtml(n,s.html);break;case"script":e.lnked("script",s.objUrl,s.async).then(function(){r.obj||shpsAjax.hooks[r.hookName]&&(shpsAjax.hooks[r.hookName](n),r.obj.onload(function(){r.obj.lded=!0,t.chkLded()}))});break;case"module":r.modules.push(s),s.lded=!1,shpsCmm.moduleMgr.get(s.url,!0).then(function(){s.lded=!0,t.chkLded()})}},shpsAjax.pgMgr.beInformedRsc=function(s,n){if(s==this.rqsUrl){var r=this.cache;if(!r[s]){var e=r[s]={};e.hookName=n.hook,e.ttl=n.ttl,n.settings&&(e.settings=n.settings),e.cpns=[],e.modules=[];var t=shpsCmm.iCMgr,a=this,p=[];n.rscs.forEach(function(s){var n=[];s.forEach(function(s){var r;r="html"==s.type||"cpnList"==s.type||"module"==s.type?Promise.resolve(s):t.getBlobUrl(s.url,s.type).then(function(n){return s.objUrl=n,s}),n.push(r)});var r=Promise.all(n);p.push(r)}),p.reduce(function(n,r){return n.then(function(){return r}).then(function(n){return n.forEach(function(n){a.installItem(n,s)}),Promise.resolve()})},Promise.resolve()).then(function(){shpsAjax.cpnMgr.rqs(e.cpns,"pg")})}}},shpsAjax.pgMgr.rqs=function(s,n){if(this.rqsHash=n,s==shpsAjax.pgMgr.rqsUrl)return!1;if(this.rqsUrl=s,s==shpsAjax.pgMgr.crtUrl)return!1;var r=shpsAjax.pgMgr.cache;if(!r[s]){shpsAjax.load=!1,shpsAjax.ldAnim.entry();var e={};return e.url=s,e.overwrite=!0,shpsAjax.rscMgr.rqs(e,"pg"),!1}if(!r[s].lded)return shpsAjax.load=!1,shpsAjax.ldAnim.entry(),r[s].cpnsNdd&&(r[s].nddLded||shpsAjax.cpnMgr.rqs(r[s].cpns,"pg")),!1;if(r[s].cpnsNdd&&r[s].cpnsNdd.some(function(s){var n=shpsAjax.cpnMgr.operator.listOpened.indexOf(s);return-1==n?!0:void 0})){var t={instant:!0};r[s].obj.exit(t),r[s].obj.introed=!1}shpsAjax.cpnMgr.rqs(r[s].cpns,"pg")},shpsAjax.hashChange=function(){},shpsAjax.lnkMgr={},shpsAjax.lnkMgr.registry={},shpsAjax.lnkMgr.lastUrl="",shpsAjax.lnkMgr.register=function(s){var n=s.hash.replace("#!","");this.registry[n]||(this.registry[n]=[]),this.registry[n].push(s),this.lastUrl==n&&s.removeAttribute("href")},shpsAjax.lnkMgr.beInformed=function(s){if(this.registry[s]&&this.registry[s].forEach(function(s){s.removeAttribute("href")}),this.registry[this.lastUrl]){var n=this;this.registry[this.lastUrl].forEach(function(s){s.href="#!"+n.lastUrl})}this.lastUrl=s},shpsAjax.logo_rszTxt=function(){},domReadyDo(function(){var s="shps-logo",n,r=document.getElementById(s),e=r.getElementsByTagName("span"),t=r.parentElement;shpsAjax.logo_rszTxt=function(){n=e[0].offsetHeight,r.style.lineHeight=n+"px",r.style.fontSize=n+"px",t.classList.contains("shps-logo-smaller")&&(t.style.perspectiveOrigin="50% "+1.5*n+"px")},shpsAjax.ldAnim={},shpsAjax.ldAnim.status="end",shpsAjax.ldAnim.cbs={},shpsAjax.ldAnim.cbs.end=[],shpsAjax.ldAnim.cbs.load=[],shpsAjax.ldAnim.cbs.exit=[],shpsAjax.ldAnim.evtVal=function(s){switch(s){case"end":case"load":case"exit":return!0;default:return!1}},shpsAjax.ldAnim.tgrEvtLsnr=function(s){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s].length>0&&(shpsAjax.ldAnim.cbs[s].forEach(function(s){s()}),shpsAjax.ldAnim.cbs[s]=[]),void 0):!1},shpsAjax.ldAnim.addEvtLsnr=function(s,n){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s].push(n),void 0):!1},shpsAjax.ldAnim.rmvEvtLsnr=function(s,n){if(!shpsAjax.ldAnim.evtVal(s))return!1;var r=shpsAjax.ldAnim.cbs[s].indexOf(n);-1!=r&&shpsAjax.ldAnim.cbs[s].splice(r,1)},shpsAjax.ldAnim.clrEvtLsnr=function(s){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s]=[],void 0):!1},shpsAjax.ldAnim.setEvtLsnr=function(s,n){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.clrEvtLsnr(s),shpsAjax.ldAnim.addEvtLsnr(s,n),void 0):!1};var a=function h(){a=function n(){};var s=t.getElementsByTagName("a")[0];s.href="#!homepage/",s.title="Go to the homepage.",shpsAjax.lnkMgr.register(s)};shpsAjax.ldAnim.onload=function(){shpsAjax.ldAnim.status="onload",shpsAjax.ldAnim.tgrEvtLsnr("load"),setTimeout(function(){for(var s=e.length-1;s>=0;s--){var p=e[s];mdf_cls(p,"remove","shps-logo-loading-in-trans"),p.classList.remove("shps-logo-entry-trans"),p.style.left=p.offsetLeft+"px",p.style.position="absolute",p.offsetHeight,mdf_cls(p,"add","shps-logo-loading-end-trans"),p.offsetHeight,p.classList.add("shps-logo-3d-"+s),p.style.left="50%",p.offsetHeight}r.style.removeProperty("animation-duration"),mdf_cls(r,"add","shps-logo-constant-rotate"),mdf_cls(r,"add","shps-logo-rotate"),t.addEventListener("transitionend",function o(s){s.target==this&&(s.target.removeEventListener(s.type,o),setTimeout(function(){t.style.top="1vmin",t.addEventListener("transitionend",function s(n){n.target.removeEventListener(n.type,s),a(),shpsAjax.ldAnim.status="end",shpsAjax.ldAnim.tgrEvtLsnr("end")},!1),mdf_cls(t,"add","shps-logo-smaller"),mdf_cls(t,"remove","shps-logo-larger"),shpsAjax.logo_rszTxt(),shpsAjax.ldAnim.tgrEvtLsnr("exit")},1e3))},!1),t.style.top=t.offsetTop-n+"px",mdf_cls(t,"add","shps-logo-larger")},500)},shpsAjax.ldAnim.ldg=function(){function s(){var t=e[r];t.addEventListener("transitionend",function a(s){s.target.removeEventListener(s.type,a),mdf_cls(t,"remove","shps-logo-entry-trans"),mdf_cls(t,"add","shps-logo-loading-in-init"),mdf_cls(t,"remove","shps-logo-loading-out"),t.offsetHeight,t===e[0]&&t.addEventListener("transitionend",function r(s){s.target.removeEventListener(s.type,r),shpsAjax.load?shpsAjax.ldAnim.onload():n()},!1),setTimeout(function(){mdf_cls(t,"add","shps-logo-loading-in-trans"),mdf_cls(t,"remove","shps-logo-loading-in-init"),t.offsetHeight},1e3)},!1),mdf_cls(t,"remove","shps-logo-loading-in-trans"),mdf_cls(t,"add","shps-logo-entry-trans"),mdf_cls(t,"add","shps-logo-loading-out"),t.offsetHeight,r--,r>=0&&setTimeout(s,100)}function n(){setTimeout(function(){r=e.length-1,s()},1e3)}shpsAjax.ldAnim.status="ldg";var r;n()},shpsAjax.ldAnim.chkLding=function(){shpsAjax.load?shpsAjax.ldAnim.onload():shpsAjax.ldAnim.ldg()},shpsAjax.ldAnim.entry=function(){function s(){mdf_cls(e[n],"remove","opa-0"),mdf_cls(e[n],"remove","shps-logo-letter-init-size"),n++,n<e.length&&setTimeout(s,200)}shpsAjax.ldAnim.entry=function(){if(shpsAjax.load)return!0;switch(shpsAjax.ldAnim.status){case"end":shpsAjax.ldAnim.status="entry",t.addEventListener("transitionend",function o(s){return s.target!=this?!1:(s.target.removeEventListener(s.type,o),shpsAjax.ldAnim.chkLding(),void 0)}),t.style.removeProperty("top"),t.style.removeProperty("perspective-origin"),t.classList.remove("shps-logo-smaller");for(var s=e.length-1,n=function i(s){this.removeEventListener(s.type,i),this.classList.remove("shps-logo-loading-end-trans"),this.style.removeProperty("position"),this.style.removeProperty("left")},a=s;a>=0;a--){var p=e[a];p.addEventListener("transitionend",n),p.style.left=25*a+"%",mdf_cls(p,"remove","shps-logo-3d-"+a),p.offsetHeight}mdf_cls(r,"remove","shps-logo-constant-rotate"),r.style.animationDuration="1s";break;case"onload":shpsAjax.ldAnim.setEvtLsnr("end",shpsAjax.ldAnim.entry)}},e[e.length-1].addEventListener("transitionend",function a(s){s.target.removeEventListener(s.type,a),shpsAjax.ldAnim.chkLding()});var n=0;s()};var p=shpsAjax.psdMenu.elm=document.getElementById(shpsAjax.psdMenu.id);p.lastW=0,optElmRszLsnr.add(p,function(){p.offsetWidth!=p.lastW&&(p.lastW=p.offsetWidth,shpsAjax.psdMenu.fixedFcts.forEach(function(s){s(p.lastW)}))});var o=shpsAjax.pgMgr.operator.scrlCnr=document.getElementById("psd-ctt-scrl-cnr"),i=shpsAjax.pgMgr.operator.cttCnr=document.getElementById(shpsAjax.pgMgr.cnr_id);shpsAjax.pgMgr.operator.hvScrl=function(){},o.lastElm,o.lastIdx,shpsCmm.scrlSnap.add(o,function(){return{v:i.children.length}},function(s){var n=s.v,r=i.children[n];(r!=o.lastElm||o.lastIdx!=n)&&(n!=o.lastIdx&&(shpsAjax.pgMgr.operator.hvScrl(n-o.lastIdx),o.lastIdx=n),o.lastElm=r,shpsAjax.pgMgr.operator.move3d(r))}),shpsAjax.hashChange=function(){var s=window.location.hash,n=/^#!([^#]+.*)/,r=n.exec(s);if(null!==r)if(r=r[1],shpsAjax.lnkMgr.beInformed(r),n=/^([^#]+)(#.*)/,s=n.exec(r),null!==s&&(r=s[1],s=s[2]),r!=shpsAjax.pgMgr.crtUrl||r!=shpsAjax.pgMgr.rqsUrl)shpsAjax.pgMgr.rqs(r,s);else{var e=shpsAjax.pgMgr;e.operator.closeHv();var t=e.cache[r];t.obj.hashChange&&t.obj.hashChange(s),t.elm.hash=s}}}),shpsCmm.wdwLded().then(function(){var s=shpsAjax.logo_rszTxt;s(),optRszLsnr.add(s),window.location.hash.indexOf("#!")>=0?shpsAjax.hashChange():shpsAjax.pgMgr.rqs("homepage/"),window.addEventListener("hashchange",shpsAjax.hashChange,!1);var n=shpsCmm.iCMgr,r=shpsCmm.lnkExtFile;n.getBlobUrl("/csss/history_sys.css","link").then(function(s){r.lnked("link",s)});var e=[{name:"historyView"},{name:"betaSign",required:!0}];shpsAjax.cpnMgr.rqs(e,void 0,!0);var t=shpsCmm.moduleMgr;t.get("/shared/modules/hex_grid/").then(function(s){r.lnked("link","/csss/psd_menu_hg.css").then(function(){var n=new s.obj.hexGrid(document.getElementById(shpsAjax.psdMenu.id).getElementsByClassName("hex-grid")[0]);shpsAjax.psdMenu.enableHg=n.enable.bind(n),shpsAjax.psdMenu.disableHg=n.disable.bind(n)})}),t.get("/shared/modules/hex_frame/").then(function(s){r.lnked("link","/csss/psd_menu_hf.css")}),n.getBlobUrl("/shared/jss/gtm.js","script").then(function(s){r.lnked("script",s)})});