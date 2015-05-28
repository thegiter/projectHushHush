//sub menu items module, static class
function smiModule(){
}

smiModule.lded = false;
smiModule.smi = {
	Portfolio:{
		type:'cat',
		href:'#!portfolio/'
	},
	News:{
		type:'cat',
		href:'#!news/'
	},
	About:{//	capitalized because they need to match the title
		type:'cat',
		href:'#!about/',
		smi:{
			SHPS:{
				type:'ctt',
				href:'#!about/#の弑す魂の_ps'
			},
			'The Owner':{
				type:'ctt',
				href:'#!about/#の弑す魂の'
			}
		}
	}
};
smiModule.onloadFcts = [];
	
smiModule.onloadDo = function(fct) {
	if (smiModule.lded) {
		fct(smiModule.smi);
	}
	else {
		smiModule.onloadFcts.push(fct);
	}
}

smiModule.ajax = {
	ptfl:false,
	news:false
};

smiModule.ajaxOnload = function(){
	//use temp variable because changing the global class variable should be avoided
	//in case the global one is accessed during the checking process
	var allLded = true;
	
	forEachObjProp(smiModule.ajax, function(value) {	//return will not work here, because return will only terminate this function, and not the outer function
		if (value == false) {
			allLded = false;
		}
	});
	
	if (allLded) {
		smiModule.lded = true;

		smiModule.onloadFcts.forEach(function(value) {
			value(smiModule.smi);
		});
	}
}

// load portfolio ajax sub-menu items
smiModule.ptflAjax = new XMLHttpRequest();
			
smiModule.ptflAjax.addEventListener('readystatechange', function() {
	if (smiModule.ptflAjax.readyState == 4) {
		var result = JSON.parse(smiModule.ptflAjax.responseText);

		if (classOf(result) == 'Object') {//the result should be an assoc array, but assoc arrays in js are just objects
			smiModule.smi['Portfolio'].smi = result;
		}

		smiModule.ajax.ptfl = true;
		
		smiModule.ajaxOnload();
	}
}, false);
			
smiModule.ptflAjax.open('POST', '/portfolio/?option=com_content&view=categories&format=ajax', true);
//Send the proper header information along with the request
smiModule.ptflAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
smiModule.ptflAjax.send('refuri='+window.location.hostname);
// end loading portfolio sub-menu items

//load news ajax smi
smiModule.newsAjax = new XMLHttpRequest();
			
smiModule.newsAjax.addEventListener('readystatechange', function() {
	if (smiModule.newsAjax.readyState == 4) {
		var result = JSON.parse(smiModule.newsAjax.responseText);

		if (classOf(result) == 'Object') {//the result should be an assoc array, but assoc arrays in js are just objects
			smiModule.smi['News'].smi = result;
		}

		smiModule.ajax.news = true;
		
		smiModule.ajaxOnload();
	}
}, false);
			
smiModule.newsAjax.open('POST', '/news/wp-admin/admin-ajax.php', true);
//Send the proper header information along with the request
smiModule.newsAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
smiModule.newsAjax.send('action=get_cats_menu&refuri='+window.location.hostname);
//end loading news ajax smi