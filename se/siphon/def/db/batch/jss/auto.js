shpsCmm.domReady().then(function() {
	var scb = seCmmBatch;
	
	Promise.all([
		scb.ldTkrs('SHSE'),
		scb.ldTkrs('SZSE')
	]).then(function() {
		shpsCmm.evtMgr.triggerOn(scb.btn, 'click');
	});
});