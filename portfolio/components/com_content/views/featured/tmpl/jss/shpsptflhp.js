//each page's js provides an hook function to cache the intro and exit functions to shpsAjax
//the name of this hook function must be set in the page's php and passed to shpsAjax through json
//for example, the hook for homepage would be homepage, and stored in shpsAjax.hooks['homepage']
//shpsAjax.hooks['homepage'] is then defined in the hp.js
//'homepage' must be defined as array['hook'] in the php
(function() {
	include('script', '/portfolio/templates/shps_ptfl/jss/shpsptfl_tmpl.js');
	
	const BG_ID = 'basic-bg';
	
	var bg;
	
	shpsAjax.hooks['portfolio'] = function(url) {
		shpsAjax.pgMgr.cache[url].obj = {};
		
		var pg = shpsAjax.pgMgr.cache[url].obj;
		
		pg.lded = false;

		//the intro function assumes that resources are loaded. which is checked with onload function
		//if you do not check to see resources are loaded first, intro function will cause error if the required resources are not present
		pg.intro = function(argObj) {
			//intro bg_tdcubes
			//the operator is used to open and not directly using cpnList, because operator needs to keep track of opened cpns
			shpsAjax.cpnMgr.operator.open('bg_tdcubes', {
				//onload is called when rsc for this page is loaded,
				//it has no way of knowing if the required cpns are loaded
				//but when intro is run, the required cpns must be loaded
				//so the get the bg cnr in the intro, not in the onload method
				cnr: document.getElementById(BG_ID)
			});
		};
		pg.exit = function(argObj) {
			//destroy auto triggered functions
			//remove window on click
		};
		pg.onload = function(cb) {
			cb();
		};
	};
})();

include('script', '/shared/lv2/featured/jss/featured.js', undefined, undefined, function() {
	var ptflFtd = new lv2FtdModule('shpsptfl-hp', '/portfolio/?option=com_content&view=featured&format=ajax');
});