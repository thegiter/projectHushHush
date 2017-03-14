"use strict";!function(){var t="lv2_featured",e="/shared/modules/contour/",i="ftd-ctt-cnr",n="ftd-ctt-col",r="ftd-ctt-block",s="ftd-ctt-img-cnr",o="ftd-ctt-img",a=shpsCmm.moduleMgr;a.hooks[t]=function(t){var d=t.obj={},h=d.lv2Ftd=function(t,e,n){var r=this;this.cnr=t,this.cttCnr=t.getElementsByClassName(i)[0],this.itemIdx=0,this.ldedP=shpsCmm.createAjax("POST",e,shpsCmm.getRefererParam()+"&refpg="+window.location.hash,"json").then(function(t){var e=t.response;if("Array"==classOf(e))return e.forEach(function(t){imgPreload(t.img_url)}),h.getContour(),r.ftdCtt=e,void 0;throw new Error("ftd ctt ld failed")})};h.prototype.lded=function(){return this.ldedP},h.prototype.intro=function(t){var e=this.cnr;return new Promise(function(i,n){requestAnimationFrame(function(){e.classList.remove("dsp-non"),e.addEventListener("transitionend",function n(t){return t.target!=this?!1:(this.removeEventListener("transitionend",n),i(),void 0)}),requestAnimationFrame(function(){e.style.height=t})})})},h.prototype.createCtt=function(){function t(t){var e=d,i=m,n=a.offsetWidth,r=a.offsetHeight;e=n,i=e/A,r>i&&(i=r,e=i*A);var s=n/2,o=r/2,h=e/2,c=i/2,l=t.parentNode.parentNode.parentNode,g=s-l.offsetLeft,f=g-h,u=t.parentNode.parentNode,v=o-u.offsetTop,p=v-c,_=f/(t.offsetWidth-e)*100,w=p/(t.offsetHeight-i)*100,y=e/t.offsetWidth*100,x=i/t.offsetHeight*100;t.style.backgroundSize=y+"% "+x+"%",t.style.backgroundPosition=_+"% "+w+"%"}var e=this.ftdCtt[this.itemIdx],i=e.img_url;this.imgLdedP=shpsCmm.imgLdr.imgLded(i);var a=this.cttCnr,d=e.img_wdt,m=e.img_hgt,c=e.item_link,l=e.txt,g=e.txt_pstn,f=e.txt_wdt,u=e.txt_html,v=a.children[0];this.ttlWdt=a.offsetWidth,this.ttlHgt=a.offsetHeight,this.block_hgt_min=Math.round(this.ttlHgt/3),this.img_wdt_min=Math.round(this.ttlWdt/6),this.img_wdt_max=this.ttlWdt-this.img_wdt_min;var p=this.ttlWdt,_=p,w;if(l){if(w="txt","undefined"==typeof g||"lft"!=g){var y=["lft","ctr","rgt"];g=y[getRandomInt(0,3)]}if("ctr"==g){var x=this.ttlWdt-2*this.img_wdt_min;("undefined"==typeof f||f>x)&&(f=getRandomInt(img_wdt_min,x+1)),this.img_wdt_max-=f}else("undefined"==typeof f||f>this.img_wdt_max)&&(f=getRandomInt(this.img_wdt_min,this.img_wdt_max+1)),this.img_wdt_max=this.ttlWdt-f;_=this.ttlWdt-f}else{var C=["even","random"];w=C[getRandomInt(0,2)]}for(var H=[],L=0,b=0,k=[];p;){var E="img";switch(w){case"txt":switch(g){case"lft":0===L&&(E="txt");break;case"ctr":1==L&&(E="txt");break;case"rgt":p<f+this.img_wdt_min&&(E="txt")}break;case"even":E="img";break;case"random":E="img"}var P="";switch(E){case"img":H[L]=getRandomInt(this.img_wdt_min,this.img_wdt_max+1),_-H[L]<this.img_wdt_min&&(H[L]=_);for(var B=0,N=[],I=this.ttlHgt;I>=this.block_hgt_min;){var W;if(0===B){var O;O=H[L]>=2*this.img_wdt_min?.1*this.ttlHgt:this.ttlHgt-this.block_hgt_min,W=getRandomInt(0,O+1),I-=W}else W+=N[B-1].hgt;var R=getRandomInt(this.block_hgt_min,this.ttlHgt-W);I-=R;var T=R/this.ttlHgt*100,F=W/this.ttlHgt*100;P+='\r\n							<div class="'+r+'" style="top:'+F+"%;height:"+T+'%;">\r\n								<div class="'+s+'">\r\n									<a href="'+c+'" title="Open the featured article page." class="'+o+'" style="background-image:url('+i+');">\r\n									</a>\r\n								</div>\r\n							</div>',N[B]={top:W,hgt:R},B++}_-=H[L];break;case"txt":H[L]=f,P=txtHTML}k.push('<div class="'+n+'" style="width:'+H[L]+'px"><!--col stands for column-->'+P+"\r\n				</div>"),p-=H[L],b+=H[L],L++}v.innerHTML=k.join(""),this.contourP=h.contourP.then(function(t){forEachNodeItem(v.getElementsByClassName(o),function(e){var i=t.cloneNode(!0);e.appendChild(i),requestAnimationFrame(function(){i.classList.remove("opa-0")})})});var A=d/m,M=[];return forEachNodeItem(v.getElementsByClassName(o),function(e){M.push(shpsCmm.optElmRszLsnr.add(e,function(){t(e)}))}),Promise.all(M)},h.prototype.introBlks=function(){var t=this.cttCnr;this.imgLdedP.then(function(){t.classList.remove("vsb-hid");var e=new Promise(function(e,i){t.addEventListener("transitionend",function n(t){return t.target!=this?!1:(this.removeEventListener("transitionend",n),e(),void 0)})});return requestAnimationFrame(function(){t.classList.remove("opa-0")}),e})},h.prototype.maxBlks=function(){var t=this.cttCnr;if(!t.mB){t.mB=!0;var e=this;forEachNodeItem(t.getElementsByClassName(n),function(t,i){forEachNodeItem(t.children,function(i,n){if(i.mBOrigTop||(i.mBOrigTop=parseFloat(i.style.top)),i.mBOrigHgt||(i.mBOrigHgt=parseFloat(i.style.height)),0===n){var r=i.mBOrigTop+i.mBOrigHgt;i.style.top=0,i.style.height=r+"%"}n===t.children.length-1&&(i.style.height=100-parseFloat(i.style.top)+"%");var s=i.children[0];s.style.bottom=0,s.style.right=0;var o=s.children[0];o.style.boxShadow="none",e.contourP.then(function(){o.getElementsByTagName("div")[0].classList.add("opa-0")})})})}},h.getContour=function(){return this.contourP?this.contourP:this.contourP=a.get(e).then(function(t){var e=document.createElement("div");return e.innerHTML='<div class="contour-wpr opa-0 fade-in-norm">\r\n					'+t.html+"\r\n				</div>",e.children[0]})}}}();