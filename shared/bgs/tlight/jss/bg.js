!function(){var e="bg-tlight",n=shpsCmm.iCMgr,t=new ttlElmOnload(e);Promise.all([n.getObjUrl("/shared/bgs/tlight/csss/bg.css","link"),n.getObjUrl("/shared/bgs/tlight/imgs/bg_ptn.png","img")]).then(function(e){t.addCss(e[0].objUrl),t.addImgs(e[1].objUrl,[""]),t.useDefault=!1,t.start()},function(){console.log("get history view css obj url rejected, history view css does not exist in indexed db.")}),shpsAjax.hooks.bg_tlight=function(n){var i=shpsAjax.cpnList[n].obj={},s="closed",c,o,d,r;i.intro=function(e){switch(s){case"closed":s="opening",c.addEventListener("transitionend",function(n){n.target.removeEventListener(n.type,arguments.callee),s="opened",e&&e.cb&&"end"==e.cbTiming&&e.cb()},!1),mdf_cls(c,"remove","opa-0"),e&&e.cb&&("start"==e.cbTiming||"during"==e.cbTiming)&&e.cb();break;case"opening":e&&e.cb&&("end"==e.cbTiming?c.addEventListener("transitionend",function n(t){t.target.removeEventListener(t.type,n),e.cb()},!1):"during"==e.cbTiming&&e.cb());break;case"opened":e&&e.cb&&"end"==e.cbTiming&&e.cb()}},i.exit=function(){s="closed",mdf_cls(c,"add","opa-0")},i.lded=!1,i.onload=function(n){function i(){c=document.getElementById(e);var t=c.children;o=t[0],d=t[1],r=t[2],n()}t.lded?i():t.callback=i}}}();