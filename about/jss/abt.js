"use strict";!function(){var t="about",e="About",n="#!about/",s="/shared/modules/lv2/common/",o="abt-bd",i="abt-pgs-wpr",r=.2;shpsAjax.hooks[t]=function(t){function a(){h.scrollLeft=h.clientWidth/2}function c(t){return h.scrollLeft!=t.offsetLeft?L(h,t.offsetLeft,!0,r):Promise.resolve()}function d(){}var m=shpsAjax.pgMgr.cache[t].obj={};m.lded=!1;var l=void 0,u=void 0,f=void 0,h=void 0,v=void 0,p=void 0,g=shpsAjax.lnkMgr;m.onload=function(t){f=document.getElementById(o),h=document.getElementById(i),v=h.getElementsByTagName("a"),shpsCmm.moduleMgr.get(s,!0).then(function(t){p=new t.obj.lv2CmmFactory.createObj("TTL",{ttl:e,href:n});var s=p.getElm();g.register(s.getElementsByTagName("a")[0]),f.insertBefore(s,f.children[0])}),l=document.getElementById("abt-shps-cnr"),u=document.getElementById("abt-sh-cnr"),forEachNodeItem(v,function(t){g.register(t)}),optElmRszLsnr.add(h,a),t()},m.intro=function(t){f.classList.remove("dsp-non"),f.addEventListener("transitionend",function e(n){return n.target!=this||"opacity"!=n.propertyName||1!=getStyle(this,"opacity")?!1:(this.removeEventListener(n.type,e),requestAnimationFrame(function(){forEachNodeItem(v,function(t){t.classList.remove("dsp-non")}),requestAnimationFrame(function(){a(),requestAnimationFrame(function(){forEachNodeItem(v,function(t){t.classList.remove("init")}),t&&t.cb&&"end"==t.cbTiming&&t.cb()})})}),void 0)}),requestAnimationFrame(function(){f.classList.remove("opa-0")})};var L=shpsCmm.transScrl;m.exit=function(t){function e(){l.classList.add("dsp-non"),u.classList.add("dsp-non"),f.classList.add("opa-0")}var n=h.clientWidth/2;t&&t.instant?(f.classList.add("dsp-non"),e()):(h.scrollLeft!=n&&L(h,n,!0,r),l.addEventListener("transitionend",function s(n){return n.target!=this||"transform"!=n.propertyName?!1:(l.removeEventListener(n.type,s),t&&t.cb&&"end"==t.cbTiming&&f.addEventListener("transitionend",function o(e){return e.target!=this||"opacity"!=e.propertyName||0!=getStyle(this,"opacity")?!1:(f.removeEventListener(e.type,o),t.cb(),void 0)}),e(),void 0)})),forEachNodeItem(v,function(t){t.classList.add("skewed"),t.classList.add("init")})};var E=!1,y,b,N=!1;m.hashChange=function(t){var e=/^#([^#]+.*)/;return t=e.exec(t),null===t?(E&&(E=!1,y=null,b=null,shpsCmm.swipeClick.rmv(h,d),h.classList.remove("scrl-x"),L(h,(h.scrollWidth-h.clientWidth)/2,!0,r).then(function(){optElmRszLsnr.add(h,a)}),forEachNodeItem(v,function(t){t.classList.add("skewed")})),!0):(t=t[1],t==y?!0:g.registry["about/#"+t]?(y=t,forEachNodeItem(v,function(e){-1!=g.registry["about/#"+t].indexOf(e)&&(b=e)}),N?N=!1:(E?c(b):(E=!0,optElmRszLsnr.rmv(h,a),forEachNodeItem(v,function(t){t.classList.remove("skewed")}),c(b).then(function(){shpsCmm.swipeClick.add(h,function(){return{h:h.getElementsByTagName("a").length}},function(t){var e=h.getElementsByTagName("a")[t.h];return e!=b&&(N=!0),e},d),h.classList.add("scrl-x")})),void 0)):!1)},m.swapOut=function(){y=null}}}();