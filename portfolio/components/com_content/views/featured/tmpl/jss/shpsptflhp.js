!function(){const t="portfolio",e="/shared/modules/lv2/featured/",n="/shared/modules/lv2/common/",o="ptfl-bd",s="lv2-cmm-ttl",l="shpsptfl-hp",r="basic-bg",d="bg_inttile";var a=shpsCmm.moduleMgr,c=new shpsCmm.elmOnldr.ttlElmOnldr;c.addElm(o),c.addBuCss("/portfolio/templates/shps_ptfl/csss/ftr.css"),c.addBuCss("/portfolio/templates/shps_ptfl/csss/ptfl.css"),cnrLdedP=c.lded(),shpsAjax.hooks[t]=function(t){var c=shpsAjax.pgMgr.cache[t].obj={};c.lded=!1;var i,m,p,f,h=shpsAjax.lnkMgr;c.onload=function(t){cnrLdedP.then(function(){i=document.getElementById(o),m=document.getElementById(l),t()})},c.intro=function(t){i.classList.remove("dsp-non"),i.addEventListener("transitionend",function o(t){return t.target!=this?!1:(this.removeEventListener(t.type,o),shpsAjax.cpnMgr.operator.open(d,{cnr:document.getElementById(r)}),void 0)}),requestAnimationFrame(function(){i.classList.remove("opa-0"),a.get(n,!0).then(function(t){f=t.obj.lv2Cmm}),a.get(e,!0).then(function(t){return p=new t.obj.lv2Ftd(m,"/portfolio/?option=com_content&view=featured&format=ajax","calc(100vh - "+i.getElementsByClassName(s)[0].offsetHeight+"px)"),p.lded()}).then(function(){forEachNodeItem(m.getElementsByTagName("a"),function(t){h.register(t)}),p.introBlks(),t&&t.cb&&"end"==t.cbTiming&&t.cb()})})},c.exit=function(t){},c.hashChange=function(t){var e=/^#([^#]+.*)/;t=e.exec(t),null!==t&&(t=t[1],f.morphFtdBlk(m))}}}();