var isGc = Boolean(window.chrome) && !(Boolean(window.opera) || navigator.userAgent.indexOf(' OPR/') >= 0);

if (isGc) {
	window.stop();
	
	document.cookie = 'ios39jfra=d8ajn';

	window.location.reload();
}