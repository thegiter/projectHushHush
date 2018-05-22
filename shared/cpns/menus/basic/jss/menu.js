"use strict";function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,n,r){return n&&e(t.prototype,n),r&&e(t,r),t}}();!function(){var e="menu_basic",t="/shared/modules/onenote_menu/",n="/shared/modules/menu_items/",r=shpsCmm.util,s=shpsAjax.psdSidebar,o=shpsAjax.pgMgr,a=shpsCmm.domMgr,i=shpsCmm.moduleMgr,c=shpsCmm.reactMgr,l=shpsCmm.evtMgr,u=shpsAjax.lnkMgr,m=o.operator,p=i.get(n,!0).then(function(e){return e.obj.getItems()});shpsAjax.hooks[e]=function(e){var n=shpsAjax.cpnList[e].obj={},o=void 0,a=void 0;n.intro=function(e){o.intro()},n.exit=function(){o.outro();var e=o.getBtn();"true"===e.getAttribute("aria-pressed")&&l.triggerOn(e,"click",!1)},n.onload=function(e){i.get(t,!0).then(function(e){return a=e.obj.onenoteMenuFactory.createObj({clss:"menu-basic sans pos-rel",maxH:"100%"}),s.appendBtn({btnClss:"clickable menu-basic-btn",toggle:!0,iconHtml:"<div>\n					</div>\n					<div>\n					</div>\n					<div>\n					</div>",lblTxt:"Menu",tooltip:"Toggle Menu"})}).then(function(t){o=t;var n=a.getElm();s.appendCtt(n,t.getBtn()).then(function(){e()}),p.then(function(e){function t(e,o,i){var l=[];r.forEachObjProp(e,function(e,r){var s=void 0;if(e.smi){var i=t(e.smi,o+1,r);s=function c(e){var t=i.parentNode;t.insertBefore(i,t.children[0]),a.focus(o+1);var n=e.target.parentNode,r=n.parentNode;r.crtFocusItm&&r.crtFocusItm.classList.remove("focus"),n.classList.add("focus"),r.crtFocusItm=n}}l.push(React.createElement(n,{itm:e,name:r,btnClss:"no-style clickable",btnOnClk:s}))});var u=c.createObj(s,{children:l,name:i}),m=u.getElm();return a.addToPane(o,m),m}var n=function(e){function t(e){_classCallCheck(this,t);var n=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n._setElm=n._setElm.bind(n),n._clss="",n.props.itm.smi&&(n._clss="ta-rgt",n._btn=React.createElement("button",{title:"Show Submenu",type:"button",className:"hvr-bg-bfr pos-rel "+n.props.btnClss,onClick:n.props.btnOnClk},"≫")),n}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function n(e){this._elm=e,this._a=e.getElementsByTagName("a")[0]}}]),_createClass(t,[{key:"componentDidMount",value:function r(){u.register(this._a)}},{key:"getElm",value:function s(){return this._elm}},{key:"render",value:function o(){return React.createElement("li",{className:this._clss,ref:this._setElm},React.createElement("a",{href:this.props.itm.href,className:"pos-rel dsp-ib"},this.props.name),this._btn)}}]),t}(React.Component),s=function(e){function t(e){_classCallCheck(this,t);var n=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n._setElm=n._setElm.bind(n),n}return _inherits(t,e),_createClass(t,[{key:"_setElm",value:function n(e){this._elm=e}}],[{key:"defaultProps",get:function r(){return{name:"."}}}]),_createClass(t,[{key:"getElm",value:function s(){return this._elm}},{key:"render",value:function o(){return React.createElement("ul",{className:"no-style no-indent mrg-0 dsp-flx flx-dir-col",ref:this._setElm},this.props.children,React.createElement("span",{className:"ta-ctr"},">>> "+this.props.name+" <<<"))}}]),t}(React.Component);t({SHPS:{type:"cat",href:"#!homepage/",smi:e}},1),a.focus(1)})})}}}();