function getFlashMovie(movieName) {
	var isIE = navigator.appName.indexOf("Microsoft") != -1;

	return (isIE) ? window[movieName] : document[movieName];
}

window.addEventListener('load', dfp, false);

function dfp() {
	document.cookie = 'lseikl='+document.getElementById('fpdfls').callfls();

	window.location.reload();
}