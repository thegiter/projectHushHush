"use strict";function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var r in a)Object.prototype.hasOwnProperty.call(a,r)&&(e[r]=a[r])}return e},_createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,a,r){return a&&e(t.prototype,a),r&&e(t,r),t}}();!function(){var e="ldg_td_bars",t="/shared/modules/td_cube/",a=shpsCmm.moduleMgr,r=shpsCmm.domMgr,n=shpsCmm.evtMgr,s=shpsCmm.util;a.hooks[e]=function(e){var r=e.obj={},n=void 0;a.get(t,!0).then(function(e){n=e.obj.tdCubeFactory});var c=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a._setElm=a._setElm.bind(a),a.state={},a._cnrStyle={},a.props.wdt&&(a._cnrStyle.width=a.props.wdt),a.props.sdwSze&&(a._cnrStyle["--sdw-sze"]=a.props.sdwSze),a}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function a(e){this._elm=e,this._tdCnr=e.getElementsByClassName("td-cnr")[0]}}]),_createClass(t,[{key:"componentDidMount",value:function r(){for(var e=0;8>e;e++)this._tdCnr.appendChild(n.createObj({front:!0,lft:!0,rgt:!0}).getElm())}},{key:"getElm",value:function c(){return this._elm}},{key:"setSdwSze",value:function l(e){this._elm.style.setProperty("--sdw-sze",e)}},{key:"show",value:function o(){var e=this;new s.AnimFramePromise(function(){e._elm.classList.remove("dsp-non")}).then(function(){e._elm.classList.remove("opa-0")})}},{key:"hide",value:function i(){var e=this,t=new Promise(function(t,a){e._elm.addEventListener("transitionend",function r(e){return e.target!=this?!1:(this.removeEventListener(e.type,r),this.classList.add("dsp-non"),t(),void 0)})});return this._elm.classList.add("opa-0"),t}},{key:"rmv",value:function m(){var e=this;return this.hide().then(function(){e._elm.parentNode.removeChild(e._elm)})}},{key:"render",value:function d(){return React.createElement("div",{className:"ldg-td-bars pos-abs disabled fade-in-norm",style:this._cnrStyle,ref:this._setElm},React.createElement("div",{className:"sdw-cnr pos-abs"},React.createElement("div",{className:"norm pos-abs-ful dsp-flx"},React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"})),React.createElement("div",{className:"lgt pos-abs-ful dsp-flx"},React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}),React.createElement("div",{className:"bar"}))),React.createElement("div",{className:"td-cnr pos-abs dsp-flx"}))}}]),t}(React.Component),l=r.ldgTdBarsFactory={};l.createObj=function(e){function t(e){a=e}var a=void 0;return ReactDOM.render(React.createElement(c,_extends({},e,{ref:t})),document.createElement("div")),a}}}();