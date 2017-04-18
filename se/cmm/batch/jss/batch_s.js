const seCmmBatch = {};

(function() {
	const scb = seCmmBatch;

	scb.setBtnHdlr = function(hdlr) {
		scb.btnHdlr = hdlr;
	};

	scb.ldTkrs = function(se) {
		return shpsCmm.ajaxMgr.createAjax('post', '/se/cmm/batch/get_tbl.php', 'se='+se, 'json').then(function(xhr) {
			if (xhr.response.status) {
				document.getElementsByClassName('tkr-col')[0].innerHTML += xhr.response.html;

				scb.btn.disabled = false;
			} else {
				scb.msgCnr.textContent = xhr.responseText;
			}
		});
	};

	scb.getTkrRows = function() {
		return document.getElementsByClassName('tkr-col')[0].children;
	};
})();

shpsCmm.domMgr.domReady().then(function() {
	const scb = seCmmBatch;

	const slForm = document.getElementById('se-form');

	scb.btn = document.getElementById('ss-btn');

	scb.msgCnr = document.getElementById('msg-cnr');
	scb.tMsgCnrs = scb.msgCnr.getElementsByTagName('span');
	scb.body = document.getElementsByClassName('tbody')[0];

	//get data structure
	scb.defIdxs = [];

	scb.hdrCells = document.getElementsByClassName('thead')[0].children;

	Array.prototype.forEach.call(scb.hdrCells, function(td, idx) {
		scb.defIdxs[idx] = td.dataset.acro;
	});

	slForm.addEventListener('submit', function(evt) {
		evt.preventDefault();

		const fd = new FormData(slForm);
		scb.ses = fd.getAll('se');

		scb.ses.forEach(function(se) {
			scb.ldTkrs(se);
		});
	});

	scb.getData = function(evt) {
		scb.btn.disabled = true;

		scb.tkrRows = scb.getTkrRows();
		scb.maxRows = scb.tkrRows.length;

		//populate rows
		let rowsHtml = '';

		for (let c = 0; c < scb.maxRows; c++) {
			rowsHtml += '<tr></tr>';
		}

		scb.body.innerHTML = rowsHtml;

		scb.btnHdlr(evt);
	};

	scb.btn.addEventListener('click', scb.getData);

	function toggleSort(evt) {
		if (this.dMSort.order == 'ds') {
			this.dMSort.order = 'as';

			this.dMSort.ascend();
		} else {
			this.dMSort.order = 'ds';

			this.dMSort.descend();
		}
	}

	const tkrCol = document.getElementsByClassName('tkr-col')[0];

	let Sort, Filter;

	shpsCmm.moduleMgr.get('/shared/modules/js_frameworks/data_mgr/').then(function (m) {
		Sort = m.obj.Sort;
		Filter = m.obj.Filter;

		Array.prototype.forEach.call(document.getElementsByClassName('se-sortable'), function(elm) {
			let idx;

			Array.prototype.forEach.call(scb.hdrCells, function(cell, cellIdx) {
				if (cell == elm.parentNode) {
					idx = cellIdx - 2;
				}
			});

			elm.dMSort = new Sort(scb.body, function(row) {
				if (row.children.length == 0) {
					return 0;
				}

				const cell = row.children[idx];

				let ctt = cell.textContent;

				const tmp = cell.querySelector('[name="def_value"]');

				//css query selector, because getElementsByName can not be used on elements, only document
				//returns null if no match found, select decendants
				if (tmp) {
					ctt = tmp.value;
				}

				return /(\-)?[\d\.]+/.exec(ctt)[0];
			}, 'num', [tkrCol]);

			elm.addEventListener('click', toggleSort);
		});

		const filterElms = [
			{
				elm: document.getElementById('advice-filter'),
				getCFct(elm) {
					let value = elm.value;

					if (value == undefined) {
						value = 'not set';
					}

					return value;
				},
				iType: 'dropdown',
				fType: 'include',
				cType: '='
			},
			{
				elm: document.getElementById('pc-filter'),
				getCFct(elm) {
					var value = elm.value;

					if (value == undefined) {
						value = 'not set';
					}

					return value;
				},
				iType: 'dropdown',
				fType: 'include',
				cType: '='
			},
			{
				elm: document.getElementById('lper-filter'),
				getCFct(elm) {
					var match = /[\d\.]+/.exec(elm.value);

					var value;

					if (match) {
						value = match[0];
					} else {
						value = elm.value;
					}

					return value;
				},
				iType: 'dropdown',
				fType: 'exclude',
				cType: '>='
			},
			{
				elm: document.getElementById('wacodr-filter'),
				getCFct(elm) {
					var match = /[\d\.]+/.exec(elm.value);

					var value;

					if (match) {
						value = match[0];
					} else {
						value = elm.value;
					}

					if (value == undefined) {
						value = 'not set';
					}

					return value;
				},
				iType: 'dropdown',
				fType: 'exclude',
				cType: '>='
			},
			{
				elm: document.getElementById('fpigr-filter'),
				getCFct(elm) {
					var match = /[\d\.]+/.exec(elm.value);

					var value;

					if (match) {
						value = match[0];
					} else {
						value = elm.value;
					}

					if (value == undefined) {
						value = 'not set';
					}

					return value;
				},
				iType: 'dropdown',
				fType: 'include',
				cType: '<='
			}
		];

		const filter = new Filter(scb.body, function(row, idx) {
			if (row.children.length == 0) {
				return '';
			}

			return row.children[idx].textContent;
		}, [tkrCol]);

		filterElms.forEach(function(filterObj) {
			let idx;

			Array.prototype.forEach.call(scb.hdrCells, function(cell, cellIdx) {
				if (cell == filterObj.elm.parentNode) {
					idx = cellIdx - 2;
				}
			});

			filter.setCriterion(filterObj.elm, filterObj.getCFct, filterObj.iType, idx, filterObj.fType, filterObj.cType);
		});
	});
});
