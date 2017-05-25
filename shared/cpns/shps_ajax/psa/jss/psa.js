"use strict";function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var s in n)Object.prototype.hasOwnProperty.call(n,s)&&(e[s]=n[s])}return e},_createClass=function(){function e(e,t){for(var n=0;n<t.length;n++){var s=t[n];s.enumerable=s.enumerable||!1,s.configurable=!0,"value"in s&&(s.writable=!0),Object.defineProperty(e,s.key,s)}}return function(t,n,s){return n&&e(t.prototype,n),s&&e(t,s),t}}();!function(){function e(e){var t=document.getElementById(_);g.elm=e.getElm(),t.insertBefore(g.elm,t.children[0]),g.scrlCnr=e.getScrlCnr(),g.cttCnr=e.getCttCnr()}function t(){w&&w.enable()}function n(){w&&w.disable()}function s(){g.elm.classList.remove("psd-menu-scrl-hide")}function r(){N.scrollTop>0?O&&(O=!1,s(),t()):O||(O=!0,g.elm.classList.add("psd-menu-scrl-hide"),n())}function i(){N&&C.rmvEvtLsnr(N,"optScrl",r)}function o(){return R.matches?(n(),s(),i()):j.matches?(N&&(r(),C.addEvtLsnr(N,"optScrl",r)),!0):(i(),s(),t(),void 0)}function a(){return null===N?!0:(n(),j.removeListener(o),R.removeListener(o),i(),N=null,s(),new y.AnimFramePromise(function(){g.elm.style.maxWidth=g.elm.offsetWidth+"px"}).then(function(){g.elm.classList.add("psd-menu-no-expand")}),void 0)}function l(){return g.crtPg===N?!0:null!==N?(N&&i(),N=g.crtPg,o(),!0):(g.elm.style.removeProperty("max-width"),g.elm.classList.remove("psd-menu-no-expand"),N=g.crtPg,o(),j.addListener(o),R.addListener(o),void 0)}var c="psdSidebarActivator",d="/shared/modules/menu/btn/",m="/shared/modules/hex_grid/",u="/shared/modules/hex_frame/",p="/shared/modules/tooltips/follow_mouse/",h="/shared/cpns/shps_ajax/psa/csss/hf.css",f="/shared/cpns/shps_ajax/psa/csss/hg.css",v="/shared/cpns/shps_ajax/psa/csss/mb.css",_="psd-bd-cnr",g=shpsAjax.psdSidebar,b=shpsCmm.moduleMgr,E=shpsCmm.extFileMgr,C=shpsCmm.evtMgr,y=shpsCmm.util,x=shpsCmm.domMgr,L=function(e){function t(e){_classCallCheck(this,t);var n=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n._setElm=n._setElm.bind(n),n}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function n(e){this._elm=e,this._scrlCnr=e.children[0].children[0],this._cttCnr=e.getElementsByClassName("ctt-cnr")[0].children[0]}}]),_createClass(t,[{key:"getElm",value:function s(){return this._elm}},{key:"getScrlCnr",value:function r(){return this._scrlCnr}},{key:"getCttCnr",value:function i(){return this._cttCnr}},{key:"render",value:function o(){return React.createElement("aside",{id:"psd-menu-cnr",className:"pos-rel dsp-flx",ref:this._setElm},React.createElement("div",{className:"scrl-cnr-wpr dsp-flx flx-dir-col h-100 disabled"},React.createElement("ul",{id:"psd-menu-scrl-cnr",className:"pos-rel dsp-ib no-style no-indent mrg-0"})),React.createElement("div",{className:"ctt-cnr pos-rel dsp-flx flx-dir-col"},React.createElement("div",{className:"dsp-flx"})),React.createElement("div",{className:"sdw disabled pos-abs-ful of-hid"}))}}]),t}(React.Component);ReactDOM.render(React.createElement(L,{ref:e}),document.createDocumentFragment());var k=0;C.addEvtLsnr(g.elm,"optElmRsz",function(){var e=g.elm.offsetWidth;e!=k&&(k=e,g.rszHdlrs.forEach(function(t){t(e)}))});var w=void 0,N=void 0,j=window.matchMedia("(max-aspect-ratio: 16/9)"),R=window.matchMedia("(max-aspect-ratio: 4/3)"),O=!0;g.isExpand()?l():a(),g.addEvtLsnr("noExpand",a),g.addEvtLsnr("doExpand",l);var B=void 0;g.addCtt=function(e,t){e&&(e.classList.add("max-w-0"),this.cttCnr.appendChild(e)),t&&t.addEventListener("click",function(t){return e&&B==e?(e.classList.add("max-w-0"),B=void 0):(B&&(B.classList.add("max-w-0"),B=void 0),e&&(e.classList.remove("max-w-0"),B=e),void 0)})},g.newCttPs.forEach(function(e){e()});var P=[],M=[],F=void 0,T=void 0,A=function(e){function t(e){_classCallCheck(this,t);var n=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n._setElm=n._setElm.bind(n),n._dspNonCls="dsp-non",n.props.noDspNon&&(n._dspNonCls=""),n}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function n(e){this._elm=e,this._btn=e.getElementsByTagName("button")[0],this._iconCnr=e.getElementsByClassName("icon-cnr")[0],this._icon=e.getElementsByClassName("icon")[0]}},{key:"_getMrg",value:function s(){var e=this._elm.cloneNode(!0);e.classList.add("vsb-hid","pos-abs"),e.classList.remove("dsp-non");var t=this._elm.parentNode;t.appendChild(e);var n={lft:x.getStyle(e,"margin-left"),rgt:x.getStyle(e,"margin-right")};return n.neg="-"+(e.offsetWidth+parseFloat(n.lft)+parseFloat(n.rgt))+"px",t.removeChild(e),n}}],[{key:"_insertHf",value:function r(e,t){var n=F.createObj(!1,{clss:"init init-trans"}).getElm();new y.AnimFramePromise(function(){e.insertBefore(n,t),n.getElementsByClassName("br")[0].addEventListener("transitionend",function s(e){this.removeEventListener(e.type,s),n.classList.remove("init-trans")})}).then(function(){n.classList.remove("init")})}},{key:"defaultProps",get:function i(){return{id:"",clss:"",btnClss:"",lblTxt:"",tag:"li"}}}]),_createClass(t,[{key:"componentDidMount",value:function o(){F?t._insertHf(this._iconCnr,this._icon):P.push({cnr:this._iconCnr,refElm:this._icon}),this.props.iconHtml&&(this._icon.innerHTML=this.props.iconHtml),this.props.toggle&&(this._btn.setAttribute("aria-pressed",!1),this._btn.addEventListener("click",function(e){this.setAttribute("aria-pressed",!("true"===this.getAttribute("aria-pressed")))})),this.props.tooltip&&(T?T.addTo(this._btn,this.props.tooltip):M.push({elm:this._btn,tt:this.props.tooltip}))}},{key:"getElm",value:function a(){return this._elm}},{key:"getBtn",value:function l(){return this._btn}},{key:"getIcon",value:function c(){return this._icon}},{key:"intro",value:function d(){var e=this;this._elm.style.marginLeft=this._getMrg().neg,new y.AnimFramePromise(function(){e._elm.classList.remove("dsp-non")}).then(function(){e._elm.style.removeProperty("margin-left")})}},{key:"outro",value:function m(){this._elm.addEventListener("transitionend",function e(t){return"margin-left"!=t.propertyName||t.target!=this?!1:(this.removeEventListener(t.type,e),this.classList.add("dsp-non"),void 0)}),this._elm.style.marginLeft=this._getMrg().neg}},{key:"render",value:function u(){return React.createElement(this.props.tag,{id:this.props.id,className:"icon-wpr-cnr "+this._dspNonCls+" "+this.props.clss,ref:this._setElm},React.createElement("button",{className:"icon-wpr no-style "+this.props.btnClss,type:"button",onClick:this.props.btnOnClk},React.createElement("span",{className:"icon-cnr pos-rel dsp-ib va-mid"},React.createElement("div",{className:"icon pos-abs"})),React.createElement("span",{className:"lbl dsp-ib of-hid va-mid"},this.props.lblTxt)))}}]),t}(React.Component);g.addBtn=function(e){function t(e){n=e}var n=void 0;return ReactDOM.render(React.createElement(A,_extends({},e,{ref:t})),document.createDocumentFragment()),g.scrlCnr.appendChild(n.getElm()),n},b.get(d,!0).then(function(e){E.lnked_bu("link",v).then(function(){var t=e.obj.menuBtnFactory.createObj({clss:"clickable",animBack:!0}),n=t.getElm(),s=g.addBtn({id:"psd-sidebar-menu-btn",noDspNon:!0,btnClss:n.className,iconHtml:n.innerHTML,tooltip:"Toggle Sidebar Tools",tag:"div"}),r=s.getElm(),i=s.getBtn();e.obj.MenuBtn.enableClkEvt(i,function(){r.classList.toggle("open")}),g.addCtt(void 0,i);var o=g.scrlCnr;o.parentNode.insertBefore(r,o)})}),g.newBtnPs.forEach(function(e){e()}),b.get(p).then(function(e){T=e.obj.tooltipFm,M.forEach(function(e){T.addTo(e.elm,e.tt)})}),b.get(u).then(function(e){E.lnked("link",h).then(function(){F=e.obj.hexFrameFactory,P.forEach(function(e){A._insertHf(e.cnr,e.refElm)})})}),b.get(m).then(function(e){E.lnked("link",f).then(function(){w=e.obj.hexGridFactory.createObj({numRows:25,numCols:35}),g.elm.insertBefore(w.getElm(),g.elm.children[0]),g.isExpand()&&w.enable()})}),shpsAjax.hooks[c]=function(e){var t=shpsAjax.cpnList[e];t.lded=!0;var n=t.obj={};n.onload=function(e){e()}}}();