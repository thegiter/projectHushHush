var seCmmBatch = {};

(function() {
	var scb = seCmmBatch;
	
	scb.setBtnHdlr = function(hdlr) {
		scb.btnHdlr = hdlr;
	};
	
	scb.style = function(elm, defName, defValue) {
		switch (defName) {
			case 'aomg':
				elm.className = 'aomg-cnr';
			
				if (defValue >= 0) {
					elm.dataset.positive = '1';
				} else {
					elm.dataset.positive = '0';
				}
				
				break;
			case 'pc':
				elm.className = 'pc-cnr';
				
				if (defValue != 1) {
					elm.dataset.negative = 1;
				} else {
					elm.dataset.negative = 0;
				}
				
				break;
			case 'cpivr':
				elm.className = 'cpivr-cnr';
				
				if (defValue <= -.25) {
					elm.dataset.indicator = 'buy';
				} else {
					elm.dataset.indicator = '';
				}
				
				defValue = defValue * 100+'%';
				
				break;
			case 'cpcvr':
				elm.className = 'cpcvr-cnr';
				
				if (defValue > .2) {
					elm.dataset.indicator = 'sell';
				} else {
					elm.dataset.indicator = '';
				}
			case 'pow':
				defValue = defValue * 100+'%';
				
				break;
			default:
		}
		
		elm.textContent = defValue;
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
});