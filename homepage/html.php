<?php
	if (!defined('root')) {
		die;
	}
?>
	<section id="hp-bd" class="fade-in-norm opa-0 dsp-non">
		<div id="hp-cnr">
			<div>
				<div id="hp-bigpic-base" class="hp-bigpic-in-init">
				</div>
			</div><div>
				<div id="hp-news-cnr" class="col-has-mrg">
					<table id="news-cnr">
						<caption>
							<h3 class="img-cnr">
								<a href="/news/" class="img-cnr dsp-ib" title="Go to the news section.">
									<img src="/shared/imgs/tsp.gif" alt="Newest News" id="hp-nn" />
								</a>
							</h3>
						</caption>
						
						<tbody>
							<?php
								require_once root.'shared/phps/db_news.php';
								
								if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
									echo 'User Connection Error';
								}											//or die(mysql_error());
								else {
									if (!@mysql_select_db(DB_NAME)) {
										echo 'Database Connection Error';
									}											//mysql_error();
									else {
										$hp_news_result = @mysql_query("SELECT * FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 5");
										
										if (!$hp_news_result) {
											echo 'Posts Retrieving Error';
										}									//or mysql_error();
										else {
											// keeps getting the next row until there are no more to get
											function hp_construct_cats_slugs($id) {
												$term_ctg = @mysql_query("SELECT * FROM wp_terms WHERE term_id='".$id."'");	// or mysql_error();
												
												if (!$term_ctg) {
													echo 'Category Retrieving Error';
												}
												else {
													$tc_row = mysql_fetch_array( $term_ctg );
													$slugs = $tc_row['slug'].'/';
													
													$tt = @mysql_query("SELECT * FROM wp_term_taxonomy WHERE term_id='".$id."'");	// or mysql_error();
											
													if (!$tt) {
															echo 'Parent Category Retrieving Error';
													}
													else {
														$tt_row = mysql_fetch_array( $tt );
												
														if ($tt_row['parent']) {
															$slugs = hp_construct_cats_slugs($tt_row['parent']).$slugs;
														}
													}
												}
													
												return $slugs;
											}
											
											while ($hp_news_post_row = mysql_fetch_array( $hp_news_result )) {
												$hp_news_unixtime = strtotime($hp_news_post_row['post_date']);
												
												if (date('n', $hp_news_unixtime) == 5) {
													$hp_news_M_dot = '';
												}
												else {
													$hp_news_M_dot = '.';
												}
												
												$hp_news_terms = @mysql_query("SELECT * FROM wp_term_relationships WHERE object_id='".$hp_news_post_row['ID']."'");	// or mysql_error();
												
												if (!$hp_news_terms) {
													echo 'Related Terms Retrieving Error';
												}
												else {
													while ($hp_news_tr_row = mysql_fetch_array( $hp_news_terms )) {
														$hp_news_term_types = @mysql_query("SELECT * FROM wp_term_taxonomy WHERE term_taxonomy_id='".$hp_news_tr_row['term_taxonomy_id']."'");	// or mysql_error();
														
														if (!$hp_news_term_types) {
															echo 'Term Types Retrieving Error';
															
															break(1);
														}
														else {
															while ($hp_news_tt_row = mysql_fetch_array( $hp_news_term_types )) {
																if ($hp_news_tt_row['taxonomy'] == 'category') {
																	$hp_news_cats_slugs = hp_construct_cats_slugs($hp_news_tt_row['term_id']);
																	
																	break(2);
																}
															}
														}
													}
													
													// Print out the contents of each row
													echo '<tr>
														<td>
															'.date('d M'.$hp_news_M_dot.' Y', $hp_news_unixtime).'
														</td><td>
															<a href="/news/'.$hp_news_cats_slugs.$hp_news_post_row['post_name'].'/" title="Read the news on its own page." target="_blank">'.$hp_news_post_row['post_title'].'</a>
														</td>
													</tr>';
												}
											}
										}
									}
								}
							?>
							
						</tbody>
						
						<tfoot>
							<td colspan="2">
								<a id="hp-news-more" href="/news/" title="Go to the news section.">more...</a>
							</td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</section>
	
	<nav id="hp-bar" class="hp-bar-in-init dsp-non">
		<div id="hp-menu-resp" class="hp-menu-resp-trans"><!-- this tag is needed to contain omc and lnks -->
			<?php
				require_once root.'shared/onenote_menu_cnr/omc.php';
					
				$omc = new omcModule;
				
				$omc->clss = 'menu-cnr loading';
			
				echo $omc;
				
				require_once root.'shared/footer/info_lnks/info_lnks.php';
				
				$ftr_lnks = ftrInfoLnksModule::getHtml();
					
				echo '
					
				'.$ftr_lnks;
			?>

		</div>
		<?php
			require_once root.'shared/menu/btn/btn.php';
					
			$mb = new menuBtnModule;
					
			$mb->id = 'hp-menu-btn';
			$mb->clss = 'no-style clickable';
				
			echo $mb;
		?>
		
		
		<ul id="hp-menu" class="no-style">
			<?php
				require_once root.'shared/mis/mis.php';
				
				$menu_html = '';								//clear variable $menu_html in case another menu was produced and $menu_html is not empty
					
				require_once root.'shared/tooltip/tooltip.php';
					
				$omc = new omcModule;
					
				$omc->clss = 'sub-menu-cnr loading';
					
				foreach ($mis as $mi => $mi_ppt) {								//ppt: properties, not the microsoft shit
					$menu_html .= '<li>
						<a href="'.$mi_ppt['href'].'">'.$mi_ppt['name'].'</a>';
					//create_tooltip($cnrTag, $cnrId, $cnrClss, $cnrAttr, $hvrTag, $hvrId, $hvrClss, $hvrAttr, $ctt, $tooltip) syntax	
					if ($mi_ppt['smi']) {
						$menu_html .= '<span class="sep">
						</span>'.create_tooltip('span', null, 'expand-btn-cnr', null, 'button', null, 'no-style clickable', 'type="button"', '<div class="ver">
							</div>
							<div class="hor">
							</div>', 'Toggle sub-menus.').'
						'.$omc;
					}
					
					$menu_html .= '
					</li>
					';
				}
				
				echo $menu_html;
			?>
		</ul>

		<div id="hp-inf-cnr-wpr">
			<div id="hp-inf-cnr">
				<span id="hp-cr"><?php
					require root.'shared/footer/cr/cr.php';
				?></span>
					
				<?php
					echo $ftr_lnks;
				?>
			</div>
		</div>
	</nav>