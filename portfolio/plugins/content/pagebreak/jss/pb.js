(function() {
	var page_regEx = /^page\d+$/;	//page hash pattern
	var pages = new Array();		// array to hold the page elements
	var curPage_ttl
	var pages_cnr
	var pb_nav_cnr
	
	function loading_anim() {		//this function activates the loading animations
	
	}
	
	function cancel_loading_anim() {
		
	}
	
	function load_ajax(page_ttl, callback) {	//function that loads the ajax if the page has not been loaded yet
		var pageNum = page_ttl.replace('page', '') - 1;
		//showall=1 to load the full article, limitstart=1 to load page 2, limitstart=2 to load page 3, etc
		var param = 'option=com_content&view=article&format=ajax&showall=&limitstart=' + pageNum + '&refuri=' + window.location.hostname;

		var ajax = new XMLHttpRequest();

		ajax.addEventListener('readystatechange', function() {
			if (ajax.readyState == 4) {
				var result = ajax.responseText.split('<!--shps separator-->');	//json.parse gives an error on the html
				
				//create new element and put in to the array
				pages[page_ttl] = new Array();
				
				var newPage = pages[page_ttl]['elm'] = document.createElement('div');
				
				mdf_cls(newPage, 'add', 'pb-ctt-wpr pos-abs dsp-non');
				
				pages_cnr.appendChild(newPage);
				
				pages[page_ttl]['nav'] = result[1];	//1: nav
				
				newPage.innerHTML = result[0];	//0: html
				
				callback();
			}
		}, false);

		ajax.open('POST', '', true);
		//Send the proper header information along with the request
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send(param);
	}
	
	function change_page(page_ttl, callback) { //the function that performs the page changing animations
		pages_cnr.style.height = pages_cnr.offsetHeight + 'px';	//preserve the height before setting content to position absolute
		
		var newPage = pages[page_ttl]['elm'];
		var curPage = pages[curPage_ttl]['elm'];
		
		mdf_cls(curPage, 'add', 'pos-abs');			//setting content to position absolute
		
		mdf_cls(newPage, 'remove', 'pb-ctt-wpr');	//remove the transition animation effect before transforming the element

		newPage.style.transform = 'translatey(' + pages_cnr.offsetHeight + 'px)';	//move the element out of the container to hide it

		mdf_cls(newPage, 'remove', 'dsp-non');		//remove display none, so height can be measured

		//set the distance to be the greater of the two
		var distance = (newPage.offsetHeight > curPage.offsetHeight) ? newPage.offsetHeight : curPage.offsetHeight;
		
		//if new page number is smaller than current page, set negative distance
		if (page_ttl.replace('page', '') < curPage_ttl.replace('page', '')) {
			distance = -distance;
		}
		
		newPage.style.transform = 'translatey(' + distance + 'px)';	//move the element to its initial position for the slide in anim

		pages_cnr.style.height = newPage.offsetHeight + 'px';	//setting a new height, which triggers the height changing animation
		
		curPage.addEventListener('transitionend', function(e) {
			e.target.removeEventListener(e.type, arguments.callee);	//self removing eventlistener function
			
			mdf_cls(newPage, 'remove', 'pos-abs');		//remove position absolute
		
			pages_cnr.style.height = 'auto';						//set height back to auto after content sliding animation is done
			
			mdf_cls(curPage, 'add', 'dsp-non');						//hide the slided content
			
			pb_nav_cnr.innerHTML = pages[page_ttl]['nav'];
			
			curPage_ttl = page_ttl;
				
			callback();
		}, false);
		
		curPage.style.transform = 'translatey(' + -distance + 'px)';			//moves the content up/down / slide effect
		
		mdf_cls(newPage, 'add', 'pb-ctt-wpr');									//add back the transition animation effect, added here to give the previous transform code some time to execute
		
		newPage.style.transform = 'initial';									//moves the element up/down / slide effect
	}
	
	function new_page(e) {			// function triggered on hashchange
		var page_ttl = window.location.hash.replace('#', '');	//get the hash portion
		
		if (page_regEx.test(page_ttl) && (page_ttl != curPage_ttl)) {	//test if the hashchange is a page hash and the requested page is not already the current page, if so do following, if not do nothing
			loading_anim();
			
			if (pages[page_ttl] == undefined) {			//check whether if the page is loaded
				load_ajax(page_ttl, function() {					//if not, load page through ajax first
					change_page(page_ttl, cancel_loading_anim);
				});
			}
			else {
				change_page(page_ttl, cancel_loading_anim);
			}
		}
	}
	
	window.addEventListener('load', function() {
		curPage_ttl = 'page1';
		
		pages[curPage_ttl] = new Array();
		
		pages[curPage_ttl]['elm'] = document.getElementById('ptfl-article-pg-1');
		
		pb_nav_cnr = document.getElementById('pb-nav-cnr');
		
		pages[curPage_ttl]['nav'] = pb_nav_cnr.innerHTML;
		
		pages_cnr = pages[curPage_ttl]['elm'].parentNode;
		
		new_page();
		
		window.addEventListener('hashchange', new_page, true);
	}, false);
})();