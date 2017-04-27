<?php
	if (!defined('root')) {
		die;
	}
?>
	<section id="hp-bd" class="fade-in-norm opa-0 dsp-non">
		<div id="hp-cnr">
			<div class="pos-rel">
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

								$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

								if ($mysqli->connect_error) {
									echo 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
								} else if (!$hp_news_result = $mysqli->query("SELECT * FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 5")) {
									echo 'Posts Retrieving Error ('.$mysqli->errno.')'.$mysqli->error;
								} else {
									// keeps getting the next row until there are no more to get
									function hp_construct_cats_slugs($id) {
										global $mysqli;

										if (!$term_ctg = $mysqli->query("SELECT * FROM wp_terms WHERE term_id='".$id."'")) {
											echo 'Category Retrieving Error ('.$mysqli->errno.')'.$mysqli->error;
										} else {
											$term_ctg->data_seek(0);

											$tc_row = $term_ctg->fetch_assoc();

											$slugs = $tc_row['slug'].'/';

											if (!$tt = $mysqli->query("SELECT * FROM wp_term_taxonomy WHERE term_id='".$id."'")) {
												echo 'Parent Category Retrieving Error ('.$mysqli->errno.')'.$mysqli->error;
											} else {
												$tt->data_seek(0);

												$tt_row = $tt->fetch_assoc();

												if ($tt_row['parent']) {
													$slugs = hp_construct_cats_slugs($tt_row['parent']).$slugs;
												}
											}
										}

										return $slugs;
									}

									$hp_news_result->data_seek(0);

									while ($hp_news_post_row = $hp_news_result->fetch_assoc()) {
										$hp_news_unixtime = strtotime($hp_news_post_row['post_date']);

										if (date('n', $hp_news_unixtime) == 5) {
											$hp_news_M_dot = '';
										} else {
											$hp_news_M_dot = '.';
										}

										if (!$hp_news_terms = $mysqli->query("SELECT * FROM wp_term_relationships WHERE object_id='".$hp_news_post_row['ID']."'")) {
											echo 'Related Terms Retrieving Error ('.$mysqli->errno.')'.$mysqli->error;
										} else {
											$hp_news_terms->data_seek(0);

											while ($hp_news_tr_row = $hp_news_terms->fetch_assoc()) {
												if (!$hp_news_term_types = $mysqli->query("SELECT * FROM wp_term_taxonomy WHERE term_taxonomy_id='".$hp_news_tr_row['term_taxonomy_id']."'")) {
													echo 'Term Types Retrieving Error ('.$mysqli->errno.')'.$mysqli->error;

													break(1);
												} else {
													$hp_news_term_types->data_seek(0);

													while ($hp_news_tt_row = $hp_news_term_types->fetch_assoc()) {
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

	<nav id="hp-bar" class="hp-bar-in-trans hp-bar-in-init hp-bar-sdw-in-init dsp-non">
		<div id="hp-menu-resp" class="hp-menu-resp-trans"><!-- this tag is needed to contain omc and lnks -->
			<?php
				require_once root.'shared/modules/onenote_menu/om.php';

				$omc = new omcModule;

				$omc->clss = 'menu-cnr loading';

				echo $omc;

				require_once root.'shared/modules/ftr/info_lnks/ils.php';

				$ftr_lnks = new ftrInfoLnksModule;

				$ftr_lnks->clss = 'vsb-hid opa-0 fade-in-norm';

				echo '

				'.$ftr_lnks;
			?>

		</div>

		<ul id="hp-menu" class="no-style">
			<?php
				require_once root.'shared/modules/menu_items/mis.php';

				$menu_html = '';//clear variable $menu_html in case another menu was produced and $menu_html is not empty

				//if tooltip's css is not loaded, then the tooltips simply would not show
				//so we don't need  to hide it, they are hidden by default
				//so we just need to load them when ready and not need to hide/intro them

				$omc = new omcModule;

				$omc->clss = 'sub-menu-cnr loading';

				foreach ($mis as $mi => $mi_ppt) {								//ppt: properties, not the microsoft shit
					$menu_html .= '<li>
						<a href="'.$mi_ppt['href'].'">'.$mi_ppt['name'].'</a>';
					//create_tooltip($cnrTag, $cnrId, $cnrClss, $cnrAttr, $hvrTag, $hvrId, $hvrClss, $hvrAttr, $ctt, $tooltip) syntax
					if ($mi_ppt['smi']) {
						$menu_html .= '<span class="sep">
						</span>'.$omc;
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
					require root.'shared/modules/ftr/cr/cr.php';
				?></span>

				<?php
					echo $ftr_lnks;
				?>
			</div>
		</div>
	</nav>
