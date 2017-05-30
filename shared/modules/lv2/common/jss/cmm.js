"use strict";function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},_createClass=function(){function e(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,r,n){return r&&e(t.prototype,r),n&&e(t,n),t}}();!function(){var e="lv2_common",t="sticky",r="zi-sticked",n=shpsCmm.moduleMgr,s=shpsCmm.evtMgr,o=shpsCmm.domMgr;n.hooks[e]=function(e){var n=e.obj={},s=function(e){function n(e){_classCallCheck(this,n);var t=_possibleConstructorReturn(this,(n.__proto__||Object.getPrototypeOf(n)).call(this,e));return t._setElm=t._setElm.bind(t),t}return _inherits(n,e),_createClass(n,[{key:"_setElm",value:function s(e){this._elm=e,this._bg=e.getElementsByClassName("lv2-cmm-ttl-bg-cnr")[0],this._ttl=e.getElementsByTagName("h2")[0]}}],[{key:"defaultProps",get:function o(){return{clss:"",bgClss:""}}}]),_createClass(n,[{key:"componentDidMount",value:function l(){if(this.props.cnr){var e=this.props.cnr;e.insertBefore(this._ttl,e.children[0]),e.insertBefore(this._bg,this._ttl)}}},{key:"getElm",value:function c(){return this._elm}},{key:"getTtl",value:function a(){return this._ttl}},{key:"enableSticky",value:function i(){this._ttl.classList.add(t,r),document.documentElement.style.setProperty("--lv2-ttl-fs","var(--lv2-ttl-fs-sml)")}},{key:"disableSticky",value:function u(){this._ttl.classList.remove(t,r),document.documentElement.style.removeProperty("--lv2-ttl-fs")}},{key:"render",value:function m(){return React.createElement("div",{className:"lv2-cmm-ttl-cnr pos-rel of-hid "+this.props.clss,ref:this._setElm},React.createElement("div",{className:"lv2-cmm-ttl-bg-cnr pos-abs top-0 w-100 "+this.props.bgClss}),React.createElement("h2",{className:"lv2-cmm-ttl ta-rgt pos-rel of-hid mrg-0"},React.createElement("a",{href:this.props.href,className:"dsp-ib"},this.props.ttl)))}}]),n}(React.Component),o=function(e){function t(e){_classCallCheck(this,t);var r=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return r._setElm=r._setElm.bind(r),r}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function r(e){this._elm=e}}],[{key:"defaultProps",get:function n(){return{clss:""}}}]),_createClass(t,[{key:"getElm",value:function s(){return this._elm}},{key:"render",value:function o(){return React.createElement("div",{className:"lv2-cmm-para-bg pos-abs rgt-0 top-0 "+this.props.clss,ref:this._setElm})}}]),t}(React.Component),l=n.lv2CmmFactory={};l.createObj=function(e,t){function r(e){n=e}var n=void 0,l=void 0;switch(e){case"TTL":l=React.createElement(s,_extends({},t,{ref:r}));break;case"PARA_BG":l=React.createElement(o,_extends({},t,{ref:r}));break;default:return!1}return ReactDOM.render(l,document.createDocumentFragment()),n}}}();