"use strict";!function(){var t="betaSign",e="beta",s="beta-mdt",n="/shared/modules/tooltips/follow_mouse/",o=new shpsCmm.TtlElmOnldr;o.addElm(s),o.lded().then(function(){var t=document.getElementById(s);shpsCmm.moduleMgr.get(n).then(function(e){e.obj.tooltipFm.addTo(t,"The site is still in beta stage.")});var o=document.getElementById(e);new shpsCmm.util.AnimFramePromise(function(){o.classList.remove("dsp-non")}).then(function(){o.classList.remove("opa-0")})}),shpsAjax.hooks[t]=function(t){shpsAjax.cpnList[t].lded=!0;var e=shpsAjax.cpnList[t].obj={};e.onload=function(t){t()}}}();