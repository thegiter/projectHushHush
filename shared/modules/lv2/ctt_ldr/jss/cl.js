"use strict";function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,r,n){return r&&e(t.prototype,r),n&&e(t,n),t}}();!function(){var e="lv2_ctt_ldr",t=shpsCmm.moduleMgr;t.hooks[e]=function(e){var t=e.obj={},r=function(e){function t(e){_classCallCheck(this,t);var r=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return r._setElm=r._setElm.bind(r),r}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function r(e){this._elm=e}},{key:"getElm",value:function n(){return this._elm}},{key:"render",value:function o(){var e=this.props.tag;return React.createElement(e,{className:this.props.clss,ref:this._setElm})}}],[{key:"defaultProps",get:function c(){return{tag:"section"}}}]),t}(React.Component),n=t.lv2CttLdrFactory={};n.createObj=function(e,t){function n(e){o=e}var o=void 0;return ReactDOM.render(React.createElement(r,{tag:e,clss:t,ref:n}),document.createElement("div")),o}}}();