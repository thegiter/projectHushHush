"use strict";!function(){var t="portfolio",e="Portfolio",n="#!portfolio/",o="/shared/modules/lv2/featured/",r="/shared/modules/lv2/common/",s="/shared/modules/lv2/ctt_ldr/",i="/portfolio/?option=com_content&view=featured&format=ajax",a="ptfl-bd",l="lv2-cmm-ttl",c="lv2-cmm-adp-full-h",d="basic-bg",m="bg_inttile",f=shpsCmm.moduleMgr,u=new shpsCmm.elmOnldr.ttlElmOnldr;u.addElm(a),u.addBuCss("/portfolio/templates/shps_ptfl/csss/ftr.css"),u.addBuCss("/portfolio/templates/shps_ptfl/csss/ptfl.css");var p=u.lded();shpsAjax.hooks[t]=function(t){function l(){}function u(){}var v=shpsAjax.pgMgr.cache[t].obj={};v.lded=!1;var h,g=void 0,b=void 0,j=void 0,B=shpsAjax.lnkMgr;v.onload=function(t){p.then(function(){h=document.getElementById(a);var c=null,d=h.children;d&&(c=d[0]);var m=void 0;f.get(r,!0).then(function(t){g=t.obj.lv2CmmFactory.createObj("TTL",{ttl:e,href:n});var o=g.getElm();B.register(o.getElementsByTagName("a")[0]),h.insertBefore(o,h.children[0])}),f.get(o,!0).then(function(t){b=t.obj.lv2FtdFactory.createObj({url:i,cached:!0});var e=b.getElm();m?h.insertBefore(e,m):h.insertBefore(e,c),l=b.play,u=b.stop}),f.get(s,!0).then(function(t){j=t.obj.lv2CttLdrFactory.createObj();var e=j.getElm();h.insertBefore(e,c),m=e}),t()})},v.intro=function(t){requestAnimationFrame(function(){h.classList.remove("dsp-non"),h.addEventListener("transitionend",function e(t){return t.target!=this?!1:(this.removeEventListener(t.type,e),shpsAjax.cpnMgr.operator.open(m,{cnr:document.getElementById(d)}),void 0)}),requestAnimationFrame(function(){h.classList.remove("opa-0"),b.intro(c).then(function(){t&&t.cb&&"end"==t.cbTiming&&t.cb()})})})},v.exit=function(t){},v.swapOut=function(){u()},v.swapIn=function(){l()};var y=!1,E=void 0;v.hashChange=function(t){var e=/^#([^#]+.*)/;if(t=e.exec(t),null!==t){if(t=t[1],t==E)return!0;y||(y=!0,g.enableSticky(),b.maxBlks())}else y&&(y=!1,g.disableSticky(),b.restoreBlks())}}}();