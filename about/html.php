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
	<div class="ctt-cnr pos-rel lv2-cmm-adp-full-h"><!--ctt cnr will have no top piece-->
		<section id="abt-pgs-wpr" class="ctt">
			<a id="abt-shps-cnr" href="#!about/#の弑す魂の_ps" class="abt-shps-cnr skewed init dsp-non">
				<div class="bg-pic pos-abs-ful">
				</div>
				<div class="abt-xx-ctt">

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
				<div class="bg-pic pos-abs-ful">
				</div>
				<h3><?php
					echo wrapL('The Owner');
				?>

				</h3>

				<div class="abt-xx-ctt">

				</div>
			</a>
		</section>

		<div><!--the gloss-->
		</div>
	</div>
</div>
