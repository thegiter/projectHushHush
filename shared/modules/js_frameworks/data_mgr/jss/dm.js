"use strict";function _classCallCheck(t,n){if(!(t instanceof n))throw new TypeError("Cannot call a class as a function")}function _defineProperties(t,n){for(var s=0;s<n.length;s++){var i=n[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function _createClass(t,n,s){return n&&_defineProperties(t.prototype,n),s&&_defineProperties(t,s),t}!function(){var t="data_mgr_fw",n=shpsCmm.moduleMgr,s=shpsCmm.util,i=shpsCmm.domMgr;n.hooks[t]=function(t){var n=t.obj={},r=function(){function t(n,s,i,r){var a=this;_classCallCheck(this,t),this.rowsCnr=n,this.getDataFct=s,this.dataType=i,this.additionalRowsCnrs=[],r&&(this.additionalRowsCnrs=r),this._frag=document.createDocumentFragment(),this._additionalFrags=[],r.forEach(function(){a._additionalFrags.push(document.createDocumentFragment())}),this._numDs=this._numDs.bind(this),this._numAs=this._numAs.bind(this),this._sort=this._sort.bind(this),this._applySort=this._applySort.bind(this),this.ascend=this.ascend.bind(this),this.descend=this.descend.bind(this)}return _createClass(t,[{key:"_createTmpArr",value:function n(){var t=this;this._tmpArr=Array.from(this.rowsCnr.children),this._tmpArr.forEach(function(t,n){t.dMSortOldIdx=n}),this._additionalCnrsTmpArr=[],this.additionalRowsCnrs.forEach(function(n){t._additionalCnrsTmpArr.push(Array.from(n.children))})}},{key:"_numAs",value:function s(t,n){return this.getDataFct(t)-this.getDataFct(n)}},{key:"_numDs",value:function i(t,n){return this.getDataFct(n)-this.getDataFct(t)}},{key:"_sortTmpArr",value:function r(t){this._tmpArr.sort(t)}},{key:"_applyRows",value:function a(t,n){var s=this,i=t+MAX_ROWS,r=this._tmpArr;i>r.length&&(i=r.length),n=n.then(function(){for(var n=function e(t){s.rowsCnr.appendChild(r[t]),s.additionalRowsCnrs.forEach(function(n,i){n.appendChild(s._additionalCnrsTmpArr[i][t])})},a=t;i>a;a++)n(a)}),r.length>i&&this._applyRows(i,n)}},{key:"_applySort",value:function e(){var t=this;this._tmpArr.forEach(function(n,s){t._frag.appendChild(n),t.additionalRowsCnrs.forEach(function(s,i){t._additionalFrags[i].appendChild(t._additionalCnrsTmpArr[i][n.dMSortOldIdx])})}),this.rowsCnr.appendChild(this._frag),this.additionalRowsCnrs.forEach(function(n,s){n.appendChild(t._additionalFrags[s])})}},{key:"_sort",value:function o(n){this._sorting||(this._sorting=!0,this._createTmpArr(),t._clearCnr(this.rowsCnr),this.additionalRowsCnrs.forEach(function(n){t._clearCnr(n)}),this._sortTmpArr(this["_"+this.dataType+n]),this._applySort(),this._sorting=!1)}}],[{key:"_clearCnr",value:function c(t){for(;t.firstChild;)t.removeChild(t.firstChild)}}]),_createClass(t,[{key:"ascend",value:function h(){this._sort("As")}},{key:"descend",value:function d(){this._sort("Ds")}}]),t}();n.Sort=r;var a=function(){function t(n,s,i){_classCallCheck(this,t),this.rowsCnr=n,this.getDataFct=s,this.additionalRowsCnrs=[],i&&(this.additionalRowsCnrs=i),this._typeArr=[],this.apply=this.apply.bind(this)}return _createClass(t,[{key:"_chkCNotMatch",value:function n(t,s,i){var r=this.getDataFct(t,i),a=s.getCFct(s.elm);if("not set"==a)return!1;var e;switch(s.cType){case"=":e=r==a?!1:!0;break;case">":e=r>a?!1:!0;break;case"<":e=a>r?!1:!0;break;case">=":e=r>=a?!1:!0;break;case"<=":e=a>=r?!1:!0;break;default:return!0}return"include"==s.fType?e:!e}},{key:"_applyRows",value:function r(t,n){var s=this,r=this.rowsCnr,a=r.children,e=r.parentNode,o=new DocumentFragment;o.appendChild(r),i.forEachNode(a,function(t,n){s._typeArr.some(function(n,i){return s._chkCNotMatch(t,n,i)})?t.classList.contains("dsp-non")||(t.classList.add("dsp-non"),s.additionalRowsCnrs.forEach(function(t){t.children[n].classList.add("dsp-non")})):t.classList.contains("dsp-non")&&(t.classList.remove("dsp-non"),s.additionalRowsCnrs.forEach(function(t){t.children[n].classList.remove("dsp-non")}))}),e.appendChild(r)}}]),_createClass(t,[{key:"addEvtLsnr",value:function a(t,n,s){switch(n){case"dropdown":t.addEventListener("change",s)}}},{key:"apply",value:function e(){var t=new s.AnimFramePromise;this._applyRows(0,t)}},{key:"setCriterion",value:function o(t,n,s,i,r,a){var e=this;this._typeArr[i]={elm:t,getCFct:n,fType:r,cType:a},this.addEvtLsnr(t,s,function(t){e.apply()})}}]),t}();n.Filter=a}}();