window.addEventListener('load', function() {
	var tab = window.location.search.substring(1).match(/tab=.*(&)?/);
	
	tab = tab[0].replace('tab=', '');
	tab = tab.replace(/%20/g, ' ');

	shps_tap_show(tab);
}, false);