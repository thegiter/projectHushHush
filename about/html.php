<?php
	if (!defined('root')) {
		die;
	}

	if (!defined('da')) {
		define('da', false);
	}
?>
<div id="abt-bd" class="bd-wpr abt-bd fade-in-intro-fast opa-0">
	<!--tab cnr here with tab indicator styled as top of the ctt cnr piece-->
	<div class="ctt-cnr lv2-cmm-adp-full-h"><!--ctt cnr will have no top piece-->
		<section id="abt-pgs-wpr" class="ctt">
			<a id="abt-shps-cnr" href="#!about/#の弑す魂の_ps" class="abt-shps-cnr skewed init dsp-non">
				<div class="bg-pic pos-abs-ful">
				</div>
				<div class="abt-xx-ctt">
					<div class="abt-pnl-rgt">
						<p>
							Find out what is <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS, learn its history, read its policies and terms, study its legendary fundation, and so much more...
						</p>
					</div>
				</div>

				<h3><?php
					function wrapL($txt) {
						$ls = str_split($txt);

						$html = '';

						foreach ($ls as $l) {
							$html .= '
							<span>'.$l.'</span>';
						}

						return $html;
					}

					echo wrapL('The Site');
				?>

				</h3>
			</a><a id="abt-sh-cnr" href="#!about/#の弑す魂の" class="abt-sh-cnr skewed init dsp-non">
				<div class="bg-pic">
				</div>
				<h3><?php
					echo wrapL('The Owner');
				?>

				</h3>

				<div class="abt-xx-ctt">
					<div class="abt-pnl-rgt">
						<p>
							Anything and everything you need to know about the owner of this site - Desmond.
						</p>
					</div>
				</div>
			</a>
		</section>

		<div><!--the gloss-->
		</div>
	</div>
</div>
