"use strict";!function(){var e="homepage",t="hp-bd",n="hp-bigpic-base",s="hp-bar",i="hp-menu",o="hp-menu-resp",a="/shared/modules/menu/btn/",r="/shared/modules/onenote_menu/",d="/shared/modules/menu_items/",c="/shared/modules/ftr/info_lnks/",l="/shared/modules/tooltips/simple/",m="/shared/modules/js_frameworks/anim/",u="ftr-info-lnks",h=shpsCmm.moduleMgr,f=shpsCmm.elmOnldr,p=shpsCmm.util,v=shpsCmm.evtMgr,g=new shpsCmm.TtlElmOnldr;g.addElm(t);var E=new shpsCmm.TtlElmOnldr;E.addElm(n),E.addImgs("/homepage/imgs//*replace*/",["base_ptn.jpg","main_pic.jpg","abstract.png"],!0);var y=Promise.all([f.elmLded(s),h.get(a,!0)]);shpsAjax.hooks[e]=function(e){var f=shpsAjax.pgMgr.cache[e].obj={};f.lded=!1;var L=void 0,b=void 0,x=void 0,w=void 0,C=function A(){},B=function I(){},H=function N(){},P=function W(){var e=void 0;h.get(m).then(function(t){e=t.obj.transScrl}),y.then(function(){new p.AnimFramePromise(function(){x.classList.remove("dsp-non"),x.addEventListener("transitionend",function t(n){if(n.target!=this)return!1;if(n.target.removeEventListener(n.type,t),x.classList.remove("hp-bar-sdw-in-init"),e){var s=(L.scrollWidth-L.clientWidth)/2,i=L.clientWidth/2;s>i&&(s=i),e(L,s,!0,1.5)}},!1)}).then(function(){x.classList.remove("hp-bar-in-init")})})};f.intro=function(e){var t={cb:function n(){e&&e.cb&&e.cb(),new Promise(function(e){L.addEventListener("transitionend",function t(n){return n.target!=this?!1:(n.target.removeEventListener(n.type,t),e(),void 0)},!1)}).then(function(){return E.lded()}).then(function(){b.style.left="0",setTimeout(P,1e3)}),new p.AnimFramePromise(function(){L.classList.remove("dsp-non")}).then(function(){w(),L.classList.remove("opa-0")})},cbTiming:"end"};shpsAjax.cpnMgr.operator.open("bg_basic",t)},f.exit=function(e){var t=function n(){L.classList.add("dsp-non")};e&&e.instant?(t(),C()):(e&&e.cb&&"end"==e.cbTiming&&(t=function s(){L.classList.add("dsp-non"),e.cb()}),L.addEventListener("transitionend",function i(e){return e.target!=this?!1:(e.target.removeEventListener(e.type,i),t(),void 0)}),H()),L.classList.add("opa-0"),b.style.removeProperty("left"),B()};var k=function M(){},j=function T(){};f.swapOut=function(){j()},f.swapIn=function(){k()},f.onload=function(e){g.lded().then(function(){function s(){if(b.offsetHeight!=b.lastH){b.lastH=b.offsetHeight;var e=1.026851851851852*b.offsetHeight,t=b.offsetWidth-e;t>0?b.style.marginRight=t+"px":b.style.removeProperty("margin-right"),b.style.width=e+"px"}}function i(){L.offsetWidth!=L.lastW&&(L.lastW=L.offsetWidth,Array.from(o.children).forEach(function(e){e.children[0].style.maxWidth=L.lastW+"px"}))}L=document.getElementById(t),b=document.getElementById(n),b.lastH=0,v.addEvtLsnr(b,"optElmRsz",s),L.lastW=0;var o=document.getElementById("hp-cnr");v.addEvtLsnr(L,"optElmRsz",i),w=function a(){w=function e(){},s(),i()},shpsAjax.psdSidebar.regFixed(function(e){L.style.backgroundPosition=e+"px",b.style.backgroundPosition=e+"px, center bottom, center top"}),e()}),y.then(function(){function e(e){r[e].state="close",r[e].btnVer.classList.remove("ver-to-hor");var t=r[e].cnr;new p.AnimFramePromise(function(){"auto"==t.style.height&&(t.style.height=t.offsetHeight+"px")}).then(function(){t.style.height="0"}),e==d&&(d=void 0)}function t(t){r[t].state="open",d&&e(d),r[t].btnVer.classList.add("ver-to-hor");var n=r[t].cnr,s=n.cloneNode(!0);s.classList.add("vsb-hid","pos-abs"),s.style.height="auto",n.parentNode.appendChild(s),n.addEventListener("transitionend",function i(e){return e.target!=this?!1:(e.target.removeEventListener(e.type,i),"open"==r[t].state&&(n.style.height="auto"),void 0)},!1),n.style.height=s.offsetHeight+"px",n.parentNode.removeChild(s),d=t}function n(n){var s=this.dataset.index;"close"==r[s].state?t(s):e(s)}function i(){f?f=!1:(d&&e(d),"opening"==m&&v.triggerOn(c,"click",!1))}x=document.getElementById(s),C=function g(){x.classList.add("dsp-non")},B=function E(){x.classList.add("hp-bar-sdw-in-init","hp-bar-in-init")},H=function y(){x.addEventListener("transitionend",function e(t){return t.target!=this?!1:(t.target.removeEventListener(t.type,e),C(),void 0)})};var o=document.getElementById("hp-menu").children,r=[],d=void 0;Array.from(o).forEach(function(e,t){var s=r[t]={},i=s.btn=e.getElementsByClassName("expand-btn-cnr")[0].getElementsByTagName("button")[0];i.dataset.index=t,s.btnVer=i.getElementsByClassName("ver")[0],s.cnr=e.getElementsByClassName("sub-menu-cnr")[0],s.state="close",i.addEventListener("click",n,!1)});var c=document.getElementById("hp-menu-btn");h.get(a).then(function(e){new e.obj.MenuBtn(c)});var l=document.getElementById("hp-menu-resp"),m="closed",u="60vh";c.addEventListener("click",function(){"opening"==m?(m="closing",l.style.maxHeight==u?new p.AnimFramePromise(function(){l.classList.remove("hp-menu-resp-trans"),l.style.maxHeight=l.offsetHeight+"px"}).then(function(){l.classList.add("hp-menu-resp-trans"),l.style.maxHeight="0"}):l.style.maxHeight="0"):(m="opening",l.style.maxHeight=u)},!1);var f=!1;x.addEventListener("click",function(){f=!0},!1),k=function L(){window.addEventListener("click",i,!1)},j=function b(){window.removeEventListener("click",i)},k()}),Promise.all([g.lded(),E.lded(),y]).then(function(){return Promise.all([h.get(r,!0),h.get(d,!0)])}).then(function(e){function t(e,n,s,i){var o=document.createElement("ul");o.classList.add("no-style","no-indent");var a=document.createElement("span");a.classList.add("pointer"),p.forEachObjProp(e,function(e,n){var r=document.createElement("li"),d=document.createElement("div");d.classList.add("con-marker"),r.appendChild(d);var c=document.createElement("a");if(c.href=e.href,c.textContent=n,c.classList.add("va-mid"),r.appendChild(c),"ctt"==e.type)c.classList.add("ctt");else if("cat"==e.type&&(c.classList.add("cat"),e.smi)){var l=document.createElement("span");l.innerHTML="&#8901;",l.classList.add("va-mid","sep"),r.appendChild(l);var m=document.createElement("button");m.title="Show sub-menus.",m.type="button",m.innerHTML="&#8811;",m.classList.add("va-mid","arrow","no-style","clickable");var u=t(e.smi,n,s,i+1);m.addEventListener("click",function(){var e=u.parentNode;e.insertBefore(u,e.childNodes[0]),s.focus(i+1),r.insertBefore(a,r.childNodes[0])},!1),r.appendChild(m)}o.appendChild(r)});var r=document.createElement("span");return r.textContent="~~ "+n+" ~~",o.appendChild(r),s.addToPane(i,o),o}for(var n=e[0].obj,s=e[1].obj,a=s.getItems(),r=document.getElementById(i).children,d=0;d<r.length;d++){var c=r[d].children[0].textContent;if(a[c]){var l=r[d].getElementsByClassName("omc")[0],m=new n.onenoteMenu(l,"50vh");t(a[c].smi,c,m,1),m.focus(1),l.classList.remove("loading")}}var u=document.getElementById(o).children[0],h=new n.onenoteMenu(u,"40vh");t(a,"SHPS",h,1),h.focus(1),u.classList.remove("loading")}).then(function(){return h.get(c)}).then(function(){Array.from(x.getElementsByClassName(u)).forEach(function(e){new p.AnimFramePromise(function(){e.classList.remove("vsb-hid")}).then(function(){e.classList.remove("opa-0")})}),h.get(l)})}}}();