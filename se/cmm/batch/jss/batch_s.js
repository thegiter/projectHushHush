var seCmmBatch = {};

(function() {
	var scb = seCmmBatch;
	
	scb.setBtnHdlr = function(hdlr) {
		scb.btnHdlr = hdlr;
	};
	
	scb.ldTkrs = function(se) {
		return shpsCmm.createAjax('post', '/se/cmm/batch/get_tbl.php', 'se='+se, 'json').then(function(xhr) {
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

shpsCmm.domReady().then(function() {
	var scb = seCmmBatch;
	
	var slForm = document.getElementById('se-form');

	scb.btn = document.getElementById('ss-btn');

	scb.msgCnr = document.getElementById('msg-cnr');
	scb.tMsgCnrs = scb.msgCnr.getElementsByTagName('span');
	scb.body = document.getElementsByClassName('tbody')[0];

	//get data structure
	scb.defIdxs = [];
	
	scb.hdrCells = document.getElementsByClassName('thead')[0].children;
	
	forEachNodeItem(scb.hdrCells, function(td, idx) {
		scb.defIdxs[idx] = td.dataset.acro;
	});
	
	slForm.addEventListener('submit', function(evt) {
		evt.preventDefault();
		
		var fd = new FormData(slForm);
		scb.se = fd.get('se');
		
		scb.ldTkrs(scb.se);
	});
	
	scb.getData = function(evt) {
		scb.btn.disabled = true;
		
		scb.tkrRows = scb.getTkrRows();
		scb.maxRows = scb.tkrRows.length;
		
		//populate rows
		var rowsHtml = '';
		
		for (var c = 0; c < scb.maxRows; c++) {
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
	
	var tkrCol = document.getElementsByClassName('tkr-col')[0];
	
	forEachNodeItem(document.getElementsByClassName('se-sortable'), function(elm) {
		var idx;
		
		forEachNodeItem(scb.hdrCells, function(cell, cellIdx) {
			if (cell == elm.parentNode) {
				idx = cellIdx - 2;
			}
		});
		
		elm.dMSort = new shpsCmm.dataMgr.sort(scb.body, function(row) {
			if (row.children.length == 0) {
				return 0;
			}
			
			return row.children[idx].textContent;
		}, 'num', [tkrCol]);
		
		elm.addEventListener('click', toggleSort);
	});
});