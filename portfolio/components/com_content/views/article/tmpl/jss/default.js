include('script', '/shared/tools/jss/tools.js', undefined, undefined, function() {	//include the js at the beginning so browser can start downloading as soon as possible
	toolsModule.bindToTop('ptfl-article-bd-wpr');
});

(function() {	
//header onload
	var hdrId = 'ptfl-artic-hdr';
	
	checkElmOnload(hdrId, function() {
		setTimeout(function() {
			var title_elmId = 'ptfl-article-title';
		
			checkElmOnload(title_elmId, function() {
				//animate title
				var title_elm = document.getElementById(title_elmId);
				
				title_elm.addEventListener('transitionend', function() {
					var cat_elmId = 'ptfl-article-header-cat';
				
					checkElmOnload(cat_elmId, function() {
						mdf_cls(cat_elmId, 'remove', 'opa-0');
					});
					
					var pub_elmId = 'ptfl-article-header-publish';
					
					checkElmOnload(pub_elmId, function() {
						mdf_cls(pub_elmId, 'remove', 'opa-0');
					});
				}, false);
				
				mdf_cls(title_elm, 'remove', 'trans-init');
			});
		}, 2000);	//2 sec delay to take into account the general time it takes the browser to render
	});
//end header onload
	
	var showArticBtnId = 'ptfl-artic-show-btn';
	
//show artic btn onload
	var showArticBtnImgArr = ['norm', 'gloss', 'glow', 'sdw'];
	
	add_imgOnload('/portfolio/components/com_content/views/article/tmpl/imgs/show_artic_btn//*replace*/.png', showArticBtnImgArr, function() {
		checkElmOnload(showArticBtnId, function() {
			mdf_cls(showArticBtnId, 'remove', 'dsp-non');
			mdf_cls(showArticBtnId, 'remove', 'opa-0');
		});
	});
//end show artic btn onload

//show artic btn onclick
	var ttlCnrId = 'ptfl-article-bd-wpr';	//total cnr id 
	
	checkElmOnload(ttlCnrId, function() {
		var showArticBtn = document.getElementById(showArticBtnId);	
		var ttlCnr = document.getElementById(ttlCnrId);
		
		showArticBtn.addEventListener('click', function() {
			transScroll(ttlCnr, document.documentElement.clientHeight, undefined, 2);
		}, false);
	});
//end show artic btn onclick
})();