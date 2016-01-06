(function() {
	
})();

shpsCmm.domReady().then(function() {
	const FORMID = 'def-form';
	
	var form = document.getElementById(FORMID);
	
	form.addEventListener('submit', function(evt) {
		evt.preventDefault();
		
		var fd = new FormData(form);
		
		shpsCmm.createAjax('POST', '/se/manual/eval.php', fd, 'json', undefined, undefined, true).then(function(xhr) {
			//xhr.response is the object containing the defs
			var def = xhr.response;
			
			
		});
	});
});