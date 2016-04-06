(function() {
	var ajax = new XMLHttpRequest();

	ajax.addEventListener('readystatechange', function() {
		if (ajax.readyState == 4) {
			if (load == 'loading') {
				loadingCycleEnd = onloadAnim;
			}
				
			load = true;
		}
	}, false);

	ajax.open('POST', 'shared/', true);
	//Send the proper header information along with the request
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	var param
	
	ajax.send(param);
})();