!function(){const t="portfolio",s="/shared/modules/lv2/featured/",e="/shared/modules/lv2/common/",o="ptfl-bd",l="lv2-cmm-ttl",n="shpsptfl-hp",a="basic-bg",c="bg_inttile";var d=shpsCmm.moduleMgr,m=new shpsCmm.elmOnldr.ttlElmOnldr;m.addElm(o),m.addBuCss("/portfolio/templates/shps_ptfl/csss/ftr.css"),m.addBuCss("/portfolio/templates/shps_ptfl/csss/ptfl.css"),cnrLdedP=Promise.all([m,d.get(e,!0)]),shpsAjax.hooks[t]=function(t){shpsAjax.pgMgr.cache[t].obj={};var e=shpsAjax.pgMgr.cache[t].obj;e.lded=!1;var a,c,m,p;e.onload=function(t){cnrLdedP.then(function(){c=document.getElementById(o),m=document.getElementById(n),t(),a=d.get(s)})},e.intro=function(t){c.classList.remove("dsp-non"),c.offsetHeight,c.classList.remove("opa-0"),a.then(function(t){p=new t.obj.lv2Ftd(m,"/portfolio/?option=com_content&view=featured&format=ajax","calc(100vh - "+c.getElementsByClassName(l)[0].offsetHeight+"px)")})},e.exit=function(t){}}}();