<?php
	if (!defined('root')) {
		die;
	}
	
	if (!defined('da')) {
		define('da', false);
	}
?>
<div id="abt-bd" class="bd-wpr abt-bd fade-in-intro-fast opa-0">
	<h2>
		<span>
			About
		</span>	
	</h2>
	
	<div class="ctt-cnr">
		<section class="ctt">
			<a id="abt-shps-cnr" href="#!about/#の弑す魂の_ps" class="abt-shps-cnr skewed init dsp-non">
				<div class="bg-pic">
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
			</a>
			<a id="abt-sh-cnr" href="#!about/#の弑す魂の" class="abt-sh-cnr skewed init dsp-non">
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
			<!--need to be manipulated by js, therefore can not use after psudo element-->
			<div><!--the gloss-->
			</div>
		</section>
	</div>
	<div class="bg-overlay">
	</div>
</div>