"use strict";function _classCallCheck(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?t:e}function _inherits(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}var _extends=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var s=arguments[e];for(var r in s)Object.prototype.hasOwnProperty.call(s,r)&&(t[r]=s[r])}return t},_createClass=function(){function t(t,e){for(var s=0;s<e.length;s++){var r=e[s];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}return function(e,s,r){return s&&t(e.prototype,s),r&&t(e,r),e}}();!function(){var t="lv2_ctt_ldr",e="/shared/modules/ldg_signs/td_bars/",s="/shared/modules/ldg_signs/css_spiners/",r="/shared/modules/js_frameworks/iv_mgr/",n=4,a=100/n,i=100-a,o=["start","ctr","end"],l=shpsCmm.moduleMgr,d=shpsCmm.util,c=shpsCmm.evtMgr,m=function p(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:d.getRandomInt(0,361),e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,s=arguments.length>2&&void 0!==arguments[2]?arguments[2]:0;return"hsla("+t+", 96%, "+(34+e)+"%, "+(.3+s)+")"},u=void 0,_=void 0,h=void 0,g=l.get(e,!0).then(function(t){u=t.obj.ldgTdBarsFactory}),f=l.get(s,!0).then(function(t){_=t.obj.ldgCssSpinersFactory});l.get(r,!0).then(function(t){h=t.obj.ivMgr}),l.hooks[t]=function(t){var e=t.obj={},s={},r=function(t){function e(t){_classCallCheck(this,e);var r=_possibleConstructorReturn(this,(e.__proto__||Object.getPrototypeOf(e)).call(this,t));r._setElm=r._setElm.bind(r),r.state={imgStyle:{},randZ:d.getRandomInt(100,201),blur:!0,translate:!0,opa:!0};var n=d.getRandomInt(0,361),l=m(n);r._lightColor=m(n,50),r._wprStyle={backgroundImage:"linear-gradient(to bottom right, "+l+" 0%, "+r._lightColor+" 50%, "+l+" 100%)"},r._txtStyle={};var c=r.props.item,u=c.img_url;r._imgLdedP=Promise.resolve();var _=c.txt,h=c.txt_html,g=c.txt_img,f=c.txt_wdt,p=c.txt_pstn;if(r._txtCtt="",r._txtCls="",_?(r._txtCtt=h,p?"lft"==p?p="start":"rgt"==p&&(p="end"):p=o[d.getRandomInt(0,3)],f||(f=d.getRandomInt(a,i+1)),r._txtStyle.flexBasis=f+"%"):c.img_cap&&(r._txtCtt=c.img_cap),r._imgCls="",u){var v=function C(t){return shpsCmm.imgLdr.imgLded(t).then(function(){return t})};r.props.cached&&(v=function b(t){return shpsCmm.iCMgr.getBlobUrl(t,"img")}),r._imgLdedP=s[u]?s[u]:s[u]=v(u),r._imgLdedP.then(function(t){r.setState({imgStyle:{backgroundImage:"url("+t+")"}})})}else r._imgCls="dsp-non";if(_||u)if(_)if(u)if(r._arCls="3-6",r._szeCls="",r._orientationCls="",g){switch(r._txtCls="pos-abs top-0 btm-0",p){case"start":r._txtCls+=" lft-0";break;case"ctr":r._txtStyle.left="50%",r._txtStyle.transform="translatex(-50%)";break;case"end":r._txtCls+=" rgt-0"}r._txtStyle.width=f+"%"}else r.state.imgStyle.backgroundPosition="left","start"==p&&(r._txtCls+=" lft-0",r.state.imgStyle.backgroundPosition="right");else switch(r._arCls="3-6",r._szeCls="",r._orientationCls="",.28125>f?r._orientationCls="portrait":9/16>f&&(r._arCls="3-3",r._szeCls=["","sml"][d.getRandomInt(0,2)]),p){case"start":case"end":r._wprStyle.justifyContent="flex-"+p;break;case"ctr":r._wprStyle.justifyContent="center"}else r._szeCls="",r._orientationCls="",r._txtCtt?(r._arCls=["3-3","3-6","3-9"][d.getRandomInt(0,3)],"3-6"==r._arCls&&(r._orientationCls="portrait")):r._arCls="3-6";else r._arCls="3-3",r._szeCls="",r._orientationCls=["portrait",""][d.getRandomInt(0,2)];return"3-3"==r._arCls&&(r._orientationCls="portrait"),r}return _inherits(e,t),_createClass(e,[{key:"_setElm",value:function r(t){this._elm=t,this._txtCnr=t.getElementsByClassName("txt-cnr")[0],this._imgCnr=t.getElementsByClassName("img-cnr")[0]}}],[{key:"defaultProps",get:function n(){return{clss:""}}}]),_createClass(e,[{key:"componentDidMount",value:function l(){var t=this;this._txtCtt&&(this._txtCnr.innerHTML=this._txtCtt),f.then(function(){t._imgLded||(t._ldgCs=_.createObj(),t._imgCnr.appendChild(t._ldgCs.getElm()),t._ldgCs.intro())}),this._imgLdedP.then(function(){t._imgLded=!0,t._ldgCs&&t._ldgCs.rmv()})}},{key:"getElm",value:function c(){return this._elm}},{key:"rand",value:function u(){this.setState({randZ:d.getRandomInt(100,201),randX:d.getRandomInt(-50,50),randY:d.getRandomInt(-50,50)})}},{key:"mve3d",value:function h(t,e){var s=void 0,r=void 0,n=void 0,a=void 0;.5!=t&&(t=2*(t-.5),s=-50*t,a=45*t),.5!=e&&(e=2*(e-.5),r=-50*e,n=-45*e),this.setState({mve3dX:s,mve3dY:r,rotateX:n,rotateY:a})}},{key:"focus",value:function g(){this.setState({blur:!1,translate:!1,opa:!1})}},{key:"blur",value:function p(){this.setState({blur:!0,translate:!0,opa:!0})}},{key:"render",value:function v(){var t="drop-shadow(-.5em -.5em .2em "+this._lightColor+")\n				drop-shadow(.5em -.5em .2em "+this._lightColor+")\n				drop-shadow(-.5em .5em .2em "+this._lightColor+")\n				drop-shadow(.5em .5em .2em "+this._lightColor+")";this.state.blur&&(t+=" blur("+((this.state.randZ-100)/100*.45+.05)+"vmax)");var e={filter:t};if(this.state.translate){if(e.transform="translatez(-"+this.state.randZ+"vmax)",this.state.randX||this.state.mve3dX){var s=0,r=0;this.state.randX&&(s=this.state.randX),this.state.mve3dX&&(r=this.state.mve3dX),e.transform+=" translatex("+(s+r)+"%)"}if(this.state.randY||this.state.mve3dY){var n=0,a=0;this.state.randY&&(n=this.state.randY),this.state.mve3dY&&(a=this.state.mve3dY),e.transform+=" translatey("+(n+a)+"%)"}this.state.rotateX&&(e.transform+=" rotateX("+this.state.rotateX+"deg)"),this.state.rotateY&&(e.transform+=" rotateY("+this.state.rotateY+"deg)")}return this.state.opa&&(e.opacity=1-this.state.randZ/200*.4),React.createElement("div",{className:"pos-rel psv-3d ar-"+this._arCls+" "+this._szeCls+" "+this._orientationCls+" "+this.props.clss,ref:this._setElm},React.createElement("div",{className:"pos-abs-ful"}),React.createElement("a",{href:this.props.item.item_link,className:"dsp-blk pos-abs-ful",style:e},React.createElement("div",{className:"pos-abs-ful dsp-flx",style:this._wprStyle},React.createElement("div",{className:"img-cnr pos-rel "+this._imgCls,style:this.state.imgStyle}),React.createElement("div",{className:"txt-cnr pos-rel "+this._txtCls,style:this._txtStyle}))))}}]),e}(React.Component),n=function(t){function e(t){_classCallCheck(this,e);var s=_possibleConstructorReturn(this,(e.__proto__||Object.getPrototypeOf(e)).call(this,t));return s._setElm=s._setElm.bind(s),s._setObj=s._setObj.bind(s),s.play=s.play.bind(s),s.stop=s.stop.bind(s),s.state={items:[]},s.props.itemsUrl&&(s._itmsLdedP=shpsCmm.jsonLdr.lded(s.props.itemsUrl,"refpg="+window.location.hash,s.props.cached,"cttItemsList").then(function(t){if("Array"===d.classOf(t)){var e=[];return s._itmObjs=[],t.forEach(function(t){e.push(React.createElement(r,{item:t,cached:s.props.cached,ref:s._setObj}))}),s.setState({items:e}),s._itmsListLded=!0}throw new Error("ftd ctt ld failed")})),s}return _inherits(e,t),_createClass(e,[{key:"_setElm",value:function s(t){this._elm=t,this._grid=t.getElementsByClassName("lv2-cl-grid")[0]}},{key:"_setObj",value:function n(t){this._itmObjs.push(t)}}],[{key:"defaultProps",get:function a(){return{tag:"section",clss:""}}}]),_createClass(e,[{key:"componentDidMount",value:function i(){var t=this;this._itmsLdedP&&(g.then(function(){t._itmsListLded||(t._ldgTb=u.createObj(),t._elm.appendChild(t._ldgTb.getElm()),t._ldgTb.intro())}),this._itmsLdedP.then(function(){t._ldgTb&&t._ldgTb.rmv(),t._itmObjs.forEach(function(e){var s=e.getElm().children[0];h.addEvtLsnr(s,"in",function(){e.focus()},{top:.3,btm:.3},t.props.scrlCnr),h.addEvtLsnr(s,"out",function(){e.blur()},{top:.3,btm:.3},t.props.scrlCnr)}),t._stopped||t.play();var e=t;c.addEvtLsnr(t._grid,"optMseMve",function(t){var s=e._grid.getBoundingClientRect(),r=(t.clientX-s.left)/e._grid.offsetWidth,n=(t.clientY-s.top)/e._grid.offsetHeight;e._itmObjs.forEach(function(t){t.mve3d(r,n)})})}))}},{key:"getElm",value:function o(){return this._elm}},{key:"play",value:function l(){var t=this;return this._itmsListLded?(this._stopped=!1,this._3dIvtId=setTimeout(function(){var e=t._itmObjs[d.getRandomInt(0,t._itmObjs.length)];e.rand(),t.play()},d.getRandomInt(250,1e4)),void 0):!1}},{key:"stop",value:function m(){this._3dIvtId&&(this._stopped=!0,clearInterval(this._3dIvtId),this._3dIvtId=void 0)}},{key:"render",value:function _(){return React.createElement(this.props.tag,{className:"lv2-cl-cnr pos-rel td-cnr "+this.props.clss,ref:this._setElm},React.createElement("div",{className:"lv2-cl-grid psv-3d"},React.createElement("div",null),this.state.items))}}]),e}(React.Component),l=e.lv2CttLdrFactory={};l.createObj=function(t){function e(t){s=t}var s=void 0;return ReactDOM.render(React.createElement(n,_extends({},t,{ref:e})),document.createDocumentFragment()),s}}}();