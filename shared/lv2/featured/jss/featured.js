//we define this module as a class, the reason is that we can have any number of objects from this class
//we can then manipulate each object individually
function lv2FtdModule(id, url) {
	this.id = id;
	
	this.teo = new ttlElmOnload(id);
	
	this.teo.addImgs('/shared/lv2/featured/imgs//*replace*/', [
		'ptn.jpg',
		'bg/caption_bd_sdw.jpg',
		'bg/caption_hd.png',
		'bg/ttl_ass.png',
		'bg/ttl_bd_sdw.jpg'
	]);
	
	this.teo.start();
	//end ftd ttl elm onload
	
	this.gen_ftdCtt = function(imgUrl, imgWdt, imgHgt, itemLink, txt, txtPstn, txtWdt, txtHTML) {
		//readjust image width and height
		//if width smaller than ttlWidth
		//get expand factor
		if (imgWdt < this.ttlWdt) {
			var ef = this.ttlWdt / imgWdt;
			
			//expand width and height
			imgWdt *= ef;
			imgHgt *= ef;
		}
		
		//if height smaller than ttlHeight
		if (imgHgt < this.ttlHgt) {
			var ef = this.ttlHgt / imgHgt;
			
			//expand width and height
			imgWdt *= ef;
			imgHgt *= ef;
		}
		
		var avl_wdt = this.ttlWdt;		// keeps track of available width left for columns
		var avl_wdt_img = avl_wdt;
		
		var style;

		if (txt) {
			style = 'txt';

			//txt position initialization
			if ((typeof txtPstn == 'undefined') || (txtPstn != ('lft' || 'ctr' || 'rgt'))) {
				var predefined_txtPstns = new Array('lft', 'ctr', 'rgt');
								
				txtPstn = predefined_txtPstns[getRandomInt(0,3)];
			}
			
			// txt width initialization, if width not exist or not valid, set new width, this width is then used to set new image width
			if (txtPstn == 'ctr') {
				var txt_wdt_max = this.ttlWdt - 2 * this.img_wdt_min;
										
				if ((typeof txtWdt == 'undefined') || (txtWdt > txt_wdt_max)) {
					txtWdt = getRandomInt(img_wdt_min, txt_wdt_max + 1); //max is exclusive, there must plus 1
				}
										
				this.img_wdt_max -= txtWdt;
			}
			else {
				if ((typeof txtWdt == 'undefined') || (txtWdt > this.img_wdt_max)) {
					txtWdt = getRandomInt(this.img_wdt_min, this.img_wdt_max + 1);
				}
										
				this.img_wdt_max = this.ttlWdt - txtWdt;
			}
			
			avl_wdt_img = this.ttlWdt - txtWdt;
			//end txt width initialization
		}
		else {
			var predefined_styles = new Array('even', 'random');
									
			style = predefined_styles[getRandomInt(0, 2)];
		}

		//begin columns
		var col_wdt = [];
		var col_arr_cnt = 0;					//keep track of number of columns generated, 0 = 1 column

		var col_lft = 0;						//keeps track of the left position
		
		var img_ctr_top = Math.round(imgHgt/2);
		var img_ctr_lft = Math.round(imgWdt/2);

		var ftdCtt_html = '';

		while (avl_wdt) {
			var this_col = 'img';								//defines whether this column is a txt col or an img col

			switch (style) {
				case 'txt':
					switch (txtPstn) {
						case 'lft':
							if (col_arr_cnt === 0) {
								this_col = 'txt';
							}
													
							break;									//break and continue works differently in js from php
						case 'ctr':
							if (col_arr_cnt == 1) {
								this_col = 'txt';
							}
													
							break;									//in php, break would break the outer loop, which in this case, would be the while loop, thus continue is used to skip the switch statement
						case 'rgt':
							if (avl_wdt < (txtWdt + this.img_wdt_min)) {
								this_col = 'txt';
							}
					}
								
					break;											//however, in js, continue would skip the while loop, and enter the next iteration of the while loop
				case 'even':
					this_col = 'img';
					
					break;
				case 'random':
					this_col = 'img'; //temp code placeholder
			}

			var blocks_html = '';

			switch (this_col) {
				case 'img':
					//begin calculating for the image column
					col_wdt[col_arr_cnt] = getRandomInt(this.img_wdt_min, this.img_wdt_max + 1);	//generates the column with a randomed width, the random width is designed so that there is always at least 2 columns

					if ((avl_wdt_img - col_wdt[col_arr_cnt]) < this.img_wdt_min) {
						col_wdt[col_arr_cnt] = avl_wdt_img;
					}

					//calculating for the image position left
					var block_distance_lft = this.ctr_lft - col_lft;
					var img_lft = img_ctr_lft - block_distance_lft;
					//end calculating for the image position left
					//end calculating for the image column
											
					var block_arr_cnt = 0;					//keep track of the blocks generated, 0 = 1
					var blocks = [];				//generate the array which will hold the top and height numbers
					var col_avl_hgt = this.ttlHgt;
											
					while (col_avl_hgt >= this.block_hgt_min) {
						//begin calculating for the block(s) inside the column
						var block_top;
						
						if (block_arr_cnt === 0) {
							var block_top_max;
							
							if (col_wdt[col_arr_cnt] >= (2 * this.img_wdt_min)) {	//if the column is 2 min-col wide
								block_top_max = 0.1 * this.ttlHgt;					//then top is within 10% of total height
							}
							else {
								block_top_max = this.ttlHgt - this.block_hgt_min;
							}
													
							block_top = getRandomInt(0, block_top_max + 1);		//randomly generates the top
							col_avl_hgt -= block_top;
						}
						else {
							block_top += blocks[block_arr_cnt - 1]['hgt'];
						}
												
						var block_hgt = getRandomInt(this.block_hgt_min, this.ttlHgt - block_top);	//randomly generates the height
						
						col_avl_hgt -= block_hgt;

						//calculate background image position top
						var block_distance_top = this.ctr_top - block_top;
						var img_top = img_ctr_top - block_distance_top;
						//end calculating for the block(s) inside the column
						
						//convert to percentages for responsive design
						var imgHgt_percent = imgHgt / block_hgt * 100;
						
						//left = (container_width - image_width) * percentage
						//percentage = left / (container_width - image_width)
						img_lft = (-img_lft) / (col_wdt[col_arr_cnt] - imgWdt) * 100;
						img_top = (-img_top) / (block_hgt - imgHgt) * 100;
						
						blocks_html += '\
						<div class="ftd-ctt-block" style="top:' + block_top + 'px;height:' + block_hgt + 'px;">\
							<div class="ftd-ctt-img-cnr">\
								<a href="' + itemLink +'" title="Open the portfolio article page." target="_blank" class="ftd-ctt-img" style="background-image:url('+imgUrl+');background-position:'+img_lft+'% '+img_top+'%;background-size:auto '+imgHgt_percent+'%">\
									' + lv2FtdModule.contour_html + '\
								</a>\
							</div>\
						</div>';
												
						blocks[block_arr_cnt] = {'top': block_top, 'hgt': block_hgt};	//store them in array, associative arrays are objects, and vice versa
						
						block_arr_cnt++;
					}
	
					avl_wdt_img -= col_wdt[col_arr_cnt];

					break;
				case 'txt':
					col_wdt[col_arr_cnt] = txtWdt;
											
					blocks_html = txtHTML;
			}

			ftdCtt_html += '<div class="ftd-ctt-col" style="width:' + col_wdt[col_arr_cnt] + 'px">	<!-- col stands for column -->' + blocks_html +'\
			</div>';

			avl_wdt -= col_wdt[col_arr_cnt];		// new available width
			col_lft += col_wdt[col_arr_cnt];		//prepare the left position for the next column

			col_arr_cnt++;							//increment the number of columns generated
		}
		//end columns

		return ftdCtt_html;
	}
	
	// load ftd content
	var ftdCtt_ajax = new XMLHttpRequest();
	var ftd = this;
	
	ftdCtt_ajax.addEventListener('readystatechange', function() {
		if (ftdCtt_ajax.readyState == 4) {
			var ftdCtt = JSON.parse(ftdCtt_ajax.responseText);
			
			if (classOf(ftdCtt) == 'Array') {
				//preload images
				//should be changed to image onload for each ftd item
				//each ftd item will display a loading screen until the image for that item is loaded
				ftdCtt.forEach(function(value) {
					imgPreload(value['img_url']);
				});
				
				function modOnload() {
					function teoOnload() {
						ftd.cnr = document.getElementById(ftd.id);

						var cttCnr = ftd.cnr.getElementsByClassName('ftd-ctt-cnr')[0];
	
						ftd.ttlWdt = cttCnr.offsetWidth;		//total width
						ftd.ttlHgt = cttCnr.offsetHeight;		//total height
						
						ftd.block_hgt_min = Math.round(ftd.ttlHgt / 3);	//at most 3 blocks
						ftd.img_wdt_min = Math.round(ftd.ttlWdt / 6);		//at most 6 columns
						ftd.img_wdt_max = ftd.ttlWdt - ftd.img_wdt_min;		//at least 2 columns

						ftd.ctr_top = Math.round(ftd.ttlHgt / 2);
						ftd.ctr_lft = Math.round(ftd.ttlWdt / 2);
						
						var wpr = cttCnr.children[0];
						
						wpr.innerHTML = ftd.gen_ftdCtt(ftdCtt[0]['img_url'], ftdCtt[0]['img_wdt'], ftdCtt[0]['img_hgt'], ftdCtt[0]['item_link'], ftdCtt[0]['txt'], ftdCtt[0]['txt_pstn'], ftdCtt[0]['txt_wdt'], ftdCtt[0]['txt_html']);
						
						//the opa anim should be changed to height opening anim
						ftd.cnr.classList.remove('opa-0');
					}
					
					if (ftd.teo.lded) {
						teoOnload();
					} else{
						ftd.teo.callback = teoOnload;
					}
				}
				
				if (lv2FtdModule.modLded) {
					modOnload();
				} else{
					lv2FtdModule.modOnloadFcts.push(modOnload);
				}
			}
		}
	}, false);
	
	ftdCtt_ajax.open('POST', url, true);
	//Send the proper header information along with the request
	ftdCtt_ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ftdCtt_ajax.send('refuri='+window.location.hostname+'&refpg='+window.location.hash);
	// end loading ftd content
}

lv2FtdModule.modOnloadFcts = [];
		
(function() {
	function trigger_modOnload () {	//modules onload
		if (typeof lv2FtdModule.contour_html != 'undefined') {
			lv2FtdModule.modLded = true;
			
			lv2FtdModule.modOnloadFcts.forEach(function(fct) {
				ftc();
			});
		}
	}

	//load modules
	var ajax_param = 'refuri=' + window.location.hostname;

	//load the contour module html
	var contour_ajax = new XMLHttpRequest();

	contour_ajax.addEventListener('readystatechange', function() {
		if (contour_ajax.readyState == 4) {
			lv2FtdModule.contour_html = '<div class="contour-wpr">\
			' + contour_ajax.responseText + '\
			</div>';
			
			trigger_modOnload();
		}
	}, false);

	contour_ajax.open('POST', '/shared/contour/contour.php', true);
	//Send the proper header information along with the request
	contour_ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	contour_ajax.send(ajax_param);
	//end loading contour
})();