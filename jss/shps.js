"use strict";function shpsAjax(){}shpsAjax.dependenciesP=shpsCmm.moduleMgr.get("/shared/modules/dependencies/",!0)["catch"](function(s){console.log(s)}),shpsAjax.dependenciesLded=function(){return this.dependenciesP},shpsAjax.siteTtl="SHPS",shpsAjax.load=!1,shpsAjax.debug={},shpsAjax.debug.checkUrl=function(s){return"/debug/view_test.php"==window.location.pathname?"/"+s:s},shpsAjax.rqsMgr={},shpsAjax.rqsMgr.ongoing=[],shpsAjax.rqsMgr.pending=[],shpsAjax.rqsMgr.abort=function(){shpsAjax.rqsMgr.ongoing.forEach(function(s){s.qable&&-1==shpsAjax.rqsMgr.pending.indexOf(s)&&shpsAjax.rqsMgr.pending.push(s),s.ajax.abort()}),shpsAjax.rqsMgr.ongoing=[]},shpsAjax.rqsMgr.removeOngoing=function(s){var n=shpsAjax.rqsMgr.ongoing.indexOf(s);-1!=n&&shpsAjax.rqsMgr.ongoing.splice(n,1)},shpsAjax.rqsMgr.removePending=function(s){var n=shpsAjax.rqsMgr.pending.indexOf(s);-1!=n&&shpsAjax.rqsMgr.pending.splice(n,1)},shpsAjax.rqsMgr.load=function(s,n){var e=this;this.pending.forEach(function(n){n.url==s.url&&e.removePending(n)}),this.ongoing.forEach(function(n){return n.url==s.url?!1:void 0}),s.overwrite&&this.abort(),this.ongoing.push(s),shpsCmm.createAjax("POST",shpsAjax.debug.checkUrl(s.url),shpsCmm.getRefererParam(),s.type,s.hdrs,s).then(function(r){e.removeOngoing(s),n(r),e.attemptPending()})},shpsAjax.rqsMgr.attemptPending=function(){shpsAjax.rqsMgr.pending.length>0&&0===shpsAjax.rqsMgr.ongoing.length&&shpsAjax.rqsMgr.load(shpsAjax.rqsMgr.pending[0])},shpsAjax.rscMgr={},shpsAjax.rscMgr.rscs={},shpsAjax.rscMgr.inform=function(s,n){shpsAjax.dependenciesLded().then(function(){switch(s){case"pg":shpsAjax.pgMgr.beInformedRsc(n,shpsAjax.rscMgr.rscs[n]);break;case"cpnLdr":shpsAjax.cpnMgr.ldr.beInformedRsc(n,shpsAjax.rscMgr.rscs[n])}})},shpsAjax.rscMgr.onLd=function(s,n,e){this.rscs[s]=e,this.inform(n,s)},shpsAjax.rscMgr.rqs=function(s,n){if(this.rscs[s.url])this.inform(n,s.url);else{var e=shpsCmm.idbCache,r=shpsCmm.iCMgr,t=this;e.get(s.url).then(function(a){if(t.onLd(s.url,n,a.rscList),a.dlDate+1e3*a.maxAge<Date.now()){var p=[{hdr:"If-Modified-Since",value:a.lastModified}];a.eTag&&p.push({hdr:"If-None-Match",value:a.eTag}),s.type="json",shpsCmm.createAjax("GET",shpsAjax.debug.checkUrl(s.url),void 0,s.type,p).then(function(n){200==n.status&&shpsAjax.rqsMgr.load(s,function(n){var t=r.createRecordObj(n);t&&(t.rscList=n.response,t.url=s.url,e.set(t))})})}},function(){s.type="json",shpsAjax.rqsMgr.load(s,function(a){var p=r.createRecordObj(a);p&&(t.onLd(s.url,n,a.response),p.rscList=a.response,p.url=s.url,e.set(p))})})}},shpsAjax.psdBd_id="psd-bd-cnr",shpsAjax.hooks={},shpsAjax.cpnList={historyView:{url:"shared/shps_ajax/hv/",cnr_id:"psd-menu-scrl-cnr",lded:!1},betaSign:{url:"shared/cpns/beta_sign/",cnr_id:"the-shps-ajax-bd",lded:!1},bg_basic:{url:"shared/bgs/basic/",cnr_id:"psd-bg-cnr",lded:!1},bg_tdcubes:{url:"shared/bgs/tdcubes/",cnr_id:"psd-bg-cnr",lded:!1},bg_inttile:{url:"shared/bgs/inttile/",cnr_id:"psd-bg-cnr",lded:!1},bg_tlight:{url:"shared/bgs/tlight/",cnr_id:"psd-bg-cnr",lded:!1},menu_basic:{url:"",cnr_id:"psd-menu-cnr",lded:!1}},shpsAjax.cpnMgr={},shpsAjax.cpnMgr.ldr={},shpsAjax.cpnMgr.ldr.rqss=[],shpsAjax.cpnMgr.ldr.rqd=[],shpsAjax.cpnMgr.ldr.nonRqd=[],shpsAjax.cpnMgr.ldr.chkAll=function(){var s=shpsAjax.cpnMgr.ldr.rqd,n=shpsAjax.cpnMgr.ldr.nonRqd;if(s.length>0||n.length>0){if(s.length>0){if(!shpsAjax.cpnMgr.ldr.rqd.every(function(s){return shpsAjax.cpnList[s.name].lded?!0:!1}))return!1;shpsAjax.cpnMgr.ldr.rqd=[]}shpsAjax.cpnMgr.beInformedLded(),n.length>0&&(shpsAjax.cpnMgr.ldr.internalLoad(shpsAjax.cpnMgr.ldr.nonRqd),shpsAjax.cpnMgr.ldr.nonRqd=[])}},shpsAjax.cpnMgr.ldr.chkLded=function(s){if(!shpsAjax.cpnList[s].lded){if(!shpsAjax.cpnList[s].obj.lded)return!1;if(shpsAjax.cpnList[s].cpnsNdd&&shpsAjax.cpnList[s].cpnsNdd.some(function(s){return shpsAjax.cpnList[s].lded?void 0:!0}))return!1;if(!shpsAjax.cpnList[s].modules.every(function(s){return s.lded}))return!1;if(shpsAjax.cpnList[s].cpnsUsed){var n=[];shpsAjax.cpnList[s].cpnsUsed.forEach(function(s){if(!shpsAjax.cpnList[s].lded){var e={name:s};n.push(e)}}),n.length>0&&shpsAjax.cpnMgr.ldr.internalLoad(n)}shpsAjax.cpnList[s].lded=!0,shpsAjax.cpnMgr.operator.beInformedLded(s)}shpsAjax.cpnList[s].forCpns&&shpsAjax.cpnList[s].forCpns.forEach(function(s){shpsAjax.cpnMgr.ldr.chkLded(s)}),shpsAjax.cpnMgr.ldr.chkAll()},shpsAjax.cpnMgr.ldr.appendHtml=function(s,n){function e(){shpsAjax.cpnMgr.ldr.appendHtml=function(s,n){var e=document.getElementById(shpsAjax.cpnList[n].cnr_id),r=document.createElement("div");r.innerHTML=s,forEachNodeItem(r.children,function(s){e.appendChild(s)})},shpsAjax.cpnMgr.ldr.appendHtml(s,n)}document.getElementById(shpsAjax.psdBd_id)?e():window.addEventListener("load",e,!1)},shpsAjax.cpnMgr.ldr.installItem=function(s,n){var e=shpsAjax.cpnList[n],r=shpsCmm.lnkExtFile,t=this;switch(s.type){case"link":r.lnked("link",s.objUrl);break;case"script":r.lnked("script",s.objUrl,s.async).then(function(){e.obj||shpsAjax.hooks[e.hookName]&&(shpsAjax.hooks[e.hookName](n),e.obj.onload(function(){e.obj.lded=!0,t.chkLded(n)}))});break;case"cpnList":e.cpnsNdd=[],e.cpnsUsed=[],e.closeCpns=[],e.nonCloseCpns=[];var a=[],p=shpsAjax.cpnList;s.cpns.forEach(function(s){s.required?(p[s.name].forCpns||(p[s.name].forCpns=[]),p[s.name].forCpns.push(n),e.cpnsNdd.push(s.name),shpsAjax.cpnList[s.name].lded||a.push(s)):e.cpnsUsed.push(s.name),s.close?e.closeCpns.push(s.name):e.nonCloseCpns.push(s.name)}),a.length>0?t.internalLoad(a):t.chkLded(n);break;case"html":t.appendHtml(s.html,n);break;case"module":e.modules.push(s),s.lded=!1,shpsCmm.moduleMgr.get(s.url,!0).then(function(){s.lded=!0,t.chkLded(n)})}},shpsAjax.cpnMgr.ldr.beInformedRsc=function(s,n){var e=void 0,r=void 0;if(this.rqss.some(function(n){return shpsAjax.cpnList[n.name].url==s?(e=n.name,r=shpsAjax.cpnList[n.name],!0):void 0})){if(r.hasRsc)return!1;r.hasRsc=!0,r.hookName=n.hook,r.modules=[];var t=shpsCmm.iCMgr,a=this,p=[];n.rscs.forEach(function(s){var n=[];s.forEach(function(s){var e=void 0;switch(s.type){case"html":case"cpnList":case"module":e=Promise.resolve(s);break;default:e=t.getData(s.url,"blob","blob").then(function(){return s})}n.push(e)}),p.push(Promise.all(n))}),p.reduce(function(s,n){return s.then(function(){return n}).then(function(s){return s.forEach(function(s){switch(s.type){case"link":case"script":case"img":case"svg":t.getObjUrl(s.url,s.type).then(function(n){s.objUrl=n.objUrl,a.installItem(s,e)});break;default:a.installItem(s,e)}}),Promise.resolve()})},Promise.resolve())}},shpsAjax.cpnMgr.ldr.internalLoad=function(s){s.forEach(function(s){var n=s.name;if(!shpsAjax.cpnList[n].lded){var e={};e.url=shpsAjax.cpnList[n].url,shpsAjax.rscMgr.rqs(e,"cpnLdr")}})},shpsAjax.cpnMgr.ldr.load=function(s){shpsAjax.cpnMgr.ldr.rqss=s.slice(),shpsAjax.cpnMgr.ldr.rqd=[],shpsAjax.cpnMgr.ldr.nonRqd=[],s.forEach(function(s){shpsAjax.cpnList[s.name].lded||(s.required?shpsAjax.cpnMgr.ldr.rqd.push(s):shpsAjax.cpnMgr.ldr.nonRqd.push(s))}),shpsAjax.cpnMgr.ldr.rqd.length>0?shpsAjax.cpnMgr.ldr.internalLoad(shpsAjax.cpnMgr.ldr.rqd):shpsAjax.cpnMgr.ldr.chkAll()},shpsAjax.cpnMgr.operator={},shpsAjax.cpnMgr.operator.rqsList=[],shpsAjax.cpnMgr.operator.listOpened=[],shpsAjax.cpnMgr.operator.rqsArgs=[],shpsAjax.cpnMgr.operator.addRa=function(s,n){var e=this.rqsArgs;n&&(e[s]||(e[s]=[]),-1==e[s].indexOf(n)&&e[s].push(n))},shpsAjax.cpnMgr.operator.open=function(s,n){if(-1==this.rqsList.indexOf(s)&&this.rqsList.push(s),this.addRa(s,n),shpsAjax.cpnList[s].lded){var e=this.rqsArgs;if(!e[s]&&-1!=this.listOpened.indexOf(s))return!0;e[s]?(-1==this.listOpened.indexOf(s)&&this.listOpened.push(s),e[s].forEach(function(n){shpsAjax.cpnList[s].obj.intro(n)}),delete e[s]):(this.listOpened.push(s),shpsAjax.cpnList[s].obj.intro())}},shpsAjax.cpnMgr.operator.beInformedLded=function(s){-1==shpsAjax.cpnMgr.operator.listOpened.indexOf(s)&&-1!=shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.open(s)},shpsAjax.cpnMgr.operator.rmvSubCpns=function(s){s.forEach(function(s){shpsAjax.cpnList[s].closeCpns&&shpsAjax.cpnList[s].closeCpns.forEach(function(s){var n=shpsAjax.cpnMgr.operator.rqsList.indexOf(s);-1!=n&&shpsAjax.cpnMgr.operator.rqsList.splice(n,1)})})},shpsAjax.cpnMgr.operator.mdfList=function(s){return s.forEach(function(s){shpsAjax.cpnList[s].nonCloseCpns&&shpsAjax.cpnList[s].nonCloseCpns.forEach(function(s){-1==shpsAjax.cpnMgr.operator.rqsList.indexOf(s)&&shpsAjax.cpnMgr.operator.rqsList.push(s)})}),shpsAjax.cpnMgr.operator.rqsList!=s?(shpsAjax.cpnMgr.operator.mdfList(shpsAjax.cpnMgr.operator.rqsList),void 0):(shpsAjax.cpnMgr.operator.rmvSubCpns(s),void 0)},shpsAjax.cpnMgr.operator.openOnlyThese=function(s){var n=this;this.rqsList=s.slice(),this.mdfList(s),this.listOpened.forEach(function(s){var e=n.rqsList.indexOf(s);console.log(s,e),-1!=e?n.rqsList.splice(e,1):(n.listOpened.splice(n.listOpened.indexOf(s),1),shpsAjax.cpnList[s].obj.exit())}),this.rqsList.forEach(function(s){shpsAjax.cpnList[s].lded&&n.open(s)})},shpsAjax.cpnMgr.busy=!1,shpsAjax.cpnMgr.rqss=[],shpsAjax.cpnMgr.q=[],shpsAjax.cpnMgr.src=void 0,shpsAjax.cpnMgr.beInformedLded=function(){if(this.busy=!1,"pg"==this.src){var s=[];this.rqss.forEach(function(n){n.close||s.push(n.name)}),this.operator.openOnlyThese(s),shpsAjax.pgMgr.beInformedCpn()}else{if(!this.q[0].every(function(s){return shpsAjax.cpnList[s.name].lded?!0:!1}))return!1;this.q.shift()}this.q.length>0&&this.rqs(shpsAjax.cpnMgr.q[0],void 0,!0)},shpsAjax.cpnMgr.rqs=function(s,n,e){var r=this;if("pg"==n)this.src=n,this.rqss=s.slice();else{if(e&&-1==this.q.indexOf(s)&&this.q.push(s),this.busy)return!1;this.src=void 0,s.forEach(function(s){-1==r.rqss.indexOf(s)&&r.rqss.push(s)})}this.busy=!0;var t=[];this.rqss.forEach(function(s){shpsAjax.cpnList[s.name].lded||t.push(s)}),t.length>0?shpsAjax.cpnMgr.ldr.load(t):shpsAjax.cpnMgr.beInformedLded()},shpsAjax.psdMenu={},shpsAjax.psdMenu.noExp=!1,shpsAjax.psdMenu.id="psd-menu-cnr",shpsAjax.psdMenu.disableHg=function(){},shpsAjax.psdMenu.enableHg=function(){},shpsAjax.psdMenu.noExpand=function(){this.noExp=!0,this.disableHg();var s=shpsAjax.psdMenu.elm;new shpsCmm.animFramePromise(function(){s.style.maxWidth=s.offsetWidth+"px"}).then(function(){s.classList.add("psd-menu-no-expand")})},shpsAjax.psdMenu.doExpand=function(){this.noExp=!1;var s=shpsAjax.psdMenu.elm;s.style.removeProperty("max-width"),mdf_cls(s,"remove","psd-menu-no-expand"),this.enableHg()},shpsAjax.psdMenu.fixedFcts=[],shpsAjax.psdMenu.regFixed=function(s){shpsAjax.psdMenu.fixedFcts.push(s)},shpsAjax.pgMgr={},shpsAjax.pgMgr.crtUrl="null",shpsAjax.pgMgr.rqsUrl="null",shpsAjax.pgMgr.rqsHash=null,shpsAjax.pgMgr.cache={},shpsAjax.pgMgr.cache["null"]={},shpsAjax.pgMgr.cache["null"].lded=!0,shpsAjax.pgMgr.cache["null"].obj={},shpsAjax.pgMgr.cache["null"].obj.intro=function(){},shpsAjax.pgMgr.cnr_id="psd-ctt-cnr",shpsAjax.pgMgr.operator={},shpsAjax.pgMgr.operator.getPgIdx=function(s){var n;return forEachNodeItem(shpsAjax.pgMgr.operator.cttCnr.children,function(e,r){e==s&&(n=r)}),n},shpsAjax.pgMgr.operator.getElms_bfr=function(s){var n=shpsAjax.pgMgr.operator.getPgIdx(s),e=this.cttCnr.children;if(n>0){for(var r=[],t=0;n>t;t++)r.push(e[t]);return r}return!1},shpsAjax.pgMgr.operator.getElms_afr=function(s){var n=shpsAjax.pgMgr.operator.getPgIdx(s),e=this.cttCnr.children;if(n<e.length-1){for(var r=[],t=n+1;t<e.length;t++)r.push(e[t]);return r}return!1},shpsAjax.pgMgr.operator.global3dOffset=120,shpsAjax.pgMgr.operator.single3dOffset=20,shpsAjax.pgMgr.operator.sdw3dSize=12,shpsAjax.pgMgr.operator.sdwOffset=14,shpsAjax.pgMgr.operator.hv3d_bfr=function(s){},shpsAjax.pgMgr.operator.move3d_bfr=function(s){var n=shpsAjax.pgMgr.operator.getElms_bfr(s),e=n.length-1;if(n!==!1){var r=parseFloat(getStyle(document.documentElement,"font-size"))*shpsAjax.pgMgr.operator.sdw3dSize,t=r/s.offsetHeight*100+100+shpsAjax.pgMgr.operator.sdwOffset,a=(t-this.global3dOffset/2)/t*90,p=this;n.forEach(function(s){s.style.transformOrigin="top",s.style.transform="translatey(-"+(p.global3dOffset+p.single3dOffset*e)+"%) rotatex(90deg) translatey(-"+p.global3dOffset+"%) rotatex(-90deg) rotatex(-"+a+"deg)",e--,p.hv3d_bfr(s)})}},shpsAjax.pgMgr.operator.hv3d_afr=function(s){},shpsAjax.pgMgr.operator.move3d_afr=function(s){var n=this.getElms_afr(s),e=0;if(n!==!1){var r=parseFloat(getStyle(document.documentElement,"font-size"))*this.sdw3dSize,t=r/s.offsetHeight*100+100+this.sdwOffset,a=(t-this.global3dOffset/2)/t*90,p=this;n.forEach(function(s){s.style.transformOrigin="bottom",s.style.transform="translatey("+(p.global3dOffset+p.single3dOffset*e)+"%) rotatex(90deg) translatey(-"+p.global3dOffset+"%) rotatex(-90deg) rotatex("+a+"deg)",e++,p.hv3d_afr(s)})}},shpsAjax.pgMgr.operator.hv3d_rmv=function(s){},shpsAjax.pgMgr.operator.remove3d=function(s){s.style.removeProperty("transform"),this.hv3d_rmv(s)},shpsAjax.pgMgr.operator.move3d=function(s){this.remove3d(s),this.move3d_bfr(s),this.move3d_afr(s)},shpsAjax.pgMgr.operator.closeHv=function(){this.scrlCnr.classList.remove("psd-vp-cnr-scrl")},shpsAjax.pgMgr.operator.initHvElm3d=function(){},shpsAjax.pgMgr.operator.swap=function(s){function n(){var n={};if(t[s].obj.hashChange){var e=shpsAjax.pgMgr.rqsHash;t[s].elm.hash=e,n.cb=function(){t[s].obj.hashChange(e)},n.cbTiming="end"}t[s].obj.introed?n.cb&&n.cb():(t[s].obj.intro(n),t[s].obj.introed=!0);var r=shpsAjax.psdMenu;t[s].settings&&t[s].settings.psdMenuNoExp?r.noExp||r.noExpand():r.noExp&&r.doExpand()}var e=shpsAjax.pgMgr.crtUrl,r=shpsAjax.pgMgr.crtUrl=s,t=shpsAjax.pgMgr.cache,a=t[s].elm,p=this.getElms_afr(a);if(p!==!1){var i=document.getElementById(shpsAjax.pgMgr.cnr_id);p.forEach(function(s){i.insertBefore(s,a)})}var o=this.scrlCnr;o.classList.add("psd-vp-cnr-scrl");var h=this,d=shpsCmm.scrlSnap;d.add(o,function(){return{v:1}},function l(){d.rmv(o,l),t[e].obj.swapOut&&t[e].obj.swapOut(),h.closeHv()}),o.scrollTop+o.clientHeight<o.scrollHeight-1?o.scrollTop=o.scrollHeight-o.clientHeight:d.forceFireHdlrs(o),t[r].obj.swapIn&&t[r].obj.swapIn(),"null"==e?n():setTimeout(n,1e3)},shpsAjax.pgMgr.chkLded=function(){var s=shpsAjax.pgMgr.rqsUrl,n=shpsAjax.pgMgr.cache;if(!n[s].lded){if(!n[s].obj||!n[s].obj.lded)return!1;if(n[s].cpnsNdd&&!n[s].nddLded)return!1;if(!n[s].modules.every(function(s){return s.lded}))return!1;n[s].lded=!0}if(s!=shpsAjax.pgMgr.crtUrl){var e=function r(){shpsAjax.pgMgr.operator.swap(s)};shpsAjax.load?e():(shpsAjax.ldAnim.setEvtLsnr("exit",e),shpsAjax.load=!0)}},shpsAjax.pgMgr.beInformedCpn=function(){var s=shpsAjax.pgMgr.rqsUrl,n=shpsAjax.pgMgr.cache;if(n[s].cpnsNdd){if(n[s].cpnsNdd.some(function(s){return shpsAjax.cpnList[s].lded?void 0:!0}))return!1;n[s].nddLded=!0}shpsAjax.pgMgr.chkLded()},shpsAjax.pgMgr.appendHtml=function(s,n){var e=this;shpsCmm.elmOnldr.elmLded(shpsAjax.psdBd_id).then(function(){var r=document.getElementById(e.cnr_id),t=document.createElement("section");t.classList.add("shps-ajax-vp"),t.innerHTML=n;var a=e.cache[s];a.elm=t,a.elm.hash=e.rqsHash,t.ttl=a.ttl,t.url=s,e.operator.initHvElm3d(t),r.appendChild(t);var p=document.createElement("div");p.classList.add("psd-vp-pg-ph"),document.getElementById("psd-ctt-scrl-cnr").appendChild(p)})},shpsAjax.pgMgr.installItem=function(s,n){var e=this.cache[n],r=shpsCmm.lnkExtFile,t=this;switch(s.type){case"link":r.lnked("link",s.objUrl);break;case"cpnList":e.cpns=s.cpns,e.cpnsNdd=[],e.cpnsUsed=[],s.cpns.forEach(function(s){s.required?e.cpnsNdd.push(s.name):e.cpnsUsed.push(s.name)}),shpsAjax.cpnMgr.rqs(e.cpns,"pg");break;case"html":this.appendHtml(n,s.html);break;case"script":r.lnked("script",s.objUrl,s.async).then(function(){e.obj||shpsAjax.hooks[e.hookName]&&(shpsAjax.hooks[e.hookName](n),e.obj.onload(function(){e.obj.lded=!0,t.chkLded()}))});break;case"module":e.modules.push(s),s.lded=!1,shpsCmm.moduleMgr.get(s.url,!0).then(function(){s.lded=!0,t.chkLded()})}},shpsAjax.pgMgr.beInformedRsc=function(s,n){if(s==this.rqsUrl){var e=this.cache;if(!e[s]){var r=e[s]={};r.hookName=n.hook,r.ttl=n.ttl,n.settings&&(r.settings=n.settings),r.cpns=[],r.modules=[];var t=shpsCmm.iCMgr,a=this,p=[];n.rscs.forEach(function(s){var n=[];s.forEach(function(s){var e=void 0;switch(s.type){case"html":case"cpnList":case"module":e=Promise.resolve(s);break;default:e=t.getData(s.url,"blob","blob").then(function(){return s})}n.push(e)});var e=Promise.all(n);p.push(e)}),p.reduce(function(n,e){return n.then(function(){return e}).then(function(n){return n.forEach(function(n){switch(n.type){case"link":case"script":case"img":case"svg":t.getObjUrl(n.url,n.type).then(function(e){n.objUrl=e.objUrl,a.installItem(n,s)});break;default:a.installItem(n,s)}}),Promise.resolve()})},Promise.resolve()).then(function(){r.cpns.length<=0&&shpsAjax.cpnMgr.rqs(r.cpns,"pg")})}}},shpsAjax.pgMgr.rqs=function(s,n){if(this.rqsHash=n,s==shpsAjax.pgMgr.rqsUrl)return!1;if(this.rqsUrl=s,s==shpsAjax.pgMgr.crtUrl)return!1;var e=shpsAjax.pgMgr.cache;if(!e[s])return shpsAjax.load=!1,shpsAjax.ldAnim.entry(),shpsAjax.rscMgr.rqs({url:s,overwrite:!0},"pg"),!1;if(!e[s].lded)return shpsAjax.load=!1,shpsAjax.ldAnim.entry(),e[s].cpnsNdd&&(e[s].nddLded||shpsAjax.cpnMgr.rqs(e[s].cpns,"pg")),!1;if(e[s].cpnsNdd&&e[s].cpnsNdd.some(function(s){var n=shpsAjax.cpnMgr.operator.listOpened.indexOf(s);return-1==n?!0:void 0})){var r={instant:!0};e[s].obj.exit(r),e[s].obj.introed=!1}shpsAjax.cpnMgr.rqs(e[s].cpns,"pg")},shpsAjax.hashChange=function(){},shpsAjax.lnkMgr={},shpsAjax.lnkMgr.registry={},shpsAjax.lnkMgr.lastUrl="",shpsAjax.lnkMgr.disableElm=function(s){s.removeAttribute("href"),s.title&&(s.lnkMgr.regdTtl=s.title,s.removeAttribute("title"))},shpsAjax.lnkMgr.enableElm=function(s){var n=s.lnkMgr;s.href||(s.href="#!"+n.regdUrl);var e=n.regdTtl;e&&!s.title&&(s.title=e)},shpsAjax.lnkMgr.rmvFromArr=function(s,n){var e=this.registry[n],r=e.indexOf(s);-1!=r&&e.splice(r,1),e.length<=0&&delete this.registry[n]},shpsAjax.lnkMgr.register=function(s){var n=s.hash.replace("#!","");if(s.lnkMgr){var e=s.lnkMgr.regdUrl;if(e==n)return!0;this.rmvFromArr(s,e)}else s.lnkMgr={};s.lnkMgr.regdUrl=n,this.registry[n]||(this.registry[n]=[]),this.registry[n].push(s),this.lastUrl==n&&this.disableElm(s)},shpsAjax.lnkMgr.deregister=function(s){var n=s.lnkMgr;n&&(this.enableElm(s),this.rmvFromArr(s,n.regdUrl),s.lnkMgr=void 0)},shpsAjax.lnkMgr.beInformed=function(s){var n=this;this.registry[s]&&this.registry[s].forEach(this.disableElm),this.registry[this.lastUrl]&&this.registry[this.lastUrl].forEach(function(s){n.enableElm(s)}),this.lastUrl=s},shpsAjax.logo_rszTxt=function(){},shpsCmm.domReady().then(function(){var s="shps-logo",n=void 0,e=document.getElementById(s),r=e.getElementsByTagName("span"),t=e.parentElement;shpsAjax.logo_rszTxt=function(){n=r[0].offsetHeight,e.style.lineHeight=n+"px",e.style.fontSize=n+"px",t.classList.contains("shps-logo-smaller")&&(t.style.perspectiveOrigin="50% "+1.5*n+"px")},shpsAjax.ldAnim={},shpsAjax.ldAnim.status="end",shpsAjax.ldAnim.cbs={},shpsAjax.ldAnim.cbs.end=[],shpsAjax.ldAnim.cbs.load=[],shpsAjax.ldAnim.cbs.exit=[],shpsAjax.ldAnim.evtVal=function(s){switch(s){case"end":case"load":case"exit":return!0;default:return!1}},shpsAjax.ldAnim.tgrEvtLsnr=function(s){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s].length>0&&(shpsAjax.ldAnim.cbs[s].forEach(function(s){s()}),shpsAjax.ldAnim.cbs[s]=[]),void 0):!1},shpsAjax.ldAnim.addEvtLsnr=function(s,n){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s].push(n),void 0):!1},shpsAjax.ldAnim.rmvEvtLsnr=function(s,n){if(!shpsAjax.ldAnim.evtVal(s))return!1;var e=shpsAjax.ldAnim.cbs[s].indexOf(n);-1!=e&&shpsAjax.ldAnim.cbs[s].splice(e,1)},shpsAjax.ldAnim.clrEvtLsnr=function(s){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.cbs[s]=[],void 0):!1},shpsAjax.ldAnim.setEvtLsnr=function(s,n){return shpsAjax.ldAnim.evtVal(s)?(shpsAjax.ldAnim.clrEvtLsnr(s),shpsAjax.ldAnim.addEvtLsnr(s,n),void 0):!1};var a=function h(){a=function n(){};var s=t.getElementsByTagName("a")[0];s.href="#!homepage/",s.title="Go to the homepage.",shpsAjax.lnkMgr.register(s)};shpsAjax.ldAnim.onload=function(){shpsAjax.ldAnim.status="onload",shpsAjax.ldAnim.tgrEvtLsnr("load"),setTimeout(function(){for(var s=function i(s){var n=r[s];new shpsCmm.animFramePromise(function(){n.classList.remove("shps-logo-loading-in-trans","shps-logo-entry-trans"),n.style.left=n.offsetLeft+"px",n.style.position="absolute"}).then(function(){n.classList.add("shps-logo-loading-end-trans"),n.classList.add("shps-logo-3d-"+s),n.style.left="50%"})},p=r.length-1;p>=0;p--)s(p);e.style.removeProperty("animation-duration"),e.classList.add("shps-logo-constant-rotate","shps-logo-rotate"),t.addEventListener("transitionend",function o(s){return s.target!=this?!1:(s.target.removeEventListener(s.type,o),setTimeout(function(){t.style.top="1vmin",t.addEventListener("transitionend",function s(n){n.target.removeEventListener(n.type,s),a(),shpsAjax.ldAnim.status="end",shpsAjax.ldAnim.tgrEvtLsnr("end")},!1),t.classList.add("shps-logo-smaller"),t.classList.remove("shps-logo-larger"),shpsAjax.logo_rszTxt(),shpsAjax.ldAnim.tgrEvtLsnr("exit")},1e3),void 0)},!1),t.style.top=t.offsetTop-n+"px",t.classList.add("shps-logo-larger")},500)},shpsAjax.ldAnim.ldg=function(){function s(){var t=r[e];t.addEventListener("transitionend",function a(s){s.target.removeEventListener(s.type,a),t.classList.remove("shps-logo-entry-trans","shps-logo-loading-out"),t.classList.add("shps-logo-loading-in-init"),t===r[0]&&t.addEventListener("transitionend",function e(s){s.target.removeEventListener(s.type,e),shpsAjax.load?shpsAjax.ldAnim.onload():n()},!1),setTimeout(function(){mdf_cls(t,"add","shps-logo-loading-in-trans"),mdf_cls(t,"remove","shps-logo-loading-in-init")},1e3)},!1),t.classList.remove("shps-logo-loading-in-trans"),t.classList.add("shps-logo-entry-trans","shps-logo-loading-out"),e--,e>=0&&setTimeout(s,100)}function n(){setTimeout(function(){e=r.length-1,s()},1e3)}shpsAjax.ldAnim.status="ldg";var e=void 0;n()},shpsAjax.ldAnim.chkLding=function(){shpsAjax.load?shpsAjax.ldAnim.onload():shpsAjax.ldAnim.ldg()},shpsAjax.ldAnim.entry=function(){function s(){mdf_cls(r[n],"remove","opa-0"),mdf_cls(r[n],"remove","shps-logo-letter-init-size"),n++,n<r.length&&setTimeout(s,200)}shpsAjax.ldAnim.entry=function(){if(shpsAjax.load)return!0;switch(shpsAjax.ldAnim.status){case"end":var s=function i(s){this.removeEventListener(s.type,i),this.classList.remove("shps-logo-loading-end-trans"),this.style.removeProperty("position"),this.style.removeProperty("left")};shpsAjax.ldAnim.status="entry",t.addEventListener("transitionend",function o(s){return s.target!=this?!1:(s.target.removeEventListener(s.type,o),shpsAjax.ldAnim.chkLding(),void 0)}),t.style.removeProperty("top"),t.style.removeProperty("perspective-origin"),t.classList.remove("shps-logo-smaller");for(var n=r.length-1,a=n;a>=0;a--){var p=r[a];p.addEventListener("transitionend",s),p.style.left=25*a+"%",mdf_cls(p,"remove","shps-logo-3d-"+a)}mdf_cls(e,"remove","shps-logo-constant-rotate"),e.style.animationDuration="1s";break;case"onload":shpsAjax.ldAnim.setEvtLsnr("end",shpsAjax.ldAnim.entry)}},r[r.length-1].addEventListener("transitionend",function a(s){s.target.removeEventListener(s.type,a),shpsAjax.ldAnim.chkLding()});var n=0;s()};var p=shpsAjax.psdMenu.elm=document.getElementById(shpsAjax.psdMenu.id);p.lastW=0,shpsCmm.optElmRszLsnr.add(p,function(){p.offsetWidth!=p.lastW&&(p.lastW=p.offsetWidth,shpsAjax.psdMenu.fixedFcts.forEach(function(s){s(p.lastW)}))});var i=shpsAjax.pgMgr.operator.scrlCnr=document.getElementById("psd-ctt-scrl-cnr"),o=shpsAjax.pgMgr.operator.cttCnr=document.getElementById(shpsAjax.pgMgr.cnr_id);shpsAjax.pgMgr.operator.hvScrl=function(){},shpsCmm.scrlSnap.add(i,function(){return{v:o.children.length}},function(s){var n=s.v,e=o.children[n];!e||e==i.lastElm&&i.lastIdx==n||(n!=i.lastIdx&&(shpsAjax.pgMgr.operator.hvScrl(n-i.lastIdx),i.lastIdx=n),i.lastElm=e,shpsAjax.pgMgr.operator.move3d(e))}),shpsAjax.hashChange=function(){var s=window.location.hash,n=/^#!([^#]+.*)/,e=n.exec(s);if(null!==e)if(e=e[1],shpsAjax.lnkMgr.beInformed(e),n=/^([^#]+)(#.*)/,s=n.exec(e),null!==s&&(e=s[1],s=s[2]),e!=shpsAjax.pgMgr.crtUrl||e!=shpsAjax.pgMgr.rqsUrl)shpsAjax.pgMgr.rqs(e,s);else{var r=shpsAjax.pgMgr;r.operator.closeHv();var t=r.cache[e];t.obj.hashChange&&t.obj.hashChange(s),t.elm.hash=s}}}),shpsCmm.wdwLded().then(function(){var s=shpsAjax.logo_rszTxt;s(),optRszLsnr.add(s),window.location.hash.indexOf("#!")>=0?shpsAjax.hashChange():shpsAjax.pgMgr.rqs("homepage/"),window.addEventListener("hashchange",shpsAjax.hashChange,!1);var n=shpsCmm.iCMgr,e=shpsCmm.lnkExtFile,r=shpsCmm.moduleMgr;n.getBlobUrl("/csss/history_sys.css","link").then(function(s){e.lnked("link",s)}),shpsAjax.dependenciesLded().then(function(){var s=[{name:"historyView"},{name:"betaSign",required:!0}];shpsAjax.cpnMgr.rqs(s,void 0,!0),Promise.all([r.get("/shared/modules/hex_grid/"),e.lnked("link","/csss/psd_menu_hg.css")]).then(function(s){var n=new s[0].obj.hexGridFactory.createObj({numRows:25,numCols:35}),e=document.getElementById(shpsAjax.psdMenu.id);e.insertBefore(n.getElm(),e.children[0]),n.enable(),shpsAjax.psdMenu.enableHg=n.enable,shpsAjax.psdMenu.disableHg=n.disable}),r.get("/shared/modules/hex_frame/").then(function(s){e.lnked("link","/csss/psd_menu_hf.css")})}),n.getBlobUrl("/shared/jss/gtm.js","script").then(function(s){e.lnked("script",s)})});